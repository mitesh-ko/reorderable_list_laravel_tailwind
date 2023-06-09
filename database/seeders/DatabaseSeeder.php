<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // if someone run migrate:fresh then cache should be cleaned
        Cache::forget('projects');
         User::factory(10)->create();
         Project::factory(10)->create();
         Task::factory(10)->create();
         \App\Models\User::factory()->create([
             'name' => 'Demo User',
             'email' => 'demo@example.com',
             'password' => Hash::make('12345678'),
         ]);
    }
}
