<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
        	'name' => 'Luis Rodriguez',
        	'phone' => '3511159550',
        	'email' => 'demo@gmail.com',
        	'profile' => 'ADMIN',
        	'status' => 'ACTIVE',
        	'password' => bcrypt('demo123')
        ]);
        User::create([
        	'name' => 'Sofia Siqueiros',
        	'phone' => '3549873214',
        	'email' => 'usuario@gmail.com',
        	'profile' => 'EMPLOYEE',
        	'status' => 'ACTIVE',
        	'password' => bcrypt('demo123')
        ]);
    }
}
