<?php 
namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller {

    //create login function
    public function login(Request $request){
        //validate email & password and redirect back
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                "errors" => $validator->errors(),
            ]);
        }

        //try to get login access using email & password
        $loginStatus = (Auth::attempt(['username'=>$request['username'],'password'=>$request['password']]));
        if($loginStatus){
            $user = Auth::user();
            $userInfo = [
                'token' => $user->createToken("APITOKEN")->plainTextToken,
                'userId' => $user->getKey(),
                'name' => $user->name,
                'email' => $user->email,
                'userAccessLevel' => $user->u_tp_id,
                'userRole'=>$user->userRole()
            ];

            return response()->json([
                'result'=>true,
                'userInfo' => $userInfo
            ],200);
        } else {
            return response()->json([
                'result'=>false,
                'errors' => 'The Entered Password is incorrect'
            ],404);
        }
    }

    public function verifyToken(Request $request){
        $user = $request->user();
        if($user){
            $userInfo = [
                'token' => $request->api_token,
                'userId' => $user->getKey(),
                'name' => $user->name,
                'email' => $user->email,
                'userAccessLevel' => $user->u_tp_id,
                'userRole'=>$user->userRole()
            ];

            return response()->json([
                'result'=>true,
                'userInfo' => $userInfo
            ],200);
        } else {
            return response()->json([
                'result'=>false,
                'errors' => 'The Entered Password is incorrect'
            ],404);
        }
    }
}