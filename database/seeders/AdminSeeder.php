<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create([
            'name' => 'Smith Cooper',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
        ]);

        Admin::create([
            'name' => 'ISMAILA HAMADOU',
            'email' => 'ismailahamadou5@gmail.com',
            'password' => bcrypt('12345678'),
        ]);
    }
}
