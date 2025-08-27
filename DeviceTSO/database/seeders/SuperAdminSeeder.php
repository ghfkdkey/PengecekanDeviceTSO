<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            ['username' => 'superadmin'],
            [
                'password_hash'   => Hash::make('Tsel123'),
                'full_name'       => 'Super Administrator',
                'email'           => 'admin@email.com',
                'role'            => 'admin',
                'remember_token'  => null,
            ]
        );
    }
}