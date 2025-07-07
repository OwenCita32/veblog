<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      User::factory()->create([
        'name' => 'Owen Cita Karmanto',
        'username' => 'owencita',
        'password' => Hash::make('password123'),
        'email' => 'owencita32@gmail.com',
      ]);

      User::factory(5)->create();
    }
}
