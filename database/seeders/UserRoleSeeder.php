<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    /**
     * Seed default users for each role.
     */
    public function run(): void
    {
        $users = [
            [
                'nama' => 'Admin Sistem',
                'username' => 'admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'nohp' => '081111111111',
            ],
            [
                'nama' => 'Pegawai Inventaris',
                'username' => 'pegawai',
                'email' => 'pegawai@example.com',
                'password' => Hash::make('password'),
                'role' => 'pegawai',
                'nohp' => '082222222222',
            ],
            [
                'nama' => 'Peminjam Kampus',
                'username' => 'peminjam',
                'email' => 'peminjam@example.com',
                'password' => Hash::make('password'),
                'role' => 'peminjam',
                'nohp' => '083333333333',
            ],
            [
                'nama' => 'Kabag Inventaris',
                'username' => 'kabag',
                'email' => 'kabag@example.com',
                'password' => Hash::make('password'),
                'role' => 'kabag',
                'nohp' => '084444444444',
            ],
        ];

        foreach ($users as $data) {
            User::updateOrCreate(
                ['email' => $data['email']],
                $data
            );
        }
    }
}
