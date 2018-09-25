<?php

use Illuminate\Database\Seeder;

class StatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('status')
            ->delete();
        $status = new App\Status();
        $status->name =  'pending';
        $status->color =  '#18cf77';
        $status->save();

        $status = new App\Status();
        $status->name =  'solved';
        $status->color =  '#8a1717';
        $status->save();

        $status = new App\Status();
        $status->name =  'bug';
        $status->color =  '#eb1010';
        $status->save();
    }
}
