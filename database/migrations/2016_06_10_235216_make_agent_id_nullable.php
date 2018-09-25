<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeAgentIdNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("tickets",function(Blueprint $table){
           DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            DB::statement("ALTER TABLE `tickets` CHANGE COLUMN `agent_id` `agent_id` INT(10) UNSIGNED NULL DEFAULT NULL  COMMENT '' AFTER `owner_id`");

           DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {



    }
}
