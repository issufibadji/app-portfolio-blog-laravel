<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeedBackSectionSettings extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('feedback_section_settings')->insert([
            'title' => 'Meu primeiro herói',
            'sub_title' => 'hero.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
