<?php

use Illuminate\Database\Seeder;
use DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
            'title' => 'Carpinteria',
            'visit_price' => '250',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('categories')->insert([
            'title' => 'Cerrajeria',
            'visit_price' => '50',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('categories')->insert([
            'title' => 'Electricidad',
            'visit_price' => '250',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('categories')->insert([
            'title' => 'Electrodomésticos',
            'visit_price' => '250',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('categories')->insert([
            'title' => 'Pintura',
            'visit_price' => '250',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('categories')->insert([
            'title' => 'Plomería',
            'visit_price' => '250',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('categories')->insert([
            'title' => 'Computadora',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('categories')->insert([
            'title' => 'Celular',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
