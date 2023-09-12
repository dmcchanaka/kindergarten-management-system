<?php 
namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\UserPermission;
use App\Models\UserType;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserRoleController extends Controller {

    public function userRoleList(Request $request){

        $userRoleList = UserType::get();
        $userRoleList->transform(function($role){
            return [
                'role_id'=>$role->u_tp_id,
                'description'=>$role->user_type,
            ];
        });
        return response()->json([
            'result'=>true,
            'userRoles' => $userRoleList
        ],200);
    }

    public function permissionList(Request $request){
        try {
            $permissions = Permission::get();
            $permissions->transform(function($per){
                return [
                    'value'=>$per->getKey(),
                    'label'=>$per->name,
                ];
            });
            return response()->json([
                'result'=>true,
                'permissions' => $permissions
            ],200);
        } catch(Exception $e){
            return response()->json([
                'result'=>false,
                'errors' => $e->getMessage()
            ],404);
        }
    }

    public function userRoleSave(Request $request){
        // if(count($request->permissions) === 0){
        //     return response()->json([
        //         'result'=>false,
        //         'errors' => 'Please select atleast one permission'
        //     ],404);
        // } elseif($request->userRole == ''){
        //     return response()->json([
        //         'result'=>false,
        //         'errors' => 'Please enter user type'
        //     ],404);
        // }

        try {
            $this->validateInput($request);

            DB::beginTransaction();

            $userType = UserType::create([
                'user_type'=>$request->userRole
            ]);

            foreach($request->permissions AS $permission){
                $allocatePermission = UserPermission::create([
                    'u_tp_id'=>$userType->getKey(), 
                    'p_id'=>$permission
                ]);
            }
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

    /**
     * Validate the input data.
     *
     * @param Request $request
     * @throws \Illuminate\Validation\ValidationException
     */
    private function validateInput(Request $request)
    {
        $request->validate([
            'userRole' => 'required|string',
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'integer|exists:permissions,p_id',
        ], [
            'userRole.required' => 'Please enter user type.',
            'permissions.required' => 'Please select at least one permission.',
            'permissions.*.integer' => 'Invalid permission ID.',
            'permissions.*.exists' => 'Invalid permission selected.',
        ]);
    }
}