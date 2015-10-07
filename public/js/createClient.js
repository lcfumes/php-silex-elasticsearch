(function($) {
    $.extend( {
        createClient: function(form) {

            $("#msg-success").hide();
            $("#msg-error").hide();

            $("#user-register").val("Wait...");
            $("#user-register").attr("disabled", "disabled");

            $.ajax({
                url : "/clients/save",
                type: "POST",
                data : $(form).serialize(),
                success: function(data, textStatus, jqXHR) {
                    if (data.result == true) {
                        $("#msg-success").show();
                        $("#msg-error").hide();
                        $("#user-register").hide();
                    } else {
                        $("#msg-success").hide();
                        $("#msg-error").show();
                        $("#user-register").val("Register");
                        $("#user-register").removeAttr("disabled");
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $("#msg-success").hide();
                    $("#msg-error").show();
                    $("#user-register").val("Register");
                    $("#user-register").removeAttr("disabled");
                }
            });
            return false;
        }
    });
})(jQuery);

$('document').ready(function() {
    $("#frm-create-client").submit(function() {
        return $.createClient(this);
    });
});