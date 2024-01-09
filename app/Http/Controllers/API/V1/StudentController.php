<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use App\Traits\UserAllocation;
use App\Validators\CustomValidator;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    use UserAllocation;

    public function parentLookUp(Request $request){
        $user = Auth::user();
        try {
            $parentList = User::where('u_tp_id', config('kindergarten.type_parent'))->get();
            if($parentList->isNotEmpty()){
                $parentList->transform(function($parent){
                    return [
                        'value'=>$parent->getKey(),
                        'label'=>$parent->name,
                    ];
                });
                return response()->json([
                    'result'=>true,
                    'parentList' => $parentList
                ],200);
            } else {
                return response()->json([
                    'result' => false,
                    'errors' => ['Dont have allocated class rooms']
                ], 400);
            }
            
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

    public function studentRegistration(Request $request){
        $data = $request->all();

        $rules = [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            // 'address' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['required'],
            'age' => ['required', 'numeric'],
            'gender' => ['required'],
            'org_id' => ['required'],
            'class_room_id' => ['required'],
            'parent_id' => ['required'],
        ];

        $attributes = [
            'first_name' => 'first name',
            'last_name' => 'last name',
            // 'address' => 'address',
            'date_of_birth' => 'date of birth',
            'age' => 'age',
            'gender' => 'gender',
            'org_id' => 'organization',
            'class_room_id' => 'class room',
            'parent_id' => 'guardian',
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
            $student = Student::create([
                'org_id'=>$request['org_id'],
                'class_room_id'=>$request['class_room_id'],
                'guardian_id'=>$request['parent_id'],
                'first_name'=>$request['first_name'],
                'last_name'=>$request['last_name'],
                'date_of_birth'=>date('Y-m-d', strtotime($request['date_of_birth'])),
                'age'=>$request['age'], 
                'gender'=>$request['gender'],
                'address'=>$request['address'],
                'special_notice'=>$request['special_notice']
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

    public function fetchStudentsList(Request $request){
        $user = Auth::user();
        try {
            $studentsInfo = $this->getUserRoleRelatedStudents($user);

            if(!$studentsInfo->isEmpty()){
                $students = Student::whereIn('id', $studentsInfo->pluck('id')->all())->get();
                $students->transform(function($student){
                    $organization = $student->organization ? [
                        'id' => $student->organization->id,
                        'name' => $student->organization->name,
                    ] : (object)[];
                    $classRoom = $student->class_room ? [
                        'id' => $student->class_room->id,
                        'name' => $student->class_room->name,
                    ] : (object)[];
                    $guardian = $student->guardian ? [
                        'id' => $student->guardian->id,
                        'name' => $student->guardian->name,
                    ] : (object)[];
                    return [
                        'id'=>$student->id,
                        'first_name'=>$student->first_name,
                        'last_name'=>$student->last_name,
                        'date_of_birth'=>$student->date_of_birth,
                        'age'=>$student->age,
                        'gender'=>$student->gender,
                        'address'=>$student->address,
                        'special_notice'=>$student->special_notice,
                        'organization'=>$organization,
                        'class_room'=>$classRoom,
                        'guardian'=>$guardian
                    ];
                });
                return response()->json([
                    'result' => true,
                    'students' => $students
                ], 200);
            } else {
                return response()->json([
                    'result' => false,
                    'errors' => ['Dont have registered parents']
                ], 400);
            }
            
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

    public function updateStudent(Request $request){
        $data = $request->all();

        $rules = [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            // 'address' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['required'],
            'age' => ['required', 'numeric'],
            'gender' => ['required'],
            'org_id' => ['required'],
            'class_room_id' => ['required'],
            'parent_id' => ['required'],
        ];

        $attributes = [
            'first_name' => 'first name',
            'last_name' => 'last name',
            // 'address' => 'address',
            'date_of_birth' => 'date of birth',
            'age' => 'age',
            'gender' => 'gender',
            'org_id' => 'organization',
            'class_room_id' => 'class room',
            'parent_id' => 'guardian',
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
            $student = Student::findOrFail($request['id']);
            $student->org_id = $request['org_id'];
            $student->class_room_id = $request['class_room_id'];
            $student->guardian_id = $request['parent_id'];
            $student->first_name = $request['first_name'];
            $student->last_name = $request['last_name'];
            $student->date_of_birth = date('Y-m-d', strtotime($request['date_of_birth']));
            $student->age = $request['age']; 
            $student->gender = $request['gender'];
            $student->address = $request['address'];
            $student->special_notice = $request['special_notice'];
            $student->save();
            DB::commit();

            return response()->json([
                'result' => true,
                'message' => 'Record has been successfuly updated'
            ], 200);
        } catch(Exception $e){
            DB::rollBack();
            return response()->json([
                'result'=>false,
                'errors' => $e->getMessage()
            ],500);
        }
    }

    public function studentRemove(Request $request){
        try {
            DB::beginTransaction();
    
            $student = Student::findOrFail($request['studentId']);
            $student->delete();
    
            DB::commit();
            return response()->json([
                'result' => true,
                'message' => 'Record has been successfuly removed'
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'result'=>false,
                'errors' => $e->getMessage()
            ],500);
        }
    }
}
