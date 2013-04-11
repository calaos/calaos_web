

$(document).ready(function()
{
        $.ajax({
                url: 'action.php',
                type: 'POST',
                data: '{ "action": "music_source", "cmd": "list" }',
                success: function (data) {
                        $("#music_source_loading").hide();
                        for (var i = 0; i < data.result.length; i++) {

                                var row = '<tr>';
                                row += '<td><b>' + data.result[i]['name'] + '</b></td>';
                                row += '<td>' + data.result[i]['version'] + '</td>';
                                row += '<td>' + data.result[i]['ip'] + '</td>';
                                row += '<td><button class="btn" type="button" data-loading-text="Connecting...">Connect</button></td>';
                                row += '</tr>';

                                var tdataElement = $(row);
                                var btn = tdataElement.find(":button");
                                var server = data.result[i];
                                btn.click(function() {
                                        btn.button('loading');
                                        $.ajax({
                                                url: 'action.php',
                                                type: 'POST',
                                               data: '{ "action": "music_source", "cmd": "setServer", "value": {"ip":"' + server['ip'] + '", "name":"' + server['name'] + '", "mac":"' + server['mac'] + '", "uuid":"' + server['uuid'] + '"} }',
                                                success: function (data) {
                                                        setTimeout(function () {
                                                                btn.button('reset');
                                                        }, 1000)
                                                }
                                        });
                                });
                                $('#music_source_list > tbody:last').append(tdataElement);
                        }
                }
        });

        $("#btManualIP").click(function () {
                var btn = $(this);
                btn.button('loading');
                $.ajax({
                        url: 'action.php',
                       type: 'POST',
                       data: '{ "action": "music_source", "cmd": "setServer", "value": {"ip":"' + $("#inputManualIP").val() + '"} }',
                       success: function (data) {
                               setTimeout(function () {
                                       btn.button('reset');
                               }, 1000)
                       }
                });
        });

        $("#btChangeName").click(function ()
        {
                var btn = $(this);
                btn.button('loading');
                $.ajax({
                        url: 'action.php',
                        type: 'POST',
                        data: '{ "action": "music_source", "cmd": "setName", "value": "' + $("#inputName").val() + '" }',
                        success: function (data) {
                                setTimeout(function () {
                                        btn.button('reset');
                                }, 1000)
                        }
                });
        });

	$('#wizard').on('finished', function(e, data) {
	    console.log("Wizard Finished");
	    $.ajax({
                       url: 'action.php',
                       type: 'POST',
                       data: '{ "action": "write_config", "cmd": "all", "value": {"hostname":"' +  $("#inputHostname").val() + '", "calaos_password": "' + $("#inputPassword").val() + '", "calaos_user": "' + $("#inputUsername").val() + '" }}',
                        success: function (data) {
                                setTimeout(function () {
                                        btn.button('reset');
                                }, 1000)
                        }
	    });
	    
        });
});


