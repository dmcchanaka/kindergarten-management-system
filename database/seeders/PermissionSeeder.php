<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
                'name' => 'gallery',
                'heading' => 'gallery',
                'route' => 'gallery',
                'icon' => 'images',
                'order' => 8,
            ],
            [
                'name' => 'add-gallery',
                'heading' => 'gallery',
                'route' => 'gallery',
                'icon' => 'images',
                'order' => 8,
            ],
            [
                'name' => 'edit-gallery',
                'heading' => 'gallery',
                'route' => 'gallery',
                'icon' => 'images',
                'order' => 8,
            ],
            [
                'name' => 'delete-gallery',
                'heading' => 'gallery',
                'route' => 'gallery',
                'icon' => 'images',
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
        Permission::truncate();

        //add array data to table
        foreach($permissions as $permission){

            $data = [
                'name' => $permission['name'],
                'heading' => $permission['heading'],
                'route' => $permission['route'],
                'icon' => $permission['icon'],
                'order' => $permission['order'],
            ];
            Permission::create($data);
        }
    }
}
