<div id="agentEditMessage"></div>
<div class="row">
	<div class="col-md-12">
		<input type="hidden" name="_method" value="put">
		<input type="hidden" name="id" id="id" value="{{ $agent[0]->id }}">
		<div class="form-group">
			<label class="col-md-2 control-label">Name</label>
			<div class="col-md-9">
				<input type="text" class="form-control" placeholder="Agent Name" name="name" id="name" value="{{ $agent[0]->userName }}">
				<span class="help-block">  </span>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-2 control-label">Email</label>
			<div class="col-md-9">
				<input type="email" class="form-control" placeholder="Agent Email" name="email" id="email" value="{{ $agent[0]->email }}">
				<span class="help-block">  </span>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-2 control-label">Password</label>
			<div class="col-md-9">
				<input type="password" class="form-control" placeholder="Agent Password" name="password" id="password">
				<span class="help-block">  </span>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-2 control-label">Categories</label>
			<div class="col-md-9">
				{!! Form::select('category[]',$categories,$selectedCategory,['class' => 'form-control select2 select2-multiple','id'=>'category','multiple']) !!}
				<span class="help-block">  </span>
			</div>
		</div>
	</div>
</div>
<script>
	$(".select2").select2({
		placeholder: "Select a category",
		width: 'resolve',
		width: '100%'
	});
</script>