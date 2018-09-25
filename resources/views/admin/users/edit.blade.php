<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <input type="hidden" name="_method" value="put">
            <input type="hidden" name="id" id="id" value="{{ $userData->id }}">
            <div class="form-group">
                <label class="col-md-3 control-label">Name</label>
                <div class="col-md-8">
                    <input type="text" class="form-control" placeholder="Name" name="name" id="name" value="{{ $userData->name }}">
                    <span class="help-block">  </span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label">Email</label>
                <div class="col-md-8">
                    <input type="email" class="form-control" placeholder="Agent Email" name="email" id="email" value="{{ $userData->email }}">
                    <span class="help-block">  </span>
                </div>
            </div>
        </div>
    </div>
</div>
