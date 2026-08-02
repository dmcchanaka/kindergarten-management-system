<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\UserPermission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //define user array to seed 
        $permissions =  [
            [
                'name' => 'dashboard',
                'heading' => 'dashboard',
                'route' => 'dashboard',
                'icon' => 'home',
                'order' => 1,
            ],
            [
                'name' => 'my-profile',
                'heading' => 'dashboard',
                'route' => 'dashboard',
                'icon' => 'home',
                'order' => 2,
            ],
            [
                'name' => 'user-roles',
                'heading' => 'user-roles',
                'route' => 'user-roles',
                'icon' => 'id-card',
                'order' => 2,
            ],
            [
                'name' => 'add-user-role',
                'heading' => 'user-roles',
                'route' => 'user-roles',
                'icon' => 'id-card',
                'order' => 2,
            ],
            [
                'name' => 'edit-user-role',
                'heading' => 'user-roles',
                'route' => 'user-roles',
                'icon' => 'id-card',
                'order' => 2,
            ],
            [
                'name' => 'delete-user-role',
                'heading' => 'user-roles',
                'route' => 'user-roles',
                'icon' => 'id-card',
                'order' => 2,
            ],
            [
                'name' => 'users',
                'heading' => 'users',
                'route' => 'users',
                'icon' => 'user',
                'order' => 3,
            ],
            [
                'name' => 'add-user',
                'heading' => 'users',
                'route' => 'users',
                'icon' => 'user',
                'order' => 3,
            ],
            [
                'name' => 'edit-user',
                'heading' => 'users',
                'route' => 'users',
                'icon' => 'user',
                'order' => 3,
            ],
            [
                'name' => 'delete-user',
                'heading' => 'users',
                'route' => 'users',
                'icon' => 'user',
                'order' => 3,
            ],
            [
                'name' => 'organizations',
                'heading' => 'organizations',
                'route' => 'organizations',
                'icon' => 'school',
                'order' => 4,
            ],
            [
                'name' => 'create-organization',
                'heading' => 'organizations',
                'route' => 'organizations',
                'icon' => 'school',
                'order' => 4,
            ],
            [
                'name' => 'organization/edit',
                'heading' => 'organizations',
                'route' => 'organizations',
                'icon' => 'school',
                'order' => 4,
            ],
            [
                'name' => 'organization/delete',
                'heading' => 'organizations',
                'route' => 'organizations',
                'icon' => 'school',
                'order' => 4,
            ],
            [
                'name' => 'class-rooms',
                'heading' => 'class rooms',
                'route' => 'class-rooms',
                'icon' => 'chair',
                'order' => 5,
            ],
            [
                'name' => 'add-class-room',
                'heading' => 'class rooms',
                'route' => 'class-rooms',
                'icon' => 'chair',
                'order' => 5,
            ],
            [
                'name' => 'edit-class-room',
                'heading' => 'class rooms',
                'route' => 'class-rooms',
                'icon' => 'chair',
                'order' => 5,
            ],
            [
                'name' => 'delete-class-room',
                'heading' => 'class rooms',
                'route' => 'class-rooms',
                'icon' => 'chair',
                'order' => 5,
            ],
            [
                'name' => 'parents',
                'heading' => 'parents',
                'route' => 'parents',
                'icon' => 'people-roof',
                'order' => 6,
            ],
            [
                'name' => 'add-parent',
                'heading' => 'parents',
                'route' => 'parents',
                'icon' => 'people-roof',
                'order' => 6,
            ],
            [
                'name' => 'edit-parent',
                'heading' => 'parents',
                'route' => 'class-rooms',
                'icon' => 'people-roof',
                'order' => 6,
            ],
            [
                'name' => 'delete-parent',
                'heading' => 'parents',
                'route' => 'parents',
                'icon' => 'people-roof',
                'order' => 6,
            ],
            [
                'name' => 'students',
                'heading' => 'students',
                'route' => 'students',
                'icon' => 'baby',
                'order' => 7,
            ],
            [
                'name' => 'add-student',
                'heading' => 'students',
                'route' => 'students',
                'icon' => 'baby',
                'order' => 7,
            ],
            [
                'name' => 'edit-student',
                'heading' => 'students',
                'route' => 'students',
                'icon' => 'baby',
                'order' => 7,
            ],
            [
                'name' => 'delete-student',
                'heading' => 'students',
                'route' => 'students',
                'icon' => 'baby',
                'order' => 7,
            ],
            [
                'name' => 'student-profile',
                'heading' => 'students',
                'route' => 'students',
                'icon' => 'baby',
                'order' => 7,
            ],
            [
                'name' => 'student-development',
                'heading' => 'students',
                'route' => 'students',
                'icon' => 'baby',
                'order' => 7,
            ],
            [
                'name' => 'posts',
                'heading' => 'posts',
                'route' => 'posts',
                'icon' => 'newspaper',
                'order' => 8,
            ],
            [
                'name' => 'add-post',
                'heading' => 'posts',
                'route' => 'posts',
                'icon' => 'newspaper',
                'order' => 8,
            ],
            [
                'name' => 'edit-post',
                'heading' => 'posts',
                'route' => 'posts',
                'icon' => 'newspaper',
                'order' => 8,
            ],
            [
                'name' => 'delete-post',
                'heading' => 'posts',
                'route' => 'posts',
                'icon' => 'newspaper',
                'order' => 8,
            ],
            [
                'name' => 'news-feed',
                'heading' => 'news-feed',
                'route' => 'news-feed',
                'icon' => 'newspaper',
                'order' => 9,
            ],
            [
                'name' => 'news-feed-content',
                'heading' => 'news-feed',
                'route' => 'news-feed',
                'icon' => 'newspaper',
                'order' => 9,
            ],
            [
                'name' => 'chat',
                'heading' => 'chat',
                'route' => 'chat',
                'icon' => 'comments',
                'order' => 10,
            ],
            [
                'name' => 'attendance',
                'heading' => 'attendance',
                'route' => 'attendance',
                'icon' => 'clock',
                'order' => 11,
            ],
            [
                'name' => 'events',
                'heading' => 'events',
                'route' => 'events',
                'icon' => 'calendar-plus',
                'order' => 12,
            ],
            [
                'name' => 'add-event',
                'heading' => 'events',
                'route' => 'events',
                'icon' => 'calendar-plus',
                'order' => 12,
            ],
            [
                'name' => 'edit-event',
                'heading' => 'events',
                'route' => 'events',
                'icon' => 'calendar-plus',
                'order' => 12,
            ],
            [
                'name' => 'delete-event',
                'heading' => 'events',
                'route' => 'events',
                'icon' => 'calendar-plus',
                'order' => 12,
            ],
            [
                'name' => 'calendar',
                'heading' => 'calendar',
                'route' => 'calendar',
                'icon' => 'calendar',
                'order' => 13,
            ],
            [
                'name' => 'settings',
                'heading' => 'settings',
                'route' => 'settings',
                'icon' => 'gears',
                'order' => 14,
            ],
        ];

        //remove all data from table
        Schema::disableForeignKeyConstraints();
        UserPermission::truncate();
        Permission::truncate();
        Schema::enableForeignKeyConstraints();

        //add array data to table
        $createdPermissions = [];
        foreach($permissions as $permission){

            $data = [
                'name' => $permission['name'],
                'heading' => $permission['heading'],
                'route' => $permission['route'],
                'icon' => $permission['icon'],
                'order' => $permission['order'],
            ];
            $p = Permission::create($data);
            $createdPermissions[$permission['name']] = $p->getKey();
        }

        // Define default permission groups
        $teacherPermissions = [
            'dashboard', 'my-profile',
            'students', 'add-student', 'edit-student', 'delete-student', 'student-profile', 'student-development',
            'posts', 'add-post', 'edit-post', 'delete-post',
            'news-feed', 'news-feed-content',
            'attendance', 'chat', 'calendar', 'events', 'add-event', 'edit-event', 'delete-event'
        ];

        $parentPermissions = [
            'dashboard', 'my-profile',
            'student-profile', 'student-development',
            'posts',
            'news-feed', 'news-feed-content',
            'chat', 'calendar'
        ];

        // Seeding user_permissions: Principal (u_tp_id = 2) gets all permissions
        foreach ($createdPermissions as $name => $id) {
            UserPermission::create([
                'u_tp_id' => 2,
                'p_id' => $id,
            ]);
        }

        // Teacher (u_tp_id = 3)
        foreach ($teacherPermissions as $name) {
            if (isset($createdPermissions[$name])) {
                UserPermission::create([
                    'u_tp_id' => 3,
                    'p_id' => $createdPermissions[$name],
                ]);
            }
        }

        // Parent (u_tp_id = 4)
        foreach ($parentPermissions as $name) {
            if (isset($createdPermissions[$name])) {
                UserPermission::create([
                    'u_tp_id' => 4,
                    'p_id' => $createdPermissions[$name],
                ]);
            }
        }
    }
}
