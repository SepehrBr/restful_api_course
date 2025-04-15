<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->state([
            'first_name' => 'Sepehr',
            'last_name' => 'Borna',
            'email' => 'admin@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password')
        ])->create();
        
        User::factory()
            ->count(100)
            ->has(Article::factory()->count(random_int(1, 10)))
            ->create();
    }
}
