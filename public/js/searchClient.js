(function($) {
    $.extend( {
        searchClient: function(form) {

            $("#user-search").val("Wait...");
            $("#user-search").attr("disabled", "disabled");

            $.ajax({
                url : "/clients/search",
                type: "POST",
                data : $(form).serialize(),
                success: function(data, textStatus, jqXHR) {
                    if (data.result == true) {
                        $("#user-search").val("search");
                        $("#user-search").removeAttr("disabled");
                    } else {
                        $("#user-search").val("search");
                        $("#user-search").removeAttr("disabled");
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $("#user-search").val("search");
                    $("#user-search").removeAttr("disabled");
                }
            });
            return false;
        }
    });
})(jQuery);

$('document').ready(function() {
    $("#frm-search-client").submit(function() {
        return $.searchClient(this);
    });
});