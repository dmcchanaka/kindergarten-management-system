<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //define user array to seed 
        $users =  [
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'u_tp_id' => 1,
                'username' => 'admin',
                'password' => Hash::make('123'),
                'first_name' => 'Admin',
                'last_name' => '',
            ],
        ];

        //remove all data from table
        User::truncate();

        //add array data to table
        foreach($users as $user){

            $data = [
                'name' => $user['name'],
                'u_tp_id' => $user['u_tp_id'],
                'email' => $user['email'],
                'username' => $user['username'],
                'password' => $user['password'],
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
            ];
            User::create($data);
        }
    }
}
