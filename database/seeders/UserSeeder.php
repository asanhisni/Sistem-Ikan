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

        // Kepala Cabang Suci
        $suci = \App\Models\Cabang::where('nama_cabang', 'Suci')->first();
        User::updateOrCreate(
            ['username' => 'kcsuci'],
            [
                'name'         => 'Kepala Cabang Suci',
                'username'     => 'kcsuci',
                'email'        => 'kcsuci@sistemikan.com',
                'password'     => Hash::make('suci123'),
                'role'         => 'kepala_cabang',
                'cabang_id'    => $suci ? $suci->id : null,
                'recovery_pin' => '123456',
            ]
        );
    }
}
