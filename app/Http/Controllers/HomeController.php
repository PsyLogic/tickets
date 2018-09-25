<?php

namespace App\Http\Controllers;

use App\Category;
use App\Setting;
use App\Ticket;
use App\User;
use Carbon\Carbon;
use Hash;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Session;

class HomeController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

	public function index()
	{
		$data['activeOpenProfile'] = "active";
		return view('profile.profile',$data);
	}

    public function dashboard()
    {
		$months = 3;

		if(Input::has('month')) {
			$months = Input::get('month');
		}
//Admin Dashboard
	    if($this->user->user_type == 'admin' || $this->user->user_type == 'agent' ) {
			$ticket = new Ticket();
			$data['allTickets'] = $ticket->getAllTickets()->count();
			$data['activeTickets'] = $ticket->getActiveTickets()->get()->count();
			$data['completedTickets'] = $ticket->getCompletedTickets()->get()->count();

		    $categories = Category::paginate(3, ['*'], 'page_a');

			$agent = User::where('user_type', 'agent')
				->paginate(3, ['*'], 'page_b');

			$data['userTicket'] = User::paginate(3, ['*'], 'page_c');

		    $lineChartData = HomeController:: getChartData($months);

			$pieChartData = [];
			foreach($categories as$k=> $categoryId){
				$pieChartData[] =json_encode([$categoryId->name,$ticket->all()->where('category_id',$categoryId->id)->count()]);
			}

			$agentPieChartData = [];
			foreach($agent as$k=> $agentId){
				$agentPieChartData[] =json_encode([$agentId->name,$ticket->all()->where('agent_id',$agentId->id)->count()]);
			}

			$agentData = $ticket->getCompletedTickets()->where('agent_id',$this->user->id)
				->orWhere('owner_id',$this->user->id)->latest('updated_at')->take(5)->get();

		    $data['dashActive'] = "active";

			$data['value'] = json_encode($lineChartData);
			$data['pieData'] =$pieChartData;
			$data['agentPieData'] =$agentPieChartData;
		    $data['categoryTicket'] = $categories;
		    $data['agentTicket'] = $agent;
		    $data['agentData'] = $agentData;
			if(\Request::ajax()){
				return $data['value'];
			}
		    return view('dashboard.dashboard', $data);
	    }
//user Dashboard
		$data['userData'] = Ticket::where('owner_id',$this->user->id)->where('type','closed')->latest('updated_at')->take(3)->get();
	    $data['dashActive'] = "active";

	    return view('dashboard.userDashboard',$data);
    }
	public function update(Request $request)
	{
		if (\Request::ajax()) {
			$userProfile =  User::find($this->user->id);
			$this->validate($request,$userProfile->profileUpdate($this->user->id));

			$userProfile->name = $request->name;
			$userProfile->email = $request->email;
			$userProfile->save();

			return ['status' => 'success', 'message' =>trans('messages.profile'),'username'=>$userProfile->name];
		}
	}

	public function updatePassword(Request $request)
	{
		if (\Request::ajax()) {
			$user = User::find($this->user->id);
			$this->validate($request,$user->profileUpdatePassword);

			$old_password = $request['old_password'];
			$new_password = $request['new_password'];

			if(Hash::check($old_password, $user->getAuthPassword())){
				$user->password = bcrypt($new_password);
				if($user->save()){
					return ['status' => 'success', 'message' =>trans('messages.profilePassword')];
				}
			}
			else{
				Session::flash('toastrMessage', trans('messages.passwordStr'));
				return $adminUpdateArray = ['status' => 'fail', 'message' => trans('messages.password')];
			}
		}
	}

	public function getChartData($months){

		$categoryName = Category::lists('name','id');
		$chartData = [];
		$averageArray = [];

		for($i=0;$i<$months;$i++) {
			$dateArray = CommonController::getDate($i);

			foreach($dateArray as $backStartDate) {
				foreach( $categoryName as $id=>$name) {
					$time = strtotime($backStartDate);
					$monthName = date("M", $time);

					$chartData[$name][$monthName] = Ticket::whereBetween('updated_at',$dateArray)
						->where('category_id', $id)
						->where('type','closed')
						->count();

					if($chartData[$name][$monthName] == 0) {
						$averageArray[$name][] = [$monthName, 0];
					}
					else {
						$averageArray[$name][] = [$monthName, 30/$chartData[$name][$monthName]];
					}
				}
			}
		}
		return $averageArray;
	}
}
