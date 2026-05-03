<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HeroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('heroes')->insert([
            'title' => 'Meu primeiro herói',
            'image' => 'hero.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
