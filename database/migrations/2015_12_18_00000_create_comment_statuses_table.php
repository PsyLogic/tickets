<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comment_status', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('comment_id')->unsigned();
            $table->foreign('comment_id')
                ->references('id')->on('comments')
                ->onDelete('CASCADE')
                ->onUpdate('CASCADE');
            $table->integer('owner_id');
            $table->enum('owner_status', ['read', 'unread'])->default('unread');
            $table->integer('agent_id');
            $table->enum('agent_status', ['read', 'unread'])->default('unread');
            $table->enum('admin_status', ['read', 'unread'])->default('unread');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comment_status');
    }
}
