<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name'     => 'Administrator',
                'username' => 'admin',
                'email'    => 'admin@sistemikan.com',
                'password' => Hash::make('admin123'),
                'role'     => 'admin',
            ]
        );

        // Kepala Cabang
        User::updateOrCreate(
            ['username' => 'kepalacabang'],
            [
                'name'     => 'Kepala Cabang',
                'username' => 'kepalacabang',
                'email'    => 'kepalacabang@sistemikan.com',
                'password' => Hash::make('cabang123'),
                'role'     => 'kepala_cabang',
            ]
        );
    }
}
