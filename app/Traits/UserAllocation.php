<?php 
namespace App\Traits;

use App\Models\GeneralSetting;
use App\Models\Organization;
use App\Models\Permission;
use App\Models\UserPermission;

Trait UserAllocation {

    //get all allocated organization list
    public function getUserOrganizationByUser($user){
        $organizationQuery = Organization::query();
        
        if (config('kindergarten.type_super_admin') != $user->u_tp_id) {
            if (config('kindergarten.type_principal') == $user->u_tp_id) {
                $organizationQuery->where('principal_id', $user->getKey());
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
        $organizationId = $organization['id'];

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
}