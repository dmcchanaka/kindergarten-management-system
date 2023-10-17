<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\ClassRoomTeacher;
use App\Models\User;
use App\Traits\UserAllocation;
use App\Validators\CustomValidator;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClassRoomController extends Controller
{
    use UserAllocation;

    public function teachersList(Request $request){
        $user = Auth::user();
        try {

            $teachers = User::where('u_tp_id', config('kindergarten.type_teacher'))->get();
            $teachers->transform(function($teacher){
                return [
                    'value'=>$teacher->getKey(),
                    'label'=>$teacher->name,
                ];
            });
            return response()->json([
                'result' => true,
                'teachers' => $teachers
            ], 200);
        } catch (QueryException $e) {
            // Handle database query exceptions
            return response()->json([
                'result' => false,
                'errors' => ['Database error: ' . $e->getMessage()]
            ], 500);
        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json([
                'result' => false,
                'errors' => ['An error occurred: ' . $e->getMessage()]
            ], 500);
        }
    }

    public function classRoomRegistration(Request $request){
        $data = $request->all();
        
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'numeric', 'digits_between:10,25'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        ];

        $attributes = [
            'name' => 'class name',
            'phone_number' => 'phone number',
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

            $classRoom = ClassRoom::create([
                'name'=>$request['name'],
                'phone_number'=>$request['phone_number'],
                'email'=>$request['email'],
            ]);

            if(isset($request['teachers']) && sizeof($request['teachers'])> 0){
                foreach($request['teachers'] as $teacher){
                    ClassRoomTeacher::create([
                        'cls_room_id'=>$classRoom->getKey(), 
                        'teacher_id'=>$teacher
                    ]);
                }
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

    public function classRoomList(Request $request){
        try {
            $user = Auth::user();
            $classRoomQuery = ClassRoom::with(['teachers']);
            $classRooms = $classRoomQuery->get();
            // return $classRooms;
            $classRooms->transform(function($cls){
                $teachers = $cls->teachers->map(function($teacher){
                    return [
                        'id' => $teacher->id,
                        'name' => $teacher->name,
                    ];
                });

                return [
                    'id'=>$cls->id,
                    'name'=>$cls->name,
                    'phone_number'=>$cls->phone_number,
                    'email'=>$cls->email,
                    'created_at'=> date('d/m/Y', strtotime($cls->created_at)),
                    'teachers'=> $teachers
                ];
            });
            return response()->json([
                'result'=>true,
                'classRoomList' => $classRooms
            ],200);
        } catch(Exception $e){
            return response()->json([
                'result' => false,
                'errors' => 'Database connection error: ' . $e->getMessage()
            ], 500);
        }
        
    }
}
