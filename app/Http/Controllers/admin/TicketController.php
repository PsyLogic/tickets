<?php

namespace App\Http\Controllers\admin;

use App\Category;
use App\Comment;
use App\CommentStatus;
use App\Http\Controllers\CommonController;
use App\Priority;
use App\Status;
use App\Ticket;
use App\User;
use Carbon\Carbon;
use DB;
use Gate;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Mail;
use Session;
use URL;
use Yajra\Datatables\Datatables;

class TicketController extends Controller
{
	//ticket view
	public function index()
    {
        if (Gate::denies('admin')) {
            abort(403);
        }
		//ticket datatable view
	    if(\Request::ajax()){
		    $tickets = Ticket::getActiveTickets();
		    return Datatables::of($tickets)
				->edit_column('subject',function($row){
					$count = CommentStatus::getAdminCommentStatus($row->id );
					if($count>0) {
						return  $row->subject . '<span class="badge badge-success">'.$count.'</span>';
					}
					return '<a href="' . URL::route('ticket.show', [$row->id]) . '" data-pk="' . $row->id . '" >' . $row->subject .'</a>';
				})
			    ->edit_column('updated_at',function($row){
					return Carbon::now()->subMinutes(Carbon::now()->diffInMinutes(Carbon::parse($row->updated_at)))->diffForHumans();
				})
			    ->edit_column('statusName',function($row){
					return '<span class="label label-sm" style="background-color:'.$row->statusColor.' ">'. ucfirst($row->statusName) .' </span>';
				})
			    ->edit_column('categoryName',function($row){
					return '<span class="label label-sm" style="background-color:'.$row->categoryColor.' ">'. ucfirst($row->categoryName) .' </span>';
				})
			    ->edit_column('priorityName',function($row){
					return '<span class="label label-sm" style="background-color:'.$row->priorityColor.' ">'. ucfirst($row->priorityName) .' </span>';
				})
				->add_column('action', function ($row) {
					 return '
					 <a class="btn green btn-sm margin-bottom-10" href="' . URL::route('ticket.show', [$row->id]) . '"><i class="fa fa-eye"></i>View</a>
					 <button  type="button" class="btn purple btn-sm margin-bottom-10 activeTicketEdit" onclick="showModal('.$row->id.')" data-pk="'.$row->id.'">Edit</button>
					<button data-pk="'.$row->id.'" data-name="'.$row->subject.'" class="btn red btn-sm margin-bottom-10 activeTicketDelete">Delete</button>
					';
				})
			->make(true);
	    }

		$data['activeTickets'] = Ticket::getActiveTickets()->take(10)->get();
		$data['completedTickets'] = Ticket::getCompletedTickets()->take(10)->get();
		$data['categories'] = Category::getCategories()->toArray();
		$data['priorities'] = Priority::getPriorities()->toArray();
		$data['status'] = Status::getStatus()->toArray();
		$data['activeTotal'] = Ticket::where('tickets.type', 'opened')->count();
		$data['completedTotal'] = Ticket::where('tickets.type', 'closed')->count();

		$data['activeOpenTicket'] = "active";

		return view('ticket.index', $data);
    }


	public function completedTickets(){
		if(Gate::denies('admin')) {
			abort(403, trans('messages.notAuthorised'));
		}

		if(\Request::ajax()){

			$completedTickets = Ticket::getCompletedTickets();
			return Datatables::of($completedTickets)
				->edit_column('updated_at',function($row){
					return Carbon::now()->subMinutes(Carbon::now()->diffInMinutes(Carbon::parse($row->updated_at)))->diffForHumans();
				})
				->edit_column('subject',function($row){
					$count = CommentStatus::getAdminCommentStatus($row->id );
					if($count>0) {
						return  $row->subject .'<span class="badge badge-success">'.$count.'</span>';
					}
					return  $row->subject;
				})
				->edit_column('statusName',function($row){
	                 return '<span class="label label-sm" style="background-color:'.$row->statusColor.' ">'. $row->statusName .' </span>';
                })
                ->edit_column('categoryName',function($row){
	                 return '<span class="label label-sm" style="background-color:'.$row->categoryColor.' ">'. $row->categoryName .' </span>';
                })
                ->edit_column('priorityName',function($row){
	                 return '<span class="label label-sm" style="background-color:'.$row->priorityColor.' ">'. $row->priorityName .' </span>';
                })
                ->add_column('action', function ($row) {
	                 return '<a class="btn green btn-sm margin-bottom-10" href="'. URL::route('ticket.show',[$row->id]).'"><i class="fa fa-eye"></i>View</a>
	                 <button type="button" class="btn purple btn-sm margin-bottom-10" onclick="showModal('.$row->id.')"  data-pk="'.$row->id.'">Edit</button>
					 <button data-pk="'.$row->id.'" data-name="'.$row->subject.'" class="btn red btn-sm margin-bottom-10 completedTicketDelete">Delete</button>
					 ';
                })
                ->make(true);
		}
	}

	//ticket create view
    public function create()
    {
		$data['priority'] = Priority::getPriorities()->toArray();
		$data['category'] = Category::getCategories()->toArray();
	    $data['activeOpenTicket'] = "active";

	    return view('ticket.create', $data);
    }

	//ticket create
    public function store(Request $request)
    {
	    if (\Request::ajax()){

		    $this->validate($request,Ticket::$rules);

			$status = Status::select('name','id')->where('name','pending')->first();

		    $agent = User::select('users.id','users.name','user_categories.category_id')->where('category_id',$request->category)
			    ->leftJoin('user_categories', 'users.id', '=', 'user_categories.user_id')
			    ->get()->toArray();

			if(!empty($agent)){
				$x = array_rand($agent);
				$agentId = $agent[$x]['id'];
			}
			else{
				$agentId = NULL;
			}


			$ticket = new Ticket();
		    $ticket->subject = $request->subject;
		    $ticket->description = $request->description;
		    $ticket->category_id = $request->category;
		    $ticket->priority_id = $request->priority;
		    $ticket->owner_id = $this->user->id;
		    $ticket->agent_id = $agentId;
		    $ticket->status_id = $status->id;
		    $ticket->save();

		    // $user = User::getTicketOwnerOrAgent($ticket->owner_id,$ticket->agent_id)->get();

			// foreach($user as $userName) {
			// 	$emailInfo = ['to' => $userName->email,'name'=>$userName->name];
			// 	$fieldValues = ['NAME'  => $userName->name, 'TICKETNAME' => $ticket->subject];
			// 	$emailId ='create';
			// 	CommonController::prepareAndSendEmail($emailId, $emailInfo, $fieldValues);
			// }

			if($this->user->user_type=='admin'){
		        return ['status' => 'success', 'message' => trans('messages.ticketAdd'),
		                'action' => 'redirect', 'url' => route('ticket.index')];
			}
		    elseif($this->user->user_type=='agent') {
			    return ['status' => 'success', 'message' => trans('messages.ticketAdd'),
			            'action' => 'redirect', 'url' => route('agent.ticket.agentIndex')];
		    }
		    else {
			    return ['status' => 'success', 'message' => trans('messages.ticketAdd'),
			            'action' => 'redirect', 'url' => route('user.ticket.userIndex')];
		    }
		}
    }

	//ticket detail view
    public function show($id)
    {
	    if($this->user->user_type=='admin'){
			if (Gate::denies('admin')) {
				abort(403);
			}
			CommentStatus::where('tickets.id',$id)->where('comment_status.admin_status','unread')
				->leftJoin('comments', 'comment_status.comment_id', '=', 'comments.id')
				->leftJoin('tickets', 'comments.ticket_id', '=', 'tickets.id')
				->update(array(
					"comment_status.admin_status" => 'read',
			));

            $data['activeTickets'] = Ticket::getTicketDetail($id)->first();
			$data['comments'] = $data['activeTickets']->comments;
            $data['total'] = count($data['comments']);
            $data['activeOpenTicket'] = "active";

            return view('ticket.showTicket', $data);
	    }
	    if($this->user->user_type=='agent'){
		    $activeTickets = Ticket::getTicketDetail($id)->first();

			//if ((isset($activeTickets)) &&(Gate::denies('agent' ) || Gate::allows('owner_id', $activeTickets->owner_id))
			//    || Gate::allows('agent_id', $activeTickets->agent_id)) {

                CommentStatus::where('tickets.id',$id)->where('comment_status.agent_status','unread')
                    ->leftJoin('comments', 'comment_status.comment_id', '=', 'comments.id')
                    ->leftJoin('tickets', 'comments.ticket_id', '=', 'tickets.id')
                    ->update(array(
                        "comment_status.agent_status" => 'read',
                    ));

				$data['activeTickets'] = $activeTickets;
				$data['comments'] = $activeTickets->comments;
				$data['total'] = count($data['comments']);
			    $data['activeAgent'] = "active";

			    return view('ticket.showTicket', $data);
		    //}
		    abort(403);
	    }
	    if($this->user->user_type=='user'){

		    $activeTickets = Ticket::getTicketDetail($id)->first();

		    //if ((isset($activeTickets)) && (Gate::denies('user')  || Gate::allows('owner_id', $activeTickets->owner_id)))  {

                CommentStatus::where('tickets.id',$id)->where('comment_status.owner_status','unread')
                ->leftJoin('comments', 'comment_status.comment_id', '=', 'comments.id')
                ->leftJoin('tickets', 'comments.ticket_id', '=', 'tickets.id')
                ->update(array(
                    "comment_status.owner_status" => 'read',
                ));

				$data['comments'] = Comment::getTotalComments($id);
				$data['total'] = count($data['comments']);
			    $data['activeTickets'] = $activeTickets;
			    $data['activeUser'] = "active";

			    return view('ticket.showTicket', $data);
		    //}
		    abort(403);
	    }

    }

	//ticket edit view
    public function edit($id)
    {
	    $activeTickets = Ticket::getTicketEditDetail($id)->first();
		$data['agents'] = User::getAgents($activeTickets->categoryId)->toarray();
		$data['categories'] = Category::getCategories()->toArray();
		$data['priorities'] = Priority::getPriorities()->toArray();
		$data['status'] = Status::getStatus()->toArray();
	    $data['activeTickets'] = $activeTickets;
	    return view('ticket.edit', $data);
    }

	//ticket update
    public function update(Request $request, $id)
    {
		if(Gate::allows('admin') || Gate::allows('agent')  ) {
			if(\Request::ajax()){
				$ticket = Ticket::find($id);
				$this->validate($request,$ticket->updateRules);
				$ticket->subject = $request->subject;
				$ticket->description = $request->description;
				$ticket->priority_id = $request->priority;
				$ticket->agent_id = $request->agent;
				$ticket->status_id = $request->status;
				$ticket->save();

				// $user = User::getTicketOwnerOrAgent($ticket->owner_id,$ticket->agent_id)->get();

				// foreach($user as $userName) {
				// 	$emailInfo = ['to' => $userName->email,'name'=>$userName->name];
				// 	$fieldValues = ['NAME'  => $userName->name, 'TICKETNAME' => $ticket->subject];
				// 	$emailId ='update';
				// 	CommonController::prepareAndSendEmail($emailId, $emailInfo, $fieldValues);
				// }

				if($this->user->user_type=='admin'){
					return ['status' => 'success', 'message' => trans('messages.ticketUpdate'), 'url' => route('ticket.index')];
				}
				if($this->user->user_type=='agent') {
					return ['status' => 'success', 'message' => trans('messages.ticketUpdate'),
						 'url' => route('agent.ticket.agentIndex')];
				}
			}
		}
		else{
			abort(403,trans('messages.notAuthorised'));
		}
    }

	//ticket delete
    public function destroy($id)
    {
		if(Gate::denies('admin')) {
			abort(403, trans('messages.notAuthorised'));
		}

	    if(\Request::ajax()) {
		    $ticketDelete = Ticket::find($id);

		    $user = User::getTicketOwnerOrAgent($ticketDelete->owner_id,$ticketDelete->agent_id)->get();

			// foreach($user as $userName) {
			// 	$emailInfo = ['to' => $userName->email,'name'=>$userName->name];
			// 	$fieldValues = ['NAME'  => $userName->name, 'TICKETNAME' => $ticketDelete->subject];
			// 	$emailId ='delete';
			// 	CommonController::prepareAndSendEmail($emailId, $emailInfo, $fieldValues);

			// }

			$ticketDelete->delete();
		    return ['status' => 'success', 'message' => trans('messages.ticketDelete')];
	    }
    }

	//ticket reopen
	public function reopenTicket($id)
    {
	    if(\Request::ajax()) {
		    $reopenTicket = Ticket::find($id);
		    $reopenTicket->type = 'opened';
		    $reopenTicket->updated_at = Carbon::now();
		    $reopenTicket->save();

		    $user = User::getTicketOwnerOrAgent($reopenTicket->owner_id,$reopenTicket->agent_id)->get();
			// foreach($user as $userName) {
			// 	$emailInfo = ['to' => $userName->email,'name'=>$userName->name];
			// 	$fieldValues = ['NAME'  => $userName->name, 'TICKETNAME' => $reopenTicket->subject];
			// 	$emailId ='reopen';
			// 	CommonController::prepareAndSendEmail($emailId, $emailInfo, $fieldValues);
			// }

		    return ['status' => 'success', 'message' => trans('messages.ticketOpen')];
	    }
    }

	//ticket deactivate
	public function closeTicket($id)
    {
	    if(\Request::ajax()) {
		    $closeTicket = Ticket::find($id);
		    $closeTicket->type = 'closed';
		    $closeTicket->updated_at = Carbon::now();
		    $closeTicket->save();

		    $user = User::getTicketOwnerOrAgent($closeTicket->owner_id,$closeTicket->agent_id)->get();

			// foreach($user as $userName) {
			// 	$emailInfo = ['to' => $userName->email,'name'=>$userName->name];
			// 	$fieldValues = ['NAME'  => $userName->name, 'TICKETNAME' => $closeTicket->subject];
			// 	$emailId ='close';
			// 	CommonController::prepareAndSendEmail($emailId, $emailInfo, $fieldValues);
			// }

		    return ['status' => 'success', 'message' => trans('messages.ticketClose')];
	    }
    }

	//ticket add comment
	public function addComment(Request $request)
    {
	    if (\Request::ajax()){
			$comment = new Comment();
		    $this->validate($request,$comment->rules);

		    $comment->comment =$request->comment;
		    $comment->user_id = $request->userId;
		    $comment->ticket_id = $request->ticketId;
		    $comment->created_at = Carbon::now();
		    $comment->save();

			$commentStatus =new CommentStatus();
			$commentStatus->comment_id =$comment->id;
			$commentStatus->owner_id =$request->ownerId;

			if($this->user->user_type =='user'){
				$commentStatus->owner_status ='read';
			}

			$commentStatus->agent_id =$request->agentId;

			if($this->user->user_type =='agent'){
				$commentStatus->agent_status ='read';
			}
			$commentStatus->save();

		    return $success = ['status' => 'success', 'message' => trans('messages.ticketComment')];
	    }

    }
	//users Activity Functions

	public function userIndex()
	{
		if (Gate::denies('user')) {
			abort(403, trans('messages.notAuthorised'));
		}

		if(\Request::ajax()){

			$tickets = Ticket::getActiveTickets()->where('owner_id',$this->user->id);
				return Datatables::of($tickets)
				->edit_column('updated_at',function($row){
				return Carbon::now()->subMinutes(Carbon::now()->diffInMinutes(Carbon::parse($row->updated_at)))->diffForHumans();
				})

				->edit_column('subject',function($row){
				 $count = CommentStatus::getUserCommentStatus($row->id );
				 if($count>0) {
					 return  $row->subject .'<span class="badge badge-success">'.$count.'</span></a>';
				 }
				 return $row->subject;
				})

				->edit_column('statusName',function($row){
				 return '<span class="label label-sm" style="background-color:'.$row->statusColor.' ">'. $row->statusName .' </span>';
				})

				->edit_column('categoryName',function($row){
				 return '<span class="label label-sm" style="background-color:'.$row->categoryColor.' ">'. $row->categoryName .' </span>';
				})

				->edit_column('priorityName',function($row){
				 return '<span class="label label-sm" style="background-color:'.$row->priorityColor.' ">'. $row->priorityName .' </span>';
				})
				->add_column('action',function($row){
				 return '<a class="btn green btn-sm margin-bottom-10" href="' . URL::route('ticket.show', [$row->id]) . '"><i class="fa fa-eye"></i>View</a>';
				})
				->make(true);
		}
		$data['activeTickets'] = Ticket::getActiveTickets()->where('owner_id',$this->user->id)->take(5)->get();
		$data['completedTickets'] = Ticket::getCompletedTickets()->where('owner_id',$this->user->id)->take(5)->get();
		$data['activeTotal'] = Ticket::where('tickets.type','opened')->Where('owner_id',$this->user->id)->count();
		$data['completedTotal']  = Ticket::where('tickets.type','closed')->Where('owner_id',$this->user->id)->count();
		$data['activeUser'] = 'active';

		return view('ticket.index',$data);
	}

	public function userCompletedTickets(){
		if (Gate::denies('user')) {
			abort(403, trans('messages.notAuthorised'));
		}

		if(\Request::ajax()){
			$completedTickets = Ticket::getCompletedTickets()->where('owner_id',$this->user->id);
			return Datatables::of($completedTickets)
				->edit_column('updated_at',function($row){
				return Carbon::now()->subMinutes(Carbon::now()->diffInMinutes(Carbon::parse($row->updated_at)))->diffForHumans();
				})

				->edit_column('subject',function($row){
				 $count = CommentStatus::getUserCommentStatus($row->id );
				 if($count>0) {
					 return $row->subject .'<span class="badge badge-success">'.$count.'</span></a>';
				 }
				 return $row->subject;
				})

				->edit_column('statusName',function($row){
				 return '<span class="label label-sm" style="background-color:'.$row->statusColor.' ">'. $row->statusName .' </span>';
				})

				->edit_column('categoryName',function($row){
				 return '<span class="label label-sm" style="background-color:'.$row->categoryColor.' ">'. $row->categoryName .' </span>';
				})

				->edit_column('priorityName',function($row){
				 return '<span class="label label-sm" style="background-color:'.$row->priorityColor.' ">'. $row->priorityName .' </span>';
				})
				->add_column('action',function($row){
					return '<a class="btn green btn-sm margin-bottom-10" href="' . URL::route('ticket.show', [$row->id]) . '"><i class="fa fa-eye"></i>View</a>';
				})
				->make(true);
		}
	}

	//agent Activity Functions

	public function agentIndex()
	{
		if (Gate::denies('agent')) {
			abort(403, trans('messages.notAuthorised'));
		}

		if(\Request::ajax()){

			$tickets = Ticket::getActiveTickets()->where(function ($query) {
						$query->orWhere('owner_id',$this->user->id)
						->orWhere('agent_id',$this->user->id) ;});
			return Datatables::of($tickets)
				->edit_column('updated_at',function($row){
				return Carbon::now()->subMinutes(Carbon::now()->diffInMinutes(Carbon::parse($row->updated_at)))->diffForHumans();
				})

				->edit_column('subject',function($row){
				 $count = CommentStatus::getAgentCommentStatus($row->id );
				 if($count>0) {
					 return $row->subject .'<span class="badge badge-success">'.$count.'</span></a>';
				 }
				 return $row->subject ;
				})

				->edit_column('statusName',function($row){
				 return '<span class="label label-sm" style="background-color:'.$row->statusColor.' ">'. $row->statusName .' </span>';
				})

				->edit_column('categoryName',function($row){
				 return '<span class="label label-sm" style="background-color:'.$row->categoryColor.' ">'. $row->categoryName .' </span>';
				})

				->edit_column('priorityName',function($row){
				 return '<span class="label label-sm" style="background-color:'.$row->priorityColor.' ">'. $row->priorityName .' </span>';
				})
				->add_column('action',function($row){
					return '<a class="btn green btn-sm margin-bottom-10" href="' . URL::route('ticket.show', [$row->id]) . '"><i class="fa fa-eye"></i>View</a>';
				})
				->make(true);
		}

		$data['activeTickets'] = Ticket::getActiveTickets()
							   ->where(function ($query) {
								   $query->orWhere('owner_id', $this->user->id)
										 ->orWhere('agent_id', $this->user->id);
							   })
							   ->take(5)
							   ->get();
		$data['completedTickets'] = Ticket::getCompletedTickets()
								  ->where(function ($query) {
									  $query->orWhere('owner_id', $this->user->id)
											->orWhere('agent_id', $this->user->id);
								  })
								  ->take(5)
								  ->get();
		$data['activeTotal'] = Ticket::where('tickets.type', 'opened')
							 ->where(function ($query) {
								 $query->orWhere('owner_id', $this->user->id)
									   ->orWhere('agent_id', $this->user->id);
							 })
							 ->count();
		$data['completedTotal'] = Ticket::where('tickets.type', 'closed')
								->where(function ($query) {
									$query->orWhere('owner_id', $this->user->id)
										  ->orWhere('agent_id', $this->user->id);
								})
								->count();
		$data['activeAgent'] = 'active';

		return view('ticket.index', $data);
	}

	public function agentCompletedTickets(){
		if (Gate::denies('agent')) {
			abort(403, trans('messages.notAuthorised'));
		}

		if(\Request::ajax()){
			$completedTickets = Ticket::getCompletedTickets()->where(function ($query) {
					$query->orWhere('owner_id',$this->user->id)
					->orWhere('agent_id',$this->user->id) ;});
			return Datatables::of($completedTickets)
				->edit_column('updated_at',function($row){
				return Carbon::now()->subMinutes(Carbon::now()->diffInMinutes(Carbon::parse($row->updated_at)))->diffForHumans();
				})

				->edit_column('subject',function($row){
				 $count = CommentStatus::getAgentCommentStatus($row->id );
				 if($count>0) {
					 return $row->subject .'<span class="badge badge-success">'.$count.'</span></a>';
				 }
				 return $row->subject;
				})

				->edit_column('statusName',function($row){
				 return '<span class="label label-sm" style="background-color:'.$row->statusColor.' ">'. $row->statusName .' </span>';
				})

				->edit_column('categoryName',function($row){
				 return '<span class="label label-sm" style="background-color:'.$row->categoryColor.' ">'. $row->categoryName .' </span>';
				})

				->edit_column('priorityName',function($row){
				 return '<span class="label label-sm" style="background-color:'.$row->priorityColor.' ">'. $row->priorityName .' </span>';
				})
				->add_column('action',function($row){
					return '<a class="btn green btn-sm margin-bottom-10" href="' . URL::route('ticket.show', [$row->id]) . '"><i class="fa fa-eye"></i>View</a>';
				})
				->make(true);

		}
	}

}
