<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;

use App\Http\Requests\StoreOrganization;
use App\Http\Requests\StorePrincipal;

use App\Models\User;
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
    public function index(): JsonResponse {

        $organizations = Organization::all();
        
        $encryptedOrganizations = $organizations->map(function ($organization) {
            $organization->e_id = Crypt::encrypt($organization->id);
            $organization->__unset('id');
            return $organization;
        });

        return response()->json([
            'organizations' => $encryptedOrganizations,
        ],200);
    }

    // Create organization
    public function create(StoreOrganization $request1, StorePrincipal $request2): JsonResponse {

        $validatedRequest1 = (object)$request1->validated();
        $validatedRequest2 = (object)$request2->validated();
                
        DB::beginTransaction();
        try {

            $user = User::create([
                'name' => $validatedRequest2->pName,
                'contact_num' => $validatedRequest2->pContact,
                'email' => $validatedRequest2->pEmail,
                'u_tp_id' => 2,
                'username' => $validatedRequest2->pEmail,
                'password' => Hash::make($validatedRequest2->pPassword)
            ]);
            
            Organization::create([
                'name' => $validatedRequest1->oName,
                'address' => $validatedRequest1->oAddress,
                'contact_num' => $validatedRequest1->oContact,
                'email' => $validatedRequest1->oEmail,
                'principle_id' => $user->id
            ]);

            DB::commit();

            return response()->json(['message' => 'New organization successfully created.'],200);

        } catch (Exception $e) {
            DB::rollBack();
            $this->logException($e);
            return response()->json(['message' => "Failed to create organization."],417);
        }

    }

    // Update organization
    public function update(Request $request): JsonResponse {

        return response()->json();
    }

    // Delete organization
    public function delete(string $id): JsonResponse {

        /**
         * When delete an organization
         *  - Delete organization
         *  - Delete principal
         *  - Delete teachers
         *  - Delete students
         *  - Delete any other configurations
         */

        Validator::make(['id' => $id], ['id' => 'required|string'])->validate();

        $id = decrypt($id);

        try {
            
            $organization = Organization::find($id);

            if (!$organization) {
                return response()->json(['message' => 'Organization not found.'], 404);
            }

            $organization->delete();
            return response()->json(['message' => "Organization deleted successfully."]);

        } catch (Exception $e) {
            DB::rollBack();
            $this->logException($e);
            return response()->json(['message' => "Failed to delete the organization."],417);
        }

        return response()->json();

    }

}
