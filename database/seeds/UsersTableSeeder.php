<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')
            ->delete();
        $admin = new App\User();
        $admin->email = 'admin@example.com';
        $admin->name = 'Admin';
        $admin->password = Hash::make('123456');
        $admin->user_type = 'admin';
        $admin->save();
        //for ($i = 0; $i < 5; $i++) {
        //    $admin = new App\User();
        //    $admin->email = 'admin' . $i . '@example.com';
        //    $admin->name = 'Admin';
        //    $admin->password = Hash::make('123456');
        //    $admin->user_type = 'admin';
        //    $admin->save();
        //}
        //for ($i = 0; $i < 5; $i++) {
        //    $admin = new App\User();
        //    $admin->email = 'agent' . $i . '@example.com';
        //    $admin->name = 'Agent';
        //    $admin->password = Hash::make('123456');
        //    $admin->user_type = 'agent';
        //    $admin->save();
        //}
        //for ($i = 0; $i < 5; $i++) {
        //    $admin = new App\User();
        //    $admin->email = 'user' . $i . '@example.com';
        //    $admin->name = 'User';
        //    $admin->password = Hash::make('123456');
        //    $admin->user_type = 'user';
        //    $admin->save();
        //}
    }
}
