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

        $organizations = Organization::select('id', 'name', 'address', 'contact_num', 'email', 'created_at')->get();
        
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
    public function update(StoreOrganization $request1, StorePrincipal $request2, $id): JsonResponse {

        $validatedRequest1 = (object)$request1->validated();
        $validatedRequest2 = (object)$request2->validated();

        DB::beginTransaction();
        try {

            Validator::make(['id' => $id], ['id' => 'required|string'])->validate();
            $id = decrypt($id);

            $organization = Organization::find($id);

            if (!$organization) {
                return response()->json(['message' => 'Organization not found.'], 404);
            }

            // Beware about this hardcoded id : Assumption -> princial user role id = 2
            $principal = User::where('u_tp_id', '2')->where('id',$organization->principle_id)->get();
            
            if (!$principal) {
                return response()->json(['message' => 'Principal not found.'], 404);
            }

            $principal->name = $validatedRequest2->pName;
            $principal->contact_num = $validatedRequest2->pContact;
            $principal->email = $validatedRequest2->pEmail;
            $principal->username = $validatedRequest2->pEmail;
            $principal->password = !empty($validatedRequest2->pPassword) ? $validatedRequest2->pPassword : $principal->password;
            $principal->save();

            $organization->name = $validatedRequest1->oName;
            $organization->address = $validatedRequest1->oAddress;
            $organization->contact_num = $validatedRequest1->oContact;
            $organization->email = $validatedRequest1->oEmail;
            $organization->save();

            DB::commit();
            return response()->json(['message' => 'Organization successfully created.'],200);

        } catch (Exception $e) {
            DB::rollBack();
            $this->logException($e);
            return response()->json(['message' => "Failed to update organization."],417);
        }
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
        
        try {
            $id = decrypt($id);
            
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

    // Find one organization
    public function find(string $id): JsonResponse {

        /**
         * When delete an organization
         *  - Delete organization
         *  - Delete principal
         *  - Delete teachers
         *  - Delete students
         *  - Delete any other configurations
         */

        Validator::make(['id' => $id], ['id' => 'required|string'])->validate();
        
        try {
            $id = decrypt($id);
            
            $organization = Organization::select('organizations.id as oId', 'organizations.name as oName', 'address as oAddress', 'organizations.contact_num as oContact', 'organizations.email as oEmail', 'users.id as uId', 'users.name as pName', 'users.contact_num as pContact',  'users.email as pEmail')
            ->join('users', 'organizations.principle_id', '=', 'users.id')
            ->where('organizations.id', $id)
            ->first();

            if (!$organization) {
                return response()->json(['message' => 'Organization not found.'], 404);
            }
            
            $organization->e_id = Crypt::encrypt($organization->oId);
            $organization->pId = Crypt::encrypt($organization->uId);
            $organization->__unset('oId');
            $organization->__unset('uId');
            
            return response()->json(['organization' => $organization, 'message' => "Organization data fetched."]);

        } catch (Exception $e) {
            DB::rollBack();
            $this->logException($e);
            return response()->json(['message' => "Failed to load the organization data.", 'ex' => $e->getMessage()],417);
        }

        return response()->json();

    }

}
