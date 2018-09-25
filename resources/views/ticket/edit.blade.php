<div class="row">
	<div class="col-md-12">
		<input type="hidden" name="id" id="id" value="{{ $activeTickets->id }}">
		<input type="hidden" name="type" id="type" value="{{ $activeTickets->type }}">
		<div class="form-group">
			<label class="col-md-2 control-label">Subject:</label>
			<div class="col-md-9">
				<input type="text" class="form-control" placeholder="A brief of your issue ticket" name="subject" id="subject" value="{{ $activeTickets->subject }}">
				<span class="help-block">  </span>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-2 control-label">Categories</label>
			<div class="col-md-9">
				{!! Form::select('category',(['' => 'Select a Category'] + $categories),$activeTickets->categoryId,['class' => 'form-control','id'=>'category','disabled']) !!}
				<span class="help-block">  </span>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-2 control-label">Agents</label>
			<div class="col-md-9">
				{!! Form::select('agent',(['' => 'Select a Agent'] + $agents  ),$activeTickets->agentId,['class' => 'form-control','id'=>'agent']) !!}
				<span class="help-block">  </span>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-2 control-label">Priorities</label>
			<div class="col-md-9">
				{!! Form::select('priority',(['' => 'Select a Priority'] + $priorities),$activeTickets->priorityId,['class' => 'form-control','id'=>'priority']) !!}
				<span class="help-block">  </span>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-2 control-label">Status</label>
			<div class="col-md-9">
				{!! Form::select('status',(['' => 'Select a Status'] + $status),$activeTickets->statusId,['class' => 'form-control','id'=>'status']) !!}
				<span class="help-block">  </span>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-2 control-label">Description:</label>
			<div class="col-md-9">
				<textarea type="text" class="form-control" placeholder="Describe your issue here in details" name="description" id="description"> {{ $activeTickets->description }} </textarea>
				<span class="help-block">  </span>
			</div>
		</div>
	</div>
</div>

