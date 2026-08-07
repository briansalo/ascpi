<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'student@example.com'],
            [
                'name' => 'Quiz Student',
                'password' => 'password',
            ]
        );

        User::updateOrCreate(
            ['email' => 'fritzl@student-ascpi.com'],
            [
                'name' => 'Fritzl',
                'password' => 'ilovebrian',
            ]
        );
    }
}
