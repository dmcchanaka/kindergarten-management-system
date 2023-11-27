<?php

namespace Database\Seeders;

use App\Models\UserType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userTypes =  [
            [
                'user_type' => 'admin',
            ],
            [
                'user_type' => 'principal',
            ],
            [
                'user_type' => 'teacher',
            ],
            [
                'user_type' => 'parent',
            ],
        ];

        //remove all data from table
        UserType::truncate();

        //add array data to table
        foreach($userTypes as $tp){

            $data = [
                'user_type' => $tp['user_type'],
            ];
            UserType::create($data);
        }
    }
}
