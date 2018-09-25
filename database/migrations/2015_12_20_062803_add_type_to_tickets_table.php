<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeToTicketsTable extends Migration
{

    public function up()
    {
        Schema::table('tickets', function (Blueprint $table) {
	        $table->enum('type', ['opened', 'closed'])->default('opened')->after('description');
        });
    }

    public function down()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
