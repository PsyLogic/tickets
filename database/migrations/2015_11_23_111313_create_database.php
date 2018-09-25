<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDatabase extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name',70);
			$table->string('email')->unique();
			$table->string('password', 60);
			$table->enum('user_type', array('admin', 'agent','user'))->default('user');
			$table->dateTime('last_login');
			$table->rememberToken();
			$table->timestamps();
		});

		Schema::create('password_resets', function (Blueprint $table) {
			$table->string('email')->index();
			$table->string('token')->index();
			$table->timestamps();
		});

		Schema::create('categories', function($table)
		{
			$table->increments('id');
			$table->string('name', 30)->unique();
			$table->string('color', 10);
			$table->timestamps();

		});

		Schema::create('user_categories', function($table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')
			      ->references('id')->on('users')
			      ->onDelete('CASCADE')
			      ->onUpdate('CASCADE');
			$table->integer('category_id')->unsigned();
			$table->foreign('category_id')
			      ->references('id')->on('categories')
			      ->onDelete('CASCADE')
			      ->onUpdate('CASCADE');
		});

		Schema::create('status', function($table)
		{
			$table->increments('id');
			$table->string('name', 30)->unique();
			$table->string('color', 10);
			$table->timestamps();
		});

		Schema::create('priorities', function($table)
		{
			$table->increments('id');
			$table->string('name', 30)->unique();
			$table->string('color', 10);
			$table->timestamps();
		});

		Schema::create('tickets', function($table)
		{
			$table->increments('id');
			$table->string('subject', 100);
			$table->string('description', 500);
			$table->integer('category_id')->unsigned();
			$table->foreign('category_id')
			      ->references('id')->on('categories')
			      ->onDelete('CASCADE')
			      ->onUpdate('CASCADE');
			$table->integer('priority_id')->unsigned();
			$table->foreign('priority_id')
			      ->references('id')->on('priorities')
			      ->onDelete('CASCADE')
			      ->onUpdate('CASCADE');
			$table->integer('owner_id')->unsigned();
			$table->foreign('owner_id')
			      ->references('id')->on('users')
			      ->onDelete('CASCADE')
			      ->onUpdate('CASCADE');
			$table->integer('agent_id')->unsigned();
			$table->foreign('agent_id')
			      ->references('id')->on('users')
			      ->onDelete('CASCADE')
			      ->onUpdate('CASCADE');
			$table->integer('status_id')->unsigned();
			$table->foreign('status_id')
			      ->references('id')->on('status')
			      ->onDelete('CASCADE')
			      ->onUpdate('CASCADE');
			$table->timestamps();
		});

		Schema::create('comments', function($table)
		{
			$table->increments('id');
			$table->text('comment');
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')
			      ->references('id')->on('users')
			      ->onDelete('CASCADE')
			      ->onUpdate('CASCADE');
			$table->integer('ticket_id')->unsigned();
			$table->foreign('ticket_id')
			      ->references('id')->on('tickets')
			      ->onDelete('CASCADE')
			      ->onUpdate('CASCADE');
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
		Schema::dropifExists('comments');
		Schema::dropifExists('tickets');
		Schema::dropifExists('status');
		Schema::dropifExists('priorities');
		Schema::dropIfExists('user_categories');
		Schema::dropIfExists('categories');
		Schema::dropIfExists('users');
		Schema::dropIfExists('password_resets');
	}
}
