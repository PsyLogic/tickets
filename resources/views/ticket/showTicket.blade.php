@extends(($user->user_type =='user')?"layouts.userApp":"layouts.app")

@section('title')
	 Ticket Detail - Ticket System
@endsection

@section('pageLevelStyle')
<link href="{{ asset('assets/global/plugins/bootstrap-summernote/summernote.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/pages/css/blog.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('page-title')
	Ticket Management
@endsection

@section('page-breadcrumb')
	<li>
		<a href="{{ url('/') }}">Home</a>
		<i class="fa fa-circle"></i>
	</li>
	<li>
		<span class="active">Ticket </span>
		<i class="fa fa-circle"></i>
	</li>

	<li>
		<span class="active">Show</span>
	</li>
@endsection

@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		@if($activeTickets->type =='opened')
			<span class="pull-right">
				@if($user->user_type =='admin')
					<div class="btn-group btn-group-devided" >
						<a href="javascript:;" id="closedTicket" data-pk="{{$activeTickets->id }}" data-name= "{{ $activeTickets->subject }}"  class="btn sbold btn-success">Mark as Closed
						</a>
						<a href="javascript:;" id="closedDelete" data-pk="{{$activeTickets->id }}" data-name= "{{ $activeTickets->subject }}" class="btn sbold red">Delete<i class="icon-trash"></i>
						</a>
					</div>
				@endif
				@if($user->user_type =='agent')
					<div class="btn-group btn-group-devided" >
						<a href="javascript:;" id="closedTicket" data-pk="{{$activeTickets->id }}" data-name= "{{ $activeTickets->subject }}"  class="btn sbold btn-success">Mark as Closed <i class="fa fa-check"></i>
						</a>
						<a  href="javascript:;" data-pk="{{$activeTickets->id }}" onclick="showModal({{$activeTickets->id }})"  class="btn sbold btn-success"><i class="icon-pencil"></i> Edit
						</a>
					</div>
				@endif
				@if($user->user_type =='user')
					<div class="btn-group btn-group-devided" >
						<a href="javascript:;" id="closedTicket" data-pk="{{$activeTickets->id }}" data-name= "{{ $activeTickets->subject }}"  class="btn sbold btn-success">Mark as Closed <i class="fa fa-check"></i>
						</a>
					</div>
				@endif
			</span>
		@endif
		@if($activeTickets->type =='closed')
			<span class="pull-right">
				@if($user->user_type =='admin')
					<div class="btn-group btn-group-devided" >
						<a href="javascript:;" id="reopenTicket" data-pk="{{$activeTickets->id }}" data-name= "{{ $activeTickets->subject }}" class="btn sbold btn-success">Reopen Ticket
						</a>
						<a href="javascript:;" id="reopenDelete" data-pk="{{$activeTickets->id }}" data-name= "{{ $activeTickets->subject }}" class="btn sbold red">Delete<i class="icon-trash"></i>
						</a>
					</div>
				@endif
				@if($user->user_type =='agent')
					<div class="btn-group btn-group-devided" >
						<a href="javascript:;" id="reopenTicket" data-pk="{{$activeTickets->id }}" data-name= "{{ $activeTickets->subject }}" class="btn sbold btn-success">Reopen Ticket
						</a>
						<a  href="javascript:;"  data-pk="{{$activeTickets->id }}" onclick="showModal({{$activeTickets->id }})"  class="btn sbold btn-success">Edit<i class="icon-badge"></i>
						</a>
					</div>
				@endif
				@if($user->user_type =='user')
					<div class="btn-group btn-group-devided" >
						<a href="javascript:;" id="reopenTicket" data-pk="{{$activeTickets->id }}" data-name= "{{ $activeTickets->subject }}" class="btn sbold btn-success">Reopen Ticket
						</a>
					</div>
				@endif
			</span>
		@endif
		<div class="caption font-dark">
			<span class="caption-subject bold uppercase"><i class="icon-layers"></i> {{ $activeTickets->subject }}</span>
		</div>
	</div>
	<div class="portlet-body">
		<div class="row">
			<div class="col-md-12">
				<div class="blog-page blog-content-2">
					<div class="blog-single-content bordered blog-container">
						<div class="row">
							<div class="col-md-6 padding">
								<p><strong> Owner:</strong> <span>{{ $activeTickets->ownerName }}</span></p>
								<p><strong> Status:</strong> <span class="label label-sm" style="background-color: {{ $activeTickets->statusColor }}">{{ ucfirst($activeTickets->statusName) }}</span></p>
								<p><strong> Priority:</strong> <span class="label label-sm" style="background-color: {{ $activeTickets->priorityColor }}">{{ ucfirst($activeTickets->priorityName) }}</span></p>
							</div>
							<div class="col-md-6 padding">
								<p><strong> Responsible:</strong> <span> {{ $activeTickets->agentName }}</span> </p>
								<p><strong> Category:</strong> <span class="label label-sm" style="background-color: {{ $activeTickets->categoryColor }}">{{ ucfirst($activeTickets->categoryName) }}</span> </p>
								<p><strong> Created:</strong> <span>{{ \Carbon\Carbon::now()->subMinutes( \Carbon\Carbon::now()->diffInMinutes( \Carbon\Carbon::parse($activeTickets->created_at)))->diffForHumans() }}</span></p>
								<p><strong> Last Update:</strong> <span>{{ \Carbon\Carbon::now()->subMinutes( \Carbon\Carbon::now()->diffInMinutes( \Carbon\Carbon::parse($activeTickets->updated_at)))->diffForHumans()   }}</span></p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-12 padding">
				<h4>
					<i class="fa fa-align-left"></i> Description
				</h4>
				<p> {!! $activeTickets->description !!}</p>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="blog-page blog-content-2">
				<div class="blog-single-content bordered blog-container">
					<div class="blog-comments">
						<h3 class="sbold blog-comments-title">Comments({{ $total }})</h3>
						<div class="c-comment-list">
							@foreach($comments as $comment)
								<div class="media">
									<div class="media-body">
										<h4 class="media-heading">
										  {{ ucwords($comment->name) }}
											@if($comment->user_type != 'user')
											<span class="badge badge-info badge-roundless"> {{ ucwords($comment->user->user_type) }}: {{ ucwords($comment->user->name) }} </span>
											@endif
											on
										  <span class="c-date"> {!! date('jS F Y',strtotime($comment->created_at)) !!}, {!! date("g:i A",strtotime($comment->created_at)) !!}</span>
										</h4> {!! $comment->comment !!}
									</div>
								</div>
							@endforeach
						</div>
						<h3 class="sbold blog-comments-title">Leave A Comment</h3>
						<form id="ticketComment">
							<input type="hidden" name="ticketId" id="ticketId" value="{{ $activeTickets->id }}">
							<input type="hidden" name="userId" id="userId" value="{{ Auth::user()->id }}">
							<input type="hidden" name="ownerId" id="ownerId" value="{{ $activeTickets->owner_id }}">
							<input type="hidden" name="agentId" id="agentId" value="{{ $activeTickets->agent_id }}">
							<div class="form-group">
								<textarea name="comment" id="comment" placeholder="Write Your Comment Here....."></textarea>
							  	<span class="help-block"> </span>
							</div>
							<div class="form-group">
								<button type="submit" class="btn blue uppercase btn-md sbold btn-block">Submit</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@section('modal')
	<div id="editModel" class="modal fade" tabindex="-1" data-width="400">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h4 class="modal-title">Edit Ticket</h4>
				</div>
				{!! Form::open(array('id'=>'ticketUpdate','class'=>'form-horizontal')) !!}
				<input type="hidden" name="_method" value="put">
				<div class="modal-body" >Loading...</div>
				<div class="modal-footer">
					<button type="button" id="close" data-dismiss="modal" class="btn dark btn-outline left">Close</button>
					<button type="submit" form="ticketUpdate" class="btn btn-success left">Update</button>
				</div>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
@endsection

@section('script')
<script src="{{ asset('assets/global/plugins/bootbox/bootbox.min.js') }}" ></script>
<script src="{{ asset('assets/global/plugins/bootstrap-summernote/summernote.min.js') }}" type="text/javascript"></script>
<script>
$(function() {
    $('#comment').summernote();
	$('#closedDelete').on('click',function( e) {
		var id = $(this).attr('data-pk');
		var name = $(this).attr('data-name');

		bootbox.confirm('<div class="modal-header"><h4 class="modal-title">{{ trans('core.delete') }}</h4></div><div class="modal-body">' +
				'<div class="bootbox-body">{{ trans('messages.delete') }} '+name+'?</div></div>',
		function (result) {
			if (result) {
				var url = '{{ URL::route('ticket.destroy', ['#id']) }}';
				url = url.replace('#id', id);
				$.ajax({
					url: url,
					beforeSend: function () {
						App.blockUI({target:".page-container",boxed:!0});
					},
					type: "DELETE",
					success: function (data) {
						App.unblockUI('.page-container');
						showToastr('success', data.message, 'success!');
						var url = '{{ URL::route('ticket.index') }}';
						window.location.href = url;
					}
				});
			}
		});
	});

	$('#reopenDelete').on('click',function( e) {
		var id = $(this).attr('data-pk');
		var name = $(this).attr('data-name');

		bootbox.confirm('<div class="modal-header"><h4 class="modal-title">{{ trans('core.delete') }}</h4></div><div class="modal-body">' +
				'<div class="bootbox-body">{{ trans('messages.delete') }} '+name+'?</div></div>',
		function (result) {
			if (result) {
				var url = '{{ URL::route('ticket.destroy', ['#id']) }}';
				url = url.replace('#id', id);
				$.ajax({
					url: url,
					beforeSend: function () {
						App.blockUI({target:".page-container",boxed:!0});
					},
					type: "DELETE",
					success: function (data) {
						App.unblockUI('.page-container');
						showToastr('success', data.message, 'success!');
						var url = '{{ URL::route('ticket.index') }}';
						window.location.href = url;
					}
				});
			}
		});
	});

	$('#reopenTicket').on('click',function(){

		var id = $(this).attr('data-pk');
		var name = $(this).attr('data-name');

		bootbox.confirm('<div class="modal-header"><h4 class="modal-title">{{ trans('core.reopen') }}</h4></div><div class="modal-body">' +
				'<div class="bootbox-body">{{ trans('messages.reopen') }} '+name+'?</div></div>',
			function (result) {
				if (result) {
					var url = '{{ URL::route('ticket.reopenTicket', ['#id']) }}';
					url = url.replace('#id', id);
					$.ajax({
						url: url,
						beforeSend: function () {
							App.blockUI({target: ".page-container", boxed: !0});
						},
						type: "post",
						success: function (data) {
							@if($user->user_type=='admin')
                                App.unblockUI('.page-container');
								if(data.status=='fail'){
									showToastr('success','Ticket Reopened!');
								}
								else{
									showToastr('success', data.message, 'success!');
								}
								var redirect = '{{ URL::route('ticket.index') }}';
								window.location.href = redirect;
							@endif
                            @if($user->user_type=='agent')
                                App.unblockUI('.page-container');
                                showToastr('success', data.message, 'success!');
								var redirect = '{{ URL::route('agent.ticket.agentIndex') }}';
								window.location.href = redirect;
							@endif
                            @if($user->user_type=='user')
                                App.unblockUI('.page-container');
                                showToastr('success', data.message, 'success!');
								var redirect = '{{ URL::route('user.ticket.userIndex') }}';
								window.location.href = redirect;
							@endif
						}
					});
				}
			}
		);
	});

	$('#closedTicket').on('click',function(){
		var id = $(this).attr('data-pk');
		var name = $(this).attr('data-name');

		bootbox.confirm('<div class="modal-header"><h4 class="modal-title">{{ trans('core.close') }}</h4></div><div class="modal-body">' +
				'<div class="bootbox-body">{{ trans('messages.close') }} '+name+'?</div></div>',
			function (result) {
				if (result) {
					var url = '{{ URL::route('ticket.closeTicket', ['#id']) }}';
					url = url.replace('#id', id);
					$.ajax({
						url: url,
						beforeSend: function () {
							App.blockUI({target: ".page-container", boxed: !0});
						},
						type: "post",
						success: function (data) {
							@if($user->user_type=='admin')
								App.unblockUI('.page-container');
                                showToastr('success', data.message, 'success!');
								var redirect = '{{ URL::route('ticket.index') }}';
								window.location.href = redirect;
							@endif
							@if($user->user_type=='agent')
								App.unblockUI('.page-container');
                                showToastr('success', data.message, 'success!');
								var redirect = '{{ URL::route('agent.ticket.agentIndex') }}';
								window.location.href = redirect;
							@endif
							@if($user->user_type=='user')
								App.unblockUI('.page-container');
                                showToastr('success', data.message, 'success!');
								var redirect = '{{ URL::route('user.ticket.userIndex') }}';
								window.location.href = redirect;
							@endif
						}
					});
				}
			}
		);
	});

	$('#ticketComment').submit(function (e) {
        e.preventDefault();
        var thisForm = $(this);

        $.ajax({
            method: 'post',
            url: '{{ URL::route('ticket.addComment') }}',
            dataType: 'json',
            data: thisForm.serialize(),
            beforeSend: function (xhr) {
                hideErrors();
                thisForm.find('button[type="submit"]').prop('disabled', true);
                App.blockUI({ target:"#ticketComment", boxed:!0 });
            },
            success: function (response) {
                App.unblockUI('#ticketComment');
                hideMessage('ticketEditMessage', 'alert-info,alert-success,alert-danger');
                slideToElement(thisForm);
                thisForm.find('button[type="submit"]').prop('disabled', false);
                var id = $('#ticketId').val();
				var url = '{{ URL::route('ticket.show', ['#id']) }}';
					url = url.replace('#id', id);
				if(response.status == 'success'){
					window.location.href = url;
				}
            },
            error: function (xhr, textStatus, thrownError) {
                App.unblockUI('#ticketComment');
                slideToElement(thisForm);
                thisForm.find('button[type="submit"]').prop('disabled', false);
                for (var key in xhr.responseJSON) {
                    if (xhr.responseJSON.hasOwnProperty(key)) {
                        var obj = xhr.responseJSON[key];
                        showInputError(key, obj[0]);
                    }
                }
            }
        });
    });
	$('#ticketUpdate').submit(function (e) {
		e.preventDefault();
		var id = $('input[name=id]').val();
		var url='{{ URL::route('ticket.update',['#id']) }}';
		url = url.replace('#id', id);
		var thisForm = $(this);
		$.ajax({
			method: 'put',
			url: url,
			dataType: 'json',
			data: thisForm.serialize(),
			beforeSend: function (xhr) {
				hideErrors();
				thisForm.find('button[type="submit"]').prop('disabled', true);
				App.blockUI({ target:"#ticketUpdate", boxed:!0 });
			},
			success: function (response) {
				App.unblockUI('#ticketUpdate');
				slideToElement(thisForm);
				thisForm.find('button[type="submit"]').prop('disabled', false);
				$('#editModel').modal('hide');
				showToastr('success', response.message, 'success!');
				window.location.href = response.url;
			},
			error: function (xhr, textStatus, thrownError) {
				App.unblockUI('#ticketUpdate');
				slideToElement(thisForm);
				thisForm.find('button[type="submit"]').prop('disabled', false);
				for (var key in xhr.responseJSON) {
					if (xhr.responseJSON.hasOwnProperty(key)) {
						var obj = xhr.responseJSON[key];
						showInputError(key, obj[0]);
					}
				}
			}
		});
	});
});
function  showModal(id){
	$('#editModel').find('.modal-body').html('Loading..');
	hideErrors();

	$("#editModel").modal('show');
	var url='{{ URL::route('ticket.edit',['#id']) }}';
	url = url.replace('#id', id);

	$.ajax({
		method: 'GET',
		url:url ,
		success: function (response) {
			$('#editModel').find('.modal-body').html(response);
		},
	});
}
</script>
@endsection

