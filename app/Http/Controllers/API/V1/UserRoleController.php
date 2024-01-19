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

        $userRoleList = UserType::whereNotIn('u_tp_id', [config('kindergarten.type_super_admin')])->get();
        $userRoleList->transform(function($role){
            $permissions = UserPermission::where('u_tp_id', $role->u_tp_id)->get();
            $permissions->transform(function($p){
                return [
                    'id'=>$p->p_id,
                    'name'=>$p->getPermissionNameById($p->p_id)
                ];
            });
            return [
                'role_id'=>$role->u_tp_id,
                'description'=>$role->user_type,
                'permissions'=>$permissions
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
        try {
            $this->validateInput($request);

            DB::beginTransaction();

            $userType = UserType::updateOrCreate([
                'user_type'=>$request->userRole
            ]);

            foreach($request->permissions AS $permission){
                $allocatePermission = UserPermission::create( [
                    'u_tp_id' => $userType->getKey(),
                    'p_id' => $permission
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

    public function userRoleUpdate(Request $request){
        try {
            $this->validateInput($request);

            DB::beginTransaction();

            //check availability
            $userType = UserType::find($request->userRoleId);
            if(!$userType){
                return response()->json([
                    'result'=>false,
                    'errors' => $request->userRole. ' is not available'
                ],404);
            } else {
                $userType->user_type = $request->userRole;
                $userType->save();

                // Find the existing UserPermission records for the user type
                $existingPermissions = UserPermission::where('u_tp_id', $userType->getKey())->get();
                foreach ($existingPermissions as $existingPermission) {
                    $existingPermission->delete();
                }
                //add new permissions
                foreach($request->permissions AS $permission){
                    $allocatePermission = UserPermission::create( [
                        'u_tp_id' => $userType->getKey(),
                        'p_id' => $permission
                    ]);
                }

                DB::commit();
                return response()->json([
                    'result' => true,
                    'message' => 'Record has been successfuly updated'
                ], 200);
            }
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