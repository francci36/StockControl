<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'FranAdmin',
            'email' => 'fran@gmail.com',
            'password' => Hash::make('fran1234'),
            'role' => 'admin',
        ]);
    }
}

