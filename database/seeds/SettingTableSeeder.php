<?php

use App\Setting;
use Illuminate\Database\Seeder;

class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')
            ->delete();
        $setting = new Setting();
        $setting->company_name =  'FRticket';
        $setting->logo =  'logo.png';
        $setting->address =  '';
        $setting->phone =  '00000000000';
        $setting->email =  'admin@example.com';
        $setting->save();
    }
}
