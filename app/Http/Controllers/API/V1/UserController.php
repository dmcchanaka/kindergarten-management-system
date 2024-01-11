<?php 
namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use App\Models\Organization;
use App\Models\Permission;
use App\Models\User;
use App\Models\UserPermission;
use App\Models\UserType;
use App\Traits\UserAllocation;
use App\Validators\CustomValidator;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller {

    use UserAllocation;

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
                    'firstName' => $user->first_name,
                    'lastName' => $user->last_name,
                    'email' => $user->email,
                    'userAccessLevel' => $user->u_tp_id,
                    'userRole'=>$user->userRole()
                ];

                /**Organization info */
                $organizationInfo = [];
                $organizationInfo = $this->getUserAllocatedOrganization($user);
                /**General settings info */
                $settings = $this->getGeneralSettingsByUser($user);
                /**User Permissions */
                $permissions = $this->getAllocatedPermissionsByUser($user);
                /**User Menu */
                $menuItems = $this->getAllocatedMainMenuByUser($user);

                return response()->json([
                    'result'=>true,
                    'userInfo' => $userInfo,
                    'userPermissions' => $permissions,
                    'settings' => $settings,
                    'organizationInfo' => $organizationInfo,
                    'userMenu' => $menuItems
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
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function verifyToken(Request $request){
        try {
            $user = $request->user();
    
            if ($user) {
                $userInfo = [
                    'token' => $request->api_token,
                    'userId' => $user->getKey(),
                    'name' => $user->name,
                    'firstName' => $user->first_name,
                    'lastName' => $user->last_name,
                    'email' => $user->email,
                    'userAccessLevel' => $user->u_tp_id,
                    'userRole' => $user->userRole(),
                    'logo' => $user->logo_url
                ];
    
                /**Organization info */
                $organizationInfo = $this->getUserAllocatedOrganization($user);
                /**General settings info */
                $settings = $this->getGeneralSettingsByUser($user);
                /**User Permissions */
                $permissions = $this->getAllocatedPermissionsByUser($user);
                /**User Menu */
                $menuItems = $this->getAllocatedMainMenuByUser($user);
    
                return response()->json([
                    'result' => true,
                    'userInfo' => $userInfo,
                    'userPermissions' => $permissions,
                    'settings' => $settings,
                    'organizationInfo' => $organizationInfo,
                    'userMenu' => $menuItems
                ], 200);
            } else {
                return response()->json([
                    'result' => false,
                    'errors' => 'The Entered Password is incorrect'
                ], 404);
            }
        } catch (\Exception $e) {
    
            // Return a generic error response to the client
            return response()->json([
                'result' => false,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    // Get users by type
    public function getUsers(Request $request, $userType): JsonResponse{

        // Get existing user types
        $existingUserTypes = UserType::select('user_type')->pluck('user_type')->toArray();
        
        if(!in_array($userType, $existingUserTypes)){
            return response()->json(['message' => 'Invalid user type'],400);
        }

        $users = User::join('user_type', 'users.u_tp_id', '=', 'user_type.u_tp_id')
                ->select('users.id as value','users.name')
                ->where('user_type.user_type', $userType)
                ->get();

        
        return response()->json(['users' => $users]);

    }

    public function usersList(Request $request){
        try {
            $user = Auth::user();
            $userQuery = User::query();
            $usersInfo = $this->getUserList($user);
            if(!$usersInfo->isEmpty()){
                $userQuery->whereIn('id', $usersInfo->pluck('id')->all());
                $users = $userQuery->orderBy('u_tp_id', 'ASC')->get();
                $users->transform(function($user){
                    return [
                        'id'=>$user->id,
                        'first_name'=>$user->first_name,
                        'last_name'=>$user->last_name,
                        'contact_number'=>$user->contact_num,
                        'email'=>$user->email,
                        'username'=>$user->username,
                        'user_role'=>$user->userRole(),
                        'address'=>$user->address,
                    ];
                });
                return response()->json([
                    'result'=>true,
                    'userList' => $users
                ],200);
            } return response()->json([
                'result' => false,
                'errors' => ['Dont have registered parents']
            ], 400);
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
            'u_tp_id' => ['required'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:3', 'confirmed'],
        ];

        $attributes = [
            'first_name' => 'first name',
            'last_name' => 'last name',
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
                'address'=>$request['address'],
                'phone_number'=>$request['phone_number']
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

    public function userUpdate(Request $request){
        $data = $request->all();

        $rules = [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'u_tp_id' => ['required'],
            // 'address' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($request['id'])],
            // 'phone_number' => ['required', 'string', 'numeric', 'digits_between:10,25'],
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($request['id'])],
        ];

        if ($request->filled('password')) {
            $rules['password'] = ['required', 'string', 'min:3', 'confirmed'];
        }

        $attributes = [
            'first_name' => 'first name',
            'last_name' => 'last name',
            'u_tp_id' => 'user role',
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

            $user = User::findOrFail($request['id']);
            $user->name = $name;
            $user->first_name = $request->input('first_name');
            $user->last_name = $request->input('last_name');
            $user->address = $request->input('address');
            $user->email = $request->input('email');
            $user->phone_number = $request->input('phone_number');
            $user->username = $request->input('username');
            if ($request->filled('password')) {
                $user->password = Hash::make($request->input('password'));
            }
            $user->save();
            DB::commit();
    
            return response()->json([
                'result' => true,
                'message' => 'Record has been successfully updated'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'result' => false,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function userProfileUpdate(Request $request){
        $data = $request->all();

        $rules = [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($request['id'])],
        ];

        $attributes = [
            'first_name' => 'first name',
            'last_name' => 'last name',
            'email' => 'email',
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

            $user = User::findOrFail($request['id']);
            $user->name = $name;
            $user->first_name = $request->input('first_name');
            $user->last_name = $request->input('last_name');
            $user->email = $request->input('email');
            $user->save();
            DB::commit();
    
            return response()->json([
                'result' => true,
                'message' => 'Record has been successfully updated'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'result' => false,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function userProfilePasswordUpdate(Request $request){
        $data = $request->all();

        $rules = [
            'password' => ['required', 'string', 'min:3', 'confirmed'],
        ];

        $attributes = [
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

            $user = User::findOrFail($request['id']);
            $user->password = Hash::make($request->input('password'));
            $user->save();
            DB::commit();
    
            return response()->json([
                'result' => true,
                'message' => 'Record has been successfully updated'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'result' => false,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function userLogoUpdate(Request $request){
        try {
            // Start a database transaction
            DB::beginTransaction();
    
            $userId = $request['userId'];
    
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $imageName = md5($file->getClientOriginalName()) . '.'.$file->getClientOriginalExtension();
    
                Storage::put('/public/users/logo/'.$imageName,file_get_contents($request->file('image')));
                $url = Storage::url('public/users/logo/'.$imageName);
    
                $user = User::findOrFail($userId);
                $user->logo_url = $url;
                $user->save();
    
                // Commit the transaction
                DB::commit();
    
                return response()->json([
                    'result'=>true,
                    'logo_url' => url('/') .$url
                ],200);
            } else {
                // Rollback the transaction
                DB::rollback();
    
                return response()->json([
                    'result'=>false,
                    'errors'=>['Something went wrong!. Please try again']
                ],404);
            }
        } catch (\Exception $e) {
            // Handle exceptions and rollback the transaction
            DB::rollback();
    
            return response()->json([
                'result'=>false,
                'errors'=>['An error occurred: '.$e->getMessage()]
            ],500);
        }
    }
}