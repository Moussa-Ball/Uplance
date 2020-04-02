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
            'name' => 'Web, Mobile & Software Dev',
            'slug' => Str::slug('Web, Mobile & Software Dev', '-'),
            'subtitle' => 'Software Engineer, Web / Mobile Developer & More',
            'icon' => 'icon-line-awesome-file-code-o',
        ]);

        Category::firstOrCreate([
            'name' => 'Data Science & Analytics',
            'slug' => Str::slug('Data Science & Analytics', '-'),
            'subtitle' => 'Data Specialist / Scientist, Data Analyst & More',
            'icon' => 'icon-line-awesome-cloud-upload',
        ]);

        Category::firstOrCreate([
            'name' => 'Accounting & Consulting',
            'slug' => Str::slug('Accounting & Consulting', '-'),
            'subtitle' => 'Auditor, Accountant, Fnancial Analyst & More',
            'icon' => 'icon-line-awesome-suitcase',
        ]);

        Category::firstOrCreate([
            'name' => 'Writting',
            'slug' => Str::slug('Writting', '-'),
            'subtitle' => 'Copywriter, Creative Writer, Translator & More',
            'icon' => 'icon-line-awesome-pencil',
        ]);

        Category::firstOrCreate([
            'name' => 'Sales & Marketing',
            'slug' => Str::slug('Sales & Marketing', '-'),
            'subtitle' => 'Brand Manager, Marketing Coordinator & More',
            'icon' => 'icon-line-awesome-pie-chart',
        ]);

        Category::firstOrCreate([
            'name' => 'Design & Creative',
            'slug' => Str::slug('Design & Creative', '-'),
            'subtitle' => 'Creative Director, Web Designer & More',
            'icon' => 'icon-line-awesome-image',
        ]);

        Category::firstOrCreate([
            'name' => 'Admin Support',
            'slug' => Str::slug('Admin Support', '-'),
            'subtitle' => 'Data Meaning, Deep Learn, Machine Learning & More',
            'icon' => 'fas fa-headphones-alt',
        ]);

        Category::firstOrCreate([
            'name' => 'Engineering & Architecture',
            'slug' => Str::slug('Engineering & Architecture', '-'),
            'subtitle' => 'Architecture, Product Design, 3D Modeling & CAD & More',
            'icon' => 'fas fa-drafting-compass',
        ]);

        Category::firstOrCreate([
            'name' => 'Customer Service',
            'slug' => Str::slug('Customer Service', '-')
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
            'name' => 'Translation',
            'slug' => Str::slug('Translation', '-')
        ]);
    }
}
