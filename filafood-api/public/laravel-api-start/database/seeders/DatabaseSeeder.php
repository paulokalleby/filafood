<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory(100)->create();

        User::factory()->create([
            'name' => 'Paulo Kalleby',
            'email' => 'paulo.devweb@gmail.com',
        ]);
    }
}