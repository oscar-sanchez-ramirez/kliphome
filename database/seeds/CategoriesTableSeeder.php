<?php

use Illuminate\Database\Seeder;
use App\Category;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create([
            'title' => 'Carpinteria',
            'visit_price' => '250',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        Category::create([
            'title' => 'Cerrajeria',
            'visit_price' => '50',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        Category::create([
            'title' => 'Electricidad',
            'visit_price' => '250',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        Category::create([
            'title' => 'Electrodomésticos',
            'visit_price' => '250',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        Category::create([
            'title' => 'Pintura',
            'visit_price' => '250',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        Category::create([
            'title' => 'Plomería',
            'visit_price' => '250',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        Category::create([
            'title' => 'Computadora',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        Category::create([
            'title' => 'Celular',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
