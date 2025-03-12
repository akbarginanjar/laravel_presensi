<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Generator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Generator $faker)
    {
        $demoUser = User::create([
            'name'              => 'Admin',
            'email'             => 'admin@demo.com',
            'password'          => Hash::make('demo'),
            'type'              => 'Admin',
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        $demoUser2 = User::create([
            'name'              => 'karyawan',
            'email'             => 'karyawan@demo.com',
            'password'          => Hash::make('demo'),
            'type'              => 'Karyawan',
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);
    }
}
