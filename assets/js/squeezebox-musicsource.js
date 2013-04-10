
$(document).ready(function()
{
        $.ajax({
                url: 'action.php',
                type: 'POST',
                data: '{ "action": "music_source", "cmd": "list" }',
                success: function (data) {
                        $("#music_source_loading").fadeOut("normal");
                        for (var i = 0; i < data.result.length; i++) {
                                $("#music_source_list").append("<b>" + data.result[i] + "</b><br/>");
                        }
                }
        });
});
