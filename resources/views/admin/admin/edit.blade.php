
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <input type="hidden" name="id" id="id" value="{{ $admin->id }}">
            <div class="form-group">
                <label class="col-md-2 control-label">Name</label>
                <div class="col-md-9">
                    <input type="text" class="form-control" placeholder="Admin Name" name="name" id="name" value="{{ $admin->name }}">
                    <span class="help-block">  </span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Email</label>
                <div class="col-md-9">
                    <input type="email" class="form-control" placeholder="Admin Email" name="email" id="email" value="{{ $admin->email }}">
                    <span class="help-block">  </span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Password</label>
                <div class="col-md-9">
                    <input type="password" class="form-control" placeholder="Admin Password" name="password" id="password">
                    <span class="help-block">  </span>
                </div>
            </div>
        </div>
    </div>
</div>
