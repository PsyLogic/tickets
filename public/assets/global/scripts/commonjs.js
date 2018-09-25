function showResponseMessage(response, divID)
{
    $('#'+divID).html('');

    if(response.status == "fail") {
        if (typeof response.errors == "object") {
            for(var key in response.errors) {
                if (response.errors.hasOwnProperty(key)) {
                    var obj = response.errors[key];
                    showInputError(key, obj[0]);
                }
            }
        } else {
            $('#'+divID).html('<div class="alert alert-danger"><p>' + response.message + '</p></div>');
        }

    } else if(response.status === "success") {
        if (response.message != undefined) {
            $('#' + divID).html('<div class="alert alert-success"><p>' + response.message + '</p></div>');
        }
        if(response.action == "redirect") {
            window.location.href = response.url;
        }

    } else if(response.status == "responsePending") {
        $('#'+divID).html('<div class="alert alert-info"><p>'+response.message+'</p></div>');
    }
}

function hideErrors() {
    $(".has-error").each(function () {
       $(this).find(".help-block").text("");
        $(this).removeClass("has-error");
    });
}

function showInputError(inputName, errorMessage) {
    var formGroup = $("#"+inputName).closest(".form-group");
    formGroup.addClass("has-error");
    formGroup.find(".help-block").text(errorMessage);
}

function showToastr(messageType, message, messageTitle) {
    toastr[messageType](message, messageTitle)

    toastr.options = {
        "closeButton": true,
        "debug": false,
        "positionClass": "toast-top-right",
        "onclick": null,
        "showDuration": "1000",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
}

function hideMessage(id, className){
    $('#'+id).removeClass(className);
    $('#'+id).html('');
}

function slideToElement(element){
    $("html, body").animate({scrollTop: $(element).offset().top-50 }, 1000);
}

