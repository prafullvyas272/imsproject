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
        // yellow , blue, green, purple,
        $roles = [
            [
                'id' => RoleEnum::ADMIN,
                'name' => 'Admin',
                'form_theme' => 'bg-primary text-white',
                'bg_theme' => 'bg-primary'
            ],
            [
                'id' => RoleEnum::OFFICER,
                'name' => 'Officer',
                'form_theme' => 'bg-purple-200',
                'bg_theme' => 'bg-purple-200'
            ],
            [
                'id' => RoleEnum::REVIEWER,
                'name' => 'Reviewer',
                'form_theme' => 'bg-green-200',
                'bg_theme' => 'bg-green-200'
            ],
            [
                'id' => RoleEnum::DIRECTOR,
                'name' => 'Director',
                'form_theme' => 'bg-blue-200',
                'bg_theme' => 'bg-blue-200'
            ],
            [
                'id' => RoleEnum::BOARD_MEMBER,
                'name' => 'Board Member',
                'form_theme' => 'bg-yellow-200',
                'bg_theme' => 'bg-yellow-200'
            ],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(
                ['id' => $role['id']],
                [
                    'name' => $role['name'],
                    'form_theme' => $role['form_theme'],
                    'bg_theme' => $role['bg_theme'],
                ]
            );
        }
    }
}
