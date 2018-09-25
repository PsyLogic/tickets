<?php

use Illuminate\Database\Seeder;

class PrioritesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('priorities')
            ->delete();
        $priority = new App\Priority();
        $priority->name =  'low';
        $priority->color =  '#18cf77';
        $priority->save();

        $priority = new App\Priority();
        $priority->name =  'high';
        $priority->color =  '#8a1717';
        $priority->save();

        $priority = new App\Priority();
        $priority->name =  'medium';
        $priority->color =  '#eb1010';
        $priority->save();
    }
}
