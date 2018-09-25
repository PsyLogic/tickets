<?php

namespace App\Providers;

use App\User;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);
	    $gate->define('user',function ($user, $type) {
		    return $user->user_type === $type;
	    });
	    $gate->define('owner_id',function ($user, $type) {
		    return $user->id === $type;
	    });
	    $gate->define('agent_id',function ($user, $type) {
		    return $user->id === $type;
	    });

	    $gate->define('admin',function ($user) {
		    return $user->user_type === 'admin';
	    });
		$gate->define('agent',function ($user) {
		    return $user->user_type === 'agent';
	    });
		$gate->define('user',function ($user) {
		    return $user->user_type === 'user';
	    });
    }
}
