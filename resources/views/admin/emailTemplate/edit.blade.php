<input type="hidden" name="id" value="{{ $emailTemplateData['id'] }}">
<div class="form-group">
    <label class="col-md-2 control-label"> Subject </label>
    <div class="col-md-9">
        <input type="text" class="form-control" placeholder="Email Subject" name="subject" id="subject" value="{{ $emailTemplateData['subject'] }}">
        <span class="help-block"> </span>
    </div>
</div>
<div class="form-group">
    <label class="col-md-2 control-label"> Content </label>
    <div class="col-md-9">
        <textarea name="contents" id="contents" placeholder="Write Your Content Here.....">{{ $emailTemplateData['content'] }}</textarea>
        <span class="help-block"> </span>
    </div>
</div>
<script>
    $('#contents').summernote();
</script>

