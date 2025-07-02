<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Enums\RoleEnum;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['id' => RoleEnum::ADMIN, 'name' => 'Admin'],
            ['id' => RoleEnum::OFFICER, 'name' => 'Officer'],
            ['id' => RoleEnum::REVIEWER, 'name' => 'Reviewer'],
            ['id' => RoleEnum::DIRECTOR, 'name' => 'Director'],
            ['id' => RoleEnum::BOARD_MEMBER, 'name' => 'Board Member'],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(
                ['id' => $role['id']],
                ['name' => $role['name']]
            );
        }
    }
}
