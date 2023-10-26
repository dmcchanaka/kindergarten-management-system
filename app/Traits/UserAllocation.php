<?php 
namespace App\Traits;

use App\Models\ClassRoom;
use App\Models\ClassRoomTeacher;
use App\Models\GeneralSetting;
use App\Models\Organization;
use App\Models\Permission;
use App\Models\Student;
use App\Models\User;
use App\Models\UserPermission;

Trait UserAllocation {

    //get all allocated organization list
    public function getUserOrganizationByUser($user){
        $organizationQuery = Organization::query();
        
        if (config('kindergarten.type_super_admin') != $user->u_tp_id) {
            if (config('kindergarten.type_principal') == $user->u_tp_id) {
                $organizationQuery->where('principal_id', $user->getKey());
            } elseif (config('kindergarten.type_teacher') == $user->u_tp_id) {
                $classRooms = $user->classRooms;
                if($classRooms->isNotEmpty()){
                    $organizationQuery->whereIn('id',  $classRooms->pluck('org_id')->all());
                    $organization = $organizationQuery->latest()->first();
                } else {
                    throw new \Exception("This teacher is not associated with any class room.");
                }
            }
        }

        $organizations = $organizationQuery->get();

        $organizations->transform(function($org){
            return [
                'id'=>$org->id,
                'name'=>$org->name,
            ];
        });
        return $organizations;
    }

    //get User Allocated Organization
    public function getUserAllocatedOrganization($user){
        $organizationQuery = Organization::query();

        // Use a switch statement for better readability and maintainability.
        switch ($user->u_tp_id) {
            case config('kindergarten.type_super_admin'):
                $organization = $organizationQuery->first();
                break;
            case config('kindergarten.type_principal'):
                $organizationQuery->where('principal_id', $user->getKey());
                $organization = $organizationQuery->latest()->first();
                break;
            case config('kindergarten.type_teacher'):
                $classRooms = $user->classRooms;
                if($classRooms->isNotEmpty()){
                    $organizationQuery->whereIn('id',  $classRooms->pluck('org_id')->all());
                    $organization = $organizationQuery->latest()->first();
                } else {
                    throw new \Exception("This teacher is not associated with any class room.");
                }
            default:
                $organization = $organizationQuery->latest()->first();
        }

        if (!$organization) {
            throw new \Exception("Don't have allocated organization");
        }

        $organizationInfo = [
            'id' => $organization->id,
            'name' => $organization->name,
        ];

        return $organizationInfo;
    }

    //get User Allocated Organization
    public function getUserAllocatedAllOrganizations($user){
        $organizationQuery = Organization::query();

        // Use a switch statement for better readability and maintainability.
        switch ($user->u_tp_id) {
            case config('kindergarten.type_super_admin'):
                $organizations = $organizationQuery->get();
                break;
            case config('kindergarten.type_principal'):
                $organizationQuery->where('principal_id', $user->getKey());
                $organizations = $organizationQuery->get();
                break;
            case config('kindergarten.type_teacher'):
                $classRooms = $user->classRooms;
                if($classRooms->isNotEmpty()){
                    $organizationQuery->whereIn('id',  $classRooms->pluck('org_id')->all());
                    $organizations = $organizationQuery->get();
                } else {
                    throw new \Exception("This teacher is not associated with any class room.");
                }
                break;
            default:
                $organizations = $organizationQuery->get();
        }

        if (!$organizations) {
            throw new \Exception("Don't have allocated organizations");
        }

        $organizationInfo = $organizations;

        return $organizationInfo;
    }

    //get general settings by logged user
    public function getGeneralSettingsByUser($user){
        $defaultSettings = [
            'logo' => url('/'). '/media/logo/logo.png',
            'backgroundColor' => '#f3f4f6',
            'headerColor' => '#344767',
            'textColor' => '#344767'
        ];

        $organization = $this->getUserAllocatedOrganization($user);
        $organizationId = isset($organization['id'])?$organization['id']:"";

        $generalSettings = null;
        $settingsQuery = GeneralSetting::query();

        if (config('kindergarten.type_principal') == $user->u_tp_id) {
            $generalSettings = $settingsQuery->where('organization_id', $organizationId)->latest()->first();
        }

        if (!$generalSettings) {
            return $defaultSettings; // Return default settings when no settings are found.
        }

        $settings = [
            'logo' => url('/') . $generalSettings->logo_url,
            'backgroundColor' => $generalSettings->background_color,
            'headerColor' => $generalSettings->heading_color,
            'textColor' => $generalSettings->text_color
        ];

        return $settings;
    }

    //get allocated permissions by logged user
    public function getAllocatedPermissionsByUser($user){
        if (config('kindergarten.type_super_admin') == $user->u_tp_id) {
            // Super admin has all permissions, so you can return all permissions here.
            return Permission::select(['p_id AS id', 'name'])->get();
        }
    
        // For other user roles, let's fetch their permissions.
        $userPermissions = UserPermission::where('u_tp_id', $user->u_tp_id)->pluck('p_id')->all();
    
        if (empty($userPermissions)) {
            // If the user doesn't have specific permissions, return an empty array.
            return [];
        }
    
        // Fetch the permissions based on the user's permissions.
        $permissions = Permission::whereIn('p_id', $userPermissions)
            ->select(['p_id AS id', 'name'])
            ->get();
    
        return $permissions;
    }

    //get user allocated main menubar
    public function getAllocatedMainMenuByUser($user){
        $menuCollection = collect([]);
        if (config('kindergarten.type_super_admin') == $user->u_tp_id) {
            // Super admin has all permissions, so you can return all permissions here.
            $menuCollection = Permission::select(['heading', 'route', 'icon'])->orderBy('order')->get();
        } else {
            // For other user roles, let's fetch their permissions.
            $userPermissions = UserPermission::where('u_tp_id', $user->u_tp_id)->pluck('p_id')->all();

            if (!empty($userPermissions)) {
                // Fetch the menu based on the user's permissions
                $allocatedMenu = Permission::whereIn('p_id', $userPermissions)
                    ->select(['heading', 'route', 'icon'])
                    ->orderBy('order')
                    ->get();
    
                // Add the fetched menu items to the collection
                $menuCollection = collect($allocatedMenu);
            }
        }
    
        // Remove duplicates based on 'heading' and re-index the collection
        $uniqueAllocatedMenu = $menuCollection->unique('heading')->values()->all();

        return $uniqueAllocatedMenu;
    }

    //get user related students
    public function getUserRoleRelatedStudents($user){
        $studentQuery = Student::query();
        switch ($user->u_tp_id) {
            case config('kindergarten.type_super_admin'):
                $students = $studentQuery->get();
                break;
            case config('kindergarten.type_principal'):
                $organizations = $this->getUserAllocatedAllOrganizations($user);
                if(!$organizations->isEmpty()){
                    $students = $studentQuery->whereIn('org_id', $organizations->pluck('id')->all())->get();
                } else {
                    $students = collect([]);
                }
                break;
            case config('kindergarten.type_teacher'):
                $classRooms = ClassRoomTeacher::where('teacher_id', $user->getKey())->get();
                if(!$classRooms->isEmpty()){
                    $students = $studentQuery->whereIn('class_room_id', $classRooms->pluck('cls_room_id')->all())->get();
                } else {
                    $students = collect([]);
                }
                break;
            case config('kindergarten.type_parent'):
                $students = $studentQuery->where('guardian_id', $user->getKey())->get();
                break;
            default:
                $students = $studentQuery->get();
        }
        if (!$students) {
            throw new \Exception("Don't have registered students");
        }
        return $students;
    }

    //get logged user related class rooms
    public function getUserRelatedClassRooms($user){
        $classRoomsQuery = ClassRoom::query();
        switch ($user->u_tp_id) {
            case config('kindergarten.type_super_admin'):
                $classRooms = $classRoomsQuery->get();
                break;
            case config('kindergarten.type_principal'):
                    $organizations = $this->getUserAllocatedAllOrganizations($user);
                    if(!$organizations->isEmpty()){
                        $classRooms = $classRoomsQuery->whereIn('org_id', $organizations->pluck('id')->all())->get();
                    } else {
                        $classRooms = collect([]);
                    }
                    break;
        }
        if (!$classRooms) {
            throw new \Exception("Don't have allocated class rooms");
        }
        return $classRooms;
    }
}