<input type="hidden" name="id" value="{{ $categoryData->id }}">
<div class="form-group">
    <label class="col-md-2 control-label">Name</label>
    <div class="col-md-8">
        <input type="text" class="form-control" placeholder="Name" name="name" id="name" value="{{ $categoryData->name }}">
        <span class="help-block"></span>
    </div>
</div>
<div class="form-group">
    <label class="col-md-2 control-label"> Color </label>
    <div class="col-md-8">
        <div class="input-group color colorpicker-default" data-color="{{ $categoryData->color }}" data-color-format="rgba" colorpicker-parent="true" >
            <input type="text" class="form-control" value="{{ $categoryData->color }}" id="color" name="color" readonly>
            <span class="input-group-btn">
                <button class="btn default" type="button">
                    <i style="background-color: {{ $categoryData['color'] }};"></i>&nbsp;</button>
            </span>
        </div>
        <span class="help-block"> </span>
    </div>
</div>
<script>
    $(".colorpicker-default").colorpicker({format:"hex"});
</script>