<?php 
namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\UserType;
use Illuminate\Http\Request;

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
}