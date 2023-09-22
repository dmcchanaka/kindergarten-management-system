<?php 
namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\User;
use App\Models\UserPermission;
use App\Validators\CustomValidator;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller {

    //create login function
    public function login(Request $request){
        try {
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

                $permissionQuery = Permission::query();
                //user permission
                if(config('kindergarten.type_super_admin') == $user->u_tp_id){
                    
                } else {
                    $userPermission = UserPermission::where('u_tp_id', $user->u_tp_id)->get();
                    if($userPermission){
                        $permissionQuery->whereIn('p_id', $userPermission->pluck('p_id')->all());
                    }
                }
                $permissions = $permissionQuery->select(['p_id AS id', 'name'])->get();

                return response()->json([
                    'result'=>true,
                    'userInfo' => $userInfo,
                    'userPermissions' => $permissions,
                ],200);
            } else {
                return response()->json([
                    'result'=>false,
                    'errors' => 'The Entered Password is incorrect'
                ],404);
            }
        } catch(Exception $e){
            return response()->json([
                'result' => false,
                'errors' => 'Database connection error: ' . $e->getMessage()
            ], 500);
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

    public function usersList(Request $request){
        try {
            $user = Auth::user();
            $userQuery = User::query();
            if(config('kindergarten.type_super_admin') != $user->u_tp_id){
                $userQuery->whereNotIn('u_tp_id', [config('kindergarten.type_super_admin')]);
            }
            $users = $userQuery->get();
            $users->transform(function($user){
                return [
                    'id'=>$user->id,
                    'name'=>$user->name,
                    'contact_number'=>$user->contact_num,
                    'email'=>$user->email,
                    'username'=>$user->username,
                    'user_role'=>$user->userRole()
                ];
            });
            return response()->json([
                'result'=>true,
                'userList' => $users
            ],200);

        } catch(Exception $e){
            return response()->json([
                'result' => false,
                'errors' => 'Database connection error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function userRegistration(Request $request){
        $data = $request->all();

        $rules = [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'u_tp_id' => ['required'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:3', 'confirmed'],
        ];

        $attributes = [
            'first_name' => 'first name',
            'last_name' => 'last name',
            'address' => 'address',
            'u_tp_id' => 'user role',
            'email' => 'email',
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
                'u_tp_id'=>$request['u_tp_id'],
                'username'=>$request['username'],
                'password'=>Hash::make($request['password']),
                'first_name'=>$request['first_name'],
                'last_name'=>$request['last_name'],
                'address'=>$request['address']
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

        // $validator = Validator::make($request->all(), [
        //     'first_name' => ['required', 'string', 'max:255'],
        //     'last_name' => ['required', 'string', 'max:255'],
        //     'address' => ['required', 'string', 'max:255'],
        //     'u_tp_id' => ['required'],
        //     'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        //     'username' => ['required', 'string', 'max:255', 'unique:users'],
        //     'password' => ['required', 'string', 'min:3', 'confirmed'],
        // ]);

        // if ($validator->fails()) {
        //     return response()->json([
        //         'result' => false,
        //         "errors" => $validator->errors(),
        //     ], 403);
        // }
    }
}