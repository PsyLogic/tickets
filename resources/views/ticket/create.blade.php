<div class="row">
	<div class="col-md-12">
		<div id="addTicketMessage"></div>
		<div class="form-group">
			<label class="col-md-2 control-label">Subject:</label>
			<div class="col-md-8">
				<input type="text" class="form-control" placeholder="A brief of your issue ticket" name="subject" id="subject">
				<span class="help-block">  </span>
			</div>
		</div>
		<div class="form-group ">
			<label class="col-md-2 control-label">Priority</label>
			<div class="col-md-8">
				{!! Form::select('priority',(['' => 'Select a priority'] + $priority),null,['class' => 'form-control', 'id' => 'priority']) !!}
				<span class="help-block">  </span>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-2 control-label">Categories</label>
			<div class="col-md-8">
				{!! Form::select('category',(['' => 'Select a Category'] + $category),null,['class' => 'form-control','id'=>'category']) !!}
				<span class="help-block">  </span>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-2 control-label">Description:</label>
			<div class="col-md-8">
			<textarea type="text" class="form-control" placeholder="Describe your issue here in details"name="description" id="description"> </textarea>
				<span class="help-block">  </span>
			</div>
		</div>
	</div>
</div>
