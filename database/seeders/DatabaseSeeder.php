<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Task;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Output\OutputInterface;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Artisan::call(
        //     'make:filament-user',
        //     [
        //         '--name' => 'Admin',
        //         '--email' => 'admin@facman.test',
        //         '--password' => '123456789',
        //         '--no-interaction' => true
        //     ],
        // );

        Task::factory(100)->create();
    }
}
