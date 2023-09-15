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
            $organization->__unset('id');
            return $organization;
        });

        return response()->json([
            'organizations' => $encryptedOrganizations,
        ], 200);
    }

    // Create organization
    public function create(StoreOrganization $request): JsonResponse
    {

        $validatedRequest = (object)$request->validated();

        try {

            Organization::create([
                'name' => $validatedRequest->name,
                'address' => $validatedRequest->address,
                'contact_num' => $validatedRequest->contact_num,
                'email' => $validatedRequest->email,
                'principle_id' => $validatedRequest->principal_id
            ]);

            return response()->json(['message' => 'New organization successfully created.', 'status' => 200]);
        } catch (Exception $e) {
            $this->logException($e);
            return response()->json(['message' => "Failed to create organization."], 417);
        }
    }

    // Update organization
    public function update(StoreOrganization $request, $id): JsonResponse
    {

        $validatedRequest = (object)$request->validated();

        DB::beginTransaction();
        try {

            Validator::make(['id' => $id], ['id' => 'required|string'])->validate();
            $id = decrypt($id);

            $organization = Organization::find($id);

            if (!$organization) {
                return response()->json(['message' => 'Organization not found.'], 404);
            }

            $organization->name = $validatedRequest->oName;
            $organization->address = $validatedRequest->oAddress;
            $organization->contact_num = $validatedRequest->oContact;
            $organization->email = $validatedRequest->oEmail;
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

            $organization = Organization::select('id', 'name', 'principle_id', 'address', 'contact_num', 'email')
                ->where('id', $id)
                ->first();

            if (!$organization) {
                return response()->json(['message' => 'Organization not found.'], 404);
            }

            $organization->e_id = Crypt::encrypt($organization->id);
            $organization->e_principle_id = Crypt::encrypt($organization->principle_id);
            $organization->__unset('id');
            //$organization->__unset('principle_id');

            return response()->json(['organization' => $organization, 'message' => "Organization data fetched.", 'status' => 200]);
        } catch (Exception $e) {
            DB::rollBack();
            $this->logException($e);
            return response()->json(['message' => "Failed to load the organization data.", 'ex' => $e->getMessage()], 417);
        }

        return response()->json(['message' => 'error']);
    }
}
