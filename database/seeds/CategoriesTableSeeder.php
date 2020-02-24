<?php

use App\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::firstOrCreate([
            'name' => 'Accounting & Consulting',
            'slug' => Str::slug('Accounting & Consulting', '-')
        ]);

        Category::firstOrCreate([
            'name' => 'Admin Support',
            'slug' => Str::slug('Admin Support', '-')
        ]);

        Category::firstOrCreate([
            'name' => 'Customer Service',
            'slug' => Str::slug('Customer Service', '-')
        ]);

        Category::firstOrCreate([
            'name' => 'Data Science & Analytics',
            'slug' => Str::slug('Data Science & Analytics', '-')
        ]);

        Category::firstOrCreate([
            'name' => 'Design & Creative',
            'slug' => Str::slug('Design & Creative', '-')
        ]);

        Category::firstOrCreate([
            'name' => 'Engineering & Architecture',
            'slug' => Str::slug('Engineering & Architecture', '-')
        ]);

        Category::firstOrCreate([
            'name' => 'IT & Networking',
            'slug' => Str::slug('IT & Networking', '-')
        ]);

        Category::firstOrCreate([
            'name' => 'Legal',
            'slug' => Str::slug('Legal', '-')
        ]);

        Category::firstOrCreate([
            'name' => 'Sales & Marketing',
            'slug' => Str::slug('Sales & Marketing', '-')
        ]);

        Category::firstOrCreate([
            'name' => 'Translation',
            'slug' => Str::slug('Translation', '-')
        ]);

        Category::firstOrCreate([
            'name' => 'Web, Mobile & Software Dev',
            'slug' => Str::slug('Web, Mobile & Software Dev', '-')
        ]);

        Category::firstOrCreate([
            'name' => 'Writting',
            'slug' => Str::slug('Writting', '-')
        ]);
    }
}
