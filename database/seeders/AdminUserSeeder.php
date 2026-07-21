<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
<<<<<<< HEAD
=======
use Spatie\Permission\Models\Role;
>>>>>>> 2ba62d3e84e40bae6f1a3a2c25edd88932751490
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
<<<<<<< HEAD

           $user = User::firstOrCreate(
=======
         $user = User::firstOrCreate(
>>>>>>> 2ba62d3e84e40bae6f1a3a2c25edd88932751490
            [
                'email' => 'admin@fynnedge.com',
            ],
            [
                'name' => 'Admin',
                'password' => Hash::make('Admin@123'),
            ]
        );

        // Assign existing Admin role
        $user->assignRole('Admin');
<<<<<<< HEAD
=======

>>>>>>> 2ba62d3e84e40bae6f1a3a2c25edd88932751490
    }
}
