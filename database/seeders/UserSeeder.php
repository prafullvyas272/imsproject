<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
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
        $email = 'admintestinguser@yopmail.com';
        $adminDoesntExist = User::whereEmail($email)->whereRoleId(RoleEnum::ADMIN)->doesntExist();
        if ($adminDoesntExist) {
            User::create([
                'name' => 'Test Admin',
                'email' => 'admintestinguser@yopmail.com',
                'password' => Hash::make(11111111),
                'role_id' => RoleEnum::ADMIN,
            ]);
        }

    }
}
