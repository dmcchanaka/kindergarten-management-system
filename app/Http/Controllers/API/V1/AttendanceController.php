<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Student;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function fetchStudentsList(Request $request){
        try {
            $students = Student::get();
            $students->transform(function($student){
                $todayAttendance = Attendance::where('student_id', $student->getKey())->where('att_date', date('Y-m-d'))->latest()->first();
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
                    'guardian'=>$guardian,
                    'attendance_status'=>($todayAttendance)?true:false
                ];
            });
            return response()->json([
                'result' => true,
                'students' => $students
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

    public function markStudentAttendance(Request $request) {
        try {
            DB::beginTransaction();
            $attendance = Attendance::create([
                'student_id'=>$request['studentId'],
                'att_date'=>date('Y-m-d'),
                'att_time'=>date('H:i:s')
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
}
