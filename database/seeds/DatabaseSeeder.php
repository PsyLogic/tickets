<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $this->call(StatusTableSeeder::class);
         $this->call(UsersTableSeeder::class);
         $this->call(CategoriesTableSeeder::class);
         $this->call(PrioritesTableSeeder::class);
         //$this->call(TicketsTableSeeder::class);
         $this->call(EmailTemplateTableSeeder::class);
         $this->call(SettingTableSeeder::class);
    }
}
