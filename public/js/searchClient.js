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
                        $("#user-search").val("Search");
                        $("#user-search").removeAttr("disabled");
                    } else {
                        $("#user-search").val("Search");
                        $("#user-search").removeAttr("disabled");
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $("#user-search").val("Search");
                    $("#user-search").removeAttr("disabled");
                }
            });
            var content = "<tr>";
            content += "<td>Luiz</td>";
            content += "<td>Fumes</td>";
            content += "<td>lcfumes@gmail.com</td>";
            content += "<td>33</td>";
            $("#result_content_table").append(content);
            return false;
        }
    });
})(jQuery);

$('document').ready(function() {
    $("#frm-search-client").submit(function() {
        return $.searchClient(this);
    });
});