<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\Organization;
use App\Models\User;
use App\Traits\UserAllocation;
use App\Validators\CustomValidator;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ParentController extends Controller
{
    use UserAllocation;

    public function classRoomsAssociatedWithOrganization(Request $request){
        $user = Auth::user();
        try {
            $classRoomQuery = ClassRoom::query();
            if(isset($request['organizationId']) && $request['organizationId'] != NULL){
                $classRoomQuery->where('org_id', $request['organizationId']);
            }
            $classRooms = $classRoomQuery->get();
            if($classRooms->isNotEmpty()){
                $classRooms->transform(function($cls){
                    return [
                        'value'=>$cls->getKey(),
                        'label'=>$cls->name,
                    ];
                });
                return response()->json([
                    'result'=>true,
                    'classRoomList' => $classRooms
                ],200);
            } else {
                return response()->json([
                    'result' => false,
                    'errors' => ['Dont have allocated class rooms']
                ], 400);
            }
            
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

    public function parentRegistration(Request $request) {
        $data = $request->all();

        $rules = [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone_number' => ['required', 'string', 'numeric', 'digits_between:10,25'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:3', 'confirmed'],
        ];

        $attributes = [
            'first_name' => 'first name',
            'last_name' => 'last name',
            'address' => 'address',
            'email' => 'email',
            'phone_number' => 'phone number',
            'username' => 'username',
            'password' => 'password',
        ];

        $validator = CustomValidator::validate($data, $rules, $attributes);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            $formattedErrors = [];

            foreach ($errors as $field => $messages) {
                $formattedErrors[$field] = $messages[0];
            }

            return response()->json([
                'result' => false,
                "errors" => $formattedErrors,
            ], 403);
        }

        try {
            DB::beginTransaction();
            $firstName = $request['first_name'];
            $lastName = $request['last_name'];
            $name = !empty($firstName) && !empty($lastName)
            ? $firstName . ' ' . $lastName
            : (!empty($firstName) 
            ? $firstName : $lastName);
            $user = User::create([
                'name'=>$name,
                'email'=>$request['email'],
                'u_tp_id'=>config('kindergarten.type_parent'),
                'username'=>$request['username'],
                'password'=>Hash::make($request['password']),
                'first_name'=>$request['first_name'],
                'last_name'=>$request['last_name'],
                'address'=>$request['address'],
                'phone_number'=>$request['phone_number'],
            ]);
            DB::commit();

            return response()->json([
                'result' => true,
                'message' => 'Record has been successfuly added'
            ], 200);
        } catch(Exception $e){
            DB::rollBack();
            return response()->json([
                'result'=>false,
                'errors' => $e->getMessage()
            ],500);
        }
    }

    public function fetchParentsList(Request $request){
        $user = Auth::user();
        try {
            $studentsInfo = $this->getUserRoleRelatedStudents($user);
            if(!$studentsInfo->isEmpty()){
                $parents = User::where('u_tp_id', config('kindergarten.type_parent'))->whereIn('id', $studentsInfo->pluck('guardian_id')->all())->get();
                $parents->transform(function($parent){
                    return [
                        'id'=>$parent->id,
                        'first_name'=>$parent->first_name,
                        'last_name'=>$parent->last_name,
                        'phone_number'=>$parent->phone_number,
                        'email'=>$parent->email,
                        'username'=>$parent->username,
                        'user_role'=>$parent->userRole(),
                        'address'=>$parent->address,
                    ];
                });
                return response()->json([
                    'result' => true,
                    'parents' => $parents
                ], 200);
            } else {
                return response()->json([
                    'result' => false,
                    'errors' => ['Dont have registered parents']
                ], 400);
            }

            // $parentsQuery = User::where('u_tp_id', config('kindergarten.type_parent'));
            // if(!$studentsInfo->isEmpty()){
            //     $parentsQuery->whereIn('id', $studentsInfo->pluck('guardian_id')->all());
            // }
            // $parents = $parentsQuery->get();
            // if(!$parents->isEmpty()){
            //     $parents->transform(function($parent){
            //         return [
            //             'id'=>$parent->id,
            //             'first_name'=>$parent->first_name,
            //             'last_name'=>$parent->last_name,
            //             'phone_number'=>$parent->phone_number,
            //             'email'=>$parent->email,
            //             'username'=>$parent->username,
            //             'user_role'=>$parent->userRole(),
            //             'address'=>$parent->address,
            //         ];
            //     });
            //     return response()->json([
            //         'result' => true,
            //         'parents' => $parents
            //     ], 200);
            // } else {
            //     return response()->json([
            //         'result' => false,
            //         'errors' => ['Dont have registered parents']
            //     ], 400);
            // }
            
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
}
