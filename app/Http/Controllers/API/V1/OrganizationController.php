<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use App\Http\Requests\StoreOrganization;
use App\Models\Organization;
use App\Traits\ExceptionLogTrait;
use App\Validators\CustomValidator;
use Exception;

/**
 *  THIS CLASS ONLY FOR SUPER ADMINS
 */

class OrganizationController extends Controller
{

    use ExceptionLogTrait;

    // Get organizations
    public function index(): JsonResponse
    {

        $organizations = Organization::select('id', 'name', 'address', 'contact_num', 'email', 'created_at')->get();

        $encryptedOrganizations = $organizations->map(function ($organization) {
            $organization->e_id = Crypt::encrypt($organization->id);
            // $organization->__unset('id');
            return $organization;
        });

        return response()->json([
            'organizations' => $encryptedOrganizations,
        ], 200);
    }

    // Create organization
    public function create(Request $request)
    {
        $data = $request->all();

        $rules = [
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email',
            'principal_id' => 'required|integer'
        ];

        $attributes = [
            'name' => 'organization name',
            'email' => 'organization email',
            'principal_id' => 'principal'
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

            Organization::create([
                'name' => $request->name,
                'address' => $request->address,
                'contact_num' => $request->contact_num,
                'email' => $request->email,
                'principal_id' => $request->principal_id
            ]);

            return response()->json(['message' => 'New organization successfully created.', 'status' => 200]);
        } catch (Exception $e) {
            $this->logException($e);
            return response()->json(['message' => "Failed to create organization.", 'ex'=> $e], 417);
        }
    }

    // Update organization
    public function update(Request $request, $id)
    {
        $data = $request->all();

        $rules = [
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email',
            'principal_id' => 'required|integer'
        ];

        $attributes = [
            'name' => 'organization name',
            'email' => 'organization email',
            'principal_id' => 'principal'
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

        DB::beginTransaction();
        try {

            Validator::make(['id' => $id], ['id' => 'required|string'])->validate();
            $id = decrypt($id);

            $organization = Organization::find($id);

            if (!$organization) {
                return response()->json(['message' => 'Organization not found.'], 404);
            }

            $organization->name = $request->name;
            $organization->address = $request->address;
            $organization->contact_num = $request->contact_num;
            $organization->email = $request->email;
            $organization->principal_id = $request->principal_id;
            $organization->save();

            DB::commit();
            return response()->json(['message' => 'Organization successfully updated.', 'status' => 200]);
        } catch (Exception $e) {
            DB::rollBack();
            $this->logException($e);
            return response()->json(['message' => "Failed to update organization."], 417);
        }
    }

    // Delete organization
    public function delete(string $id): JsonResponse
    {

        Validator::make(['id' => $id], ['id' => 'required|string'])->validate();

        try {
            $id = decrypt($id);

            $organization = Organization::find($id);

            if (!$organization) {
                return response()->json(['message' => 'Organization not found.'], 404);
            }

            $organization->delete();
            return response()->json(['message' => "Organization deleted successfully.", 'status' => 204]);
        } catch (Exception $e) {
            DB::rollBack();
            $this->logException($e);
            return response()->json(['message' => "Failed to delete the organization."], 417);
        }

        return response()->json();
    }

    // Find one organization
    public function find(string $id): JsonResponse
    {
        Validator::make(['id' => $id], ['id' => 'required|string'])->validate();

        try {
            $id = Crypt::decrypt($id);

            $organization = Organization::select('id', 'name', 'principal_id', 'address', 'contact_num', 'email')
                ->where('id', $id)
                ->first();

            if (!$organization) {
                return response()->json(['message' => 'Organization not found.'], 404);
            }

            $organization->e_id = Crypt::encrypt($organization->id);
            $organization->e_principal_id = Crypt::encrypt($organization->principal_id);
            $organization->__unset('id');
            //$organization->__unset('principal_id');

            return response()->json(['organization' => $organization, 'message' => "Organization data fetched.", 'status' => 200]);
        } catch (Exception $e) {
            DB::rollBack();
            $this->logException($e);
            return response()->json(['message' => "Failed to load the organization data.", 'ex' => $e->getMessage()], 417);
        }

        return response()->json(['message' => 'error']);
    }
}
