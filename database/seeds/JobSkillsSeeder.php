<?php

use App\Skill;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class JobSkillsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Skill::firstOrCreate(['name' => 'Laravel', 'slug' => Str::slug('Laravel')]);
        Skill::firstOrCreate(['name' => 'Symfony', 'slug' => Str::slug('Symfony')]);
        Skill::firstOrCreate(['name' => 'Symfony', 'slug' => Str::slug('Symfony')]);
        Skill::firstOrCreate(['name' => 'PHP', 'slug' => Str::slug('Angular')]);
    }
}
