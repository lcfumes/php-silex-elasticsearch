(function($) {
    $.extend( {
        searchClient: function(form) {

            $("#user-search").val("Wait...");
            $("#user-search").attr("disabled", "disabled");
            $("#result_content_table").html("");

            $.ajax({
                url : "/clients/search",
                type: "POST",
                dataType: "json",
                data : $(form).serialize(),
                success: $.showClient,
                error: function (jqXHR, textStatus, errorThrown) {
                    $("#user-search").val("Search");
                    $("#user-search").removeAttr("disabled");
                }
            });
        },

        showClient: function(response) {
            $("#user-search").val("Search");
            $("#user-search").removeAttr("disabled");

            var content = "";

            for (var i=0; i < response.length; i++) {
                content += "<tr>";
                content += "<td>" + response[i].first_name + "</td>";
                content += "<td>" + response[i].last_name + "</td>";
                content += "<td>" + response[i].email + "</td>";
                content += "<td>" + response[i].age + "</td>";
                content += "<td> X </td>";
                content += "</tr>";
            }
            
            $("#result_content_table").append(content);
        }
    });
})(jQuery);

$('document').ready(function() {
    $("#frm-search-client").submit(function(e) {
        return $.searchClient(this);
    });
});