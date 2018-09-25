<?php

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
        DB::table('categories')
            ->delete();
            $category = new App\Category();
            $category->name =  'technical';
            $category->color =  '#18cf77';
            $category->save();

            $category = new App\Category();
            $category->name =  'billing';
            $category->color =  '#8a1717';
            $category->save();

            $category = new App\Category();
            $category->name =  'services';
            $category->color =  '#8a1717';
            $category->save();

    }
}
