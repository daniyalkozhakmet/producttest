<?php

namespace Database\Seeders;

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
        //
        User::create([
            'first_name' => 'Daniyal',
            'last_name' => 'Kozhakmetov',
            'email' => 'admin@gmail.com',
            "password" => Hash::make('password'),
        ]);
        User::create([
            'first_name' => 'Dummy',
            'last_name' => 'Dummiev',
            'email' => 'test@gmail.com',
            "password" => Hash::make('password'),
        ]);
    }
}
