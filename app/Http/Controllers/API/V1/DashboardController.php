<?php

namespace App\Http\Controllers\API\V1;
use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\User;
use App\Traits\UserAllocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller {

    use UserAllocation;

    public function overview(Request $request){
        $user = Auth::user();

        $organization = $this->getUserAllocatedAllOrganizations($user);
        $classRoom = $this->getUserRelatedClassRooms($user);
        if(config('kindergarten.type_super_admin') == $user->u_tp_id){
            $teachers = User::where('u_tp_id', config('kindergarten.type_teacher'))->get();
        } elseif(config('kindergarten.type_principal') == $user->u_tp_id){
            $organizations = $this->getUserAllocatedAllOrganizations($user);
                if(!$organizations->isEmpty()){
                    $teacherIds = ClassRoom::whereIn('org_id', $organizations->pluck('id'))
                        ->with('teachers:id')
                        ->get()
                        ->pluck('teachers.*.id')
                        ->flatten()
                        ->unique()
                        ->values()
                        ->all();
                    $teachers = User::where('u_tp_id', config('kindergarten.type_teacher'))->whereIn('id', $teacherIds)->get();
                }
        } else {
            $teachers = collect([]);
        }
        $student = $this->getUserRoleRelatedStudents($user);

        return response()->json([
            'result'=>true,
            'overview'=> [
                'organizationsCount' => isset($organization) ? $organization->count(): 0,
                'classRoomsCount' => isset($classRoom) ? $classRoom->count(): 0,
                'teachersCount' => isset($teachers) ? $teachers->count(): 0,
                'studentsCount' => isset($student) ? $student->count(): 0,
            ]
        ],200);
    }
}