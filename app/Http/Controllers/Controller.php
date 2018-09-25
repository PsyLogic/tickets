<?php

namespace App\Http\Controllers;

use App\Setting;
use Auth;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
	protected $user;

	public function __construct($guard = null )
	{
		if (Auth::guard($guard)->check()) {
			 $this->user = Auth::user();


			$this->logo = Setting::first(['logo']);
		} else {
			$this->user = null;
			$this->logo = Setting::first(['logo']);

		}

		\View::share(['user'=>$this->user,'logo'=>$this->logo]);
	}
}
