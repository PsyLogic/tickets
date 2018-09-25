<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


Route::group(['middleware' => ['web','auth']], function () {

	Route::get('/', ['as' => 'dashboard', 'uses' => 'HomeController@dashboard']);
	Route::get('dashboard', ['as' => 'dashboard', 'uses' => 'HomeController@dashboard']);

	// Profile Update Route
	Route::get('profile', ['as' => 'ticket.profile', 'uses' => 'HomeController@index']);
	Route::post('password', ['as' => 'ticket.PasswordUpdate', 'uses' => 'HomeController@updatePassword']);
	Route::post('userprofile', ['as' => 'ticket.profileUpdate', 'uses' => 'HomeController@update']);
	//End Profile Route

	Route::resource('admin/status', 'Admin\StatusController');
	Route::resource('admin/category', 'Admin\CategoryController');
	Route::resource('admin/priority', 'Admin\PriorityController');
	Route::resource('admin/agent', 'Admin\AgentController');
	Route::post('admin/password',['as'=> 'admin.profile.password','uses' =>  'Admin\AdminController@updatePassword']);
	Route::resource('admin/user', 'Admin\UserController');

	Route::get('ticket/dataTable',['as'=> 'ticket.completedDataTable','uses' =>  'Admin\TicketController@completedTickets']);
	Route::post('ticket/addComment',['as'=> 'ticket.addComment','uses' =>  'Admin\TicketController@addComment']);
	Route::post('ticket/reopenTicket/{id}',['as'=> 'ticket.reopenTicket','uses' =>  'Admin\TicketController@reopenTicket']);
	Route::post('ticket/closeTicket/{id}',['as'=> 'ticket.closeTicket','uses' =>  'Admin\TicketController@closeTicket']);
    Route::resource('ticket', 'Admin\TicketController');

    // Create Admin Routes
    Route::resource('admins','Admin\AdminController');
    Route::resource('emailtemplate', 'Admin\EmailTemplateController');

	//Setting Routes
	Route::resource('admin/setting','Admin\SettingController');

    //Users Activity Routes
	Route::get('user/ticket',['as'=> 'user.ticket.userIndex','uses' =>  'Admin\TicketController@userIndex']);
	Route::get('user/ticket/userCompletedTickets',['as'=> 'user.ticket.CompletedTickets','uses' =>  'Admin\TicketController@userCompletedTickets']);

    //Agent Activity Routes
	Route::get('agent/ticket',['as'=> 'agent.ticket.agentIndex','uses' =>  'Admin\TicketController@agentIndex']);
	Route::get('agent/ticket/userCompletedTickets',['as'=> 'agent.ticket.CompletedTickets','uses' =>  'Admin\TicketController@agentCompletedTickets']);
});

Route::group(['middleware' => 'web'],function () {
    Route::auth();
	Route::get('password/email', 'Auth\PasswordController@getEmail');
});

