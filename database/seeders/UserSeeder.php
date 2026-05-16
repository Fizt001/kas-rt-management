<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@btr.com',
                'password' => Hash::make('password'),
                'role' => 'superadmin',
            ],
            [
                'name' => 'Ketua RT',
                'email' => 'rt@btr.com',
                'password' => Hash::make('password'),
                'role' => 'rt',
            ],
            [
                'name' => 'Bendahara RT',
                'email' => 'bendahara@btr.com',
                'password' => Hash::make('password'),
                'role' => 'bendahara',
            ],
            [
                'name' => 'contoh warga',
                'email' => 'warga@btr.com',
                'password' => Hash::make('warga123'),
                'role' => 'warga',
            ],
            [
                'name' => 'Pengurus Mesjid',
                'email' => 'mesjid@btr.com',
                'password' => Hash::make('password'),
                'role' => 'mesjid',
            ],
            [
                'name' => 'Pengurus Koperasi',
                'email' => 'koperasi@btr.com',
                'password' => Hash::make('password'),
                'role' => 'koperasi',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}