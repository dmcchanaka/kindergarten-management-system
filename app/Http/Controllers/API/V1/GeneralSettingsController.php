<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use App\Traits\UserAllocation;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GeneralSettingsController extends Controller
{
    use UserAllocation;

    public function fetchGeneralSettings(Request $request){
        $user = Auth::user();
        try {

            $settings = $this->getGeneralSettingsByUser($user);
            return response()->json([
                'result' => true,
                'settings' => $settings
            ], 200);
        } catch (QueryException $e) {
            // Handle database query exceptions
            return response()->json([
                'result' => false,
                'errors' => ['Database error: ' . $e->getMessage()]
            ], 500);
        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json([
                'result' => false,
                'errors' => ['An error occurred: ' . $e->getMessage()]
            ], 500);
        }
    }

    public function saveLogo(Request $request){
        try {
            // Start a database transaction
            DB::beginTransaction();
    
            $organizationId = $request['organizationId'];
            $userId = $request['userId'];
    
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $imageName = md5($file->getClientOriginalName()) . '.'.$file->getClientOriginalExtension();
    
                Storage::put('/public/organization/logo/'.$imageName,file_get_contents($request->file('image')));
                $url = Storage::url('public/organization/logo/'.$imageName);
    
                GeneralSetting::updateOrInsert(
                    ['organization_id' => $organizationId],
                    ['organization_id' => $organizationId, 'added_by' => $userId, 'logo_url' => $url]
                );
    
                // Commit the transaction
                DB::commit();
    
                return response()->json([
                    'result'=>true,
                    'logo_url' => url('/') .$url
                ],200);
            } else {
                // Rollback the transaction
                DB::rollback();
    
                return response()->json([
                    'result'=>false,
                    'errors'=>['Something went wrong!. Please try again']
                ],404);
            }
        } catch (\Exception $e) {
            // Handle exceptions and rollback the transaction
            DB::rollback();
    
            return response()->json([
                'result'=>false,
                'errors'=>['An error occurred: '.$e->getMessage()]
            ],500);
        }
    }

    public function saveUiSettings(Request $request){
        try {
            // Start a database transaction
            DB::beginTransaction();
    
            $organizationId = $request['organizationId'];
            $userId = $request['userId'];

            $settings = GeneralSetting::updateOrInsert(
                ['organization_id' => $organizationId],
                [
                    'organization_id' => $organizationId, 
                    'added_by' => $userId, 
                    'background_color' => $request['backgroundColor'],
                    'heading_color' => $request['headerColor'],
                    'text_color' => $request['textColor'],
                ]
            );
            DB::commit();
            return $this->fetchGeneralSettings($request);
        } catch (\Exception $e) {
            // Handle exceptions and rollback the transaction
            DB::rollback();
    
            return response()->json([
                'result'=>false,
                'errors'=>['An error occurred: '.$e->getMessage()]
            ],500);
        }
    }
}
