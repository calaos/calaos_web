//Iphone js functions

var img_loader = null;
var exit_camera = false;
var restart_camera = true;

function stop_camera()
{
        if (!restart_camera)
                exit_camera = true;
}

function start_camera_single(id)
{
        restart_camera = true;
        exit_camera = false;
        WA.AddEventListener("endslide", function () { refresh_camera(id); });
}

function refresh_camera(camid)
{
        var obj = null;

        WA.AddEventListener("beginslide", stop_camera);

        obj = document.getElementById("camera_single_" + camid);

        if (obj == null)
        {
                exit_camera = true;
                return;
        }

        // The dummy above enforces a bypass of the browser image cache
        // Here we load the image
        var url = "../camera.php?camera_id=" + camid + "&width=200&height=153";
        date = new Date();
        url += '&dummy=' + date.getTime().toString(10);
        delete date;

        if (img_loader != null) delete img_loader;
        img_loader = new Image();

        //loading done callback
        img_loader.onload = function()
                {
                        var o = document.getElementById("camera_single_" + camid);
                        if (o == null) return;
                        o.src = img_loader.src;
                }

        img_loader.src = url;

        if (exit_camera == false)
        {
                setTimeout("refresh_camera('" + camid + "')", 1000);
                restart_camera = false;
        }
}

var async = null;
function send_async(url, cb)
{
        if (async) delete async;
        async = new XMLHttpRequest();

        if (!async) return;
        async.onreadystatechange = cb;
        async.open("GET", url, true);
        async.send(null);
}

function standard_audio_cb()
{
        if (async.readyState == 4 && async.status == 200)
        {
                setTimeout("refresh_player()", 1000);
        }
}

var player_id = -1;
function player_play(id)
{
        send_async("cmd.php?id=" + id + "&cmd=play", standard_audio_cb);
        player_id = id;
}
function player_next(id)
{
        send_async("cmd.php?id=" + id + "&cmd=next", standard_audio_cb);
        player_id = id;
}
function player_stop(id)
{
        send_async("cmd.php?id=" + id + "&cmd=stop", standard_audio_cb);
        player_id = id;
}
function player_previous(id)
{
        send_async("cmd.php?id=" + id + "&cmd=previous", standard_audio_cb);
        player_id = id;
}


function null_cb()
{
        if (async.readyState == 4 && async.status == 200)
        {
        }
}
function output_action(id, value)
{
        send_async("../action.php?action=output&id="+id+"&value="+value, null_cb);
        setTimeout(function () { update_img(id) }, 1500);
}
function input_action(id, value)
{
        send_async("../action.php?action=input&id="+id+"&value="+value, null_cb);
        setTimeout(function () { update_img(id) }, 1500);
}

function update_img(id)
{
        send_async("../action.php?action=update&id="+id, update_img_cb);
}
function update_img_cb()
{
        if (async.readyState == 4 && async.status == 200)
        {
                var data = async.responseText;

                var val = data.split(' ');
                var enabled = false;
                if (val[1] == 'true' || val[1] > 0)
                        enabled = true;

                var image = document.getElementById("img_" + val[0]);
                if (image == null)
                {
                        return;
                }

                if (enabled)
                {
                        var t = image.src;
                        image.src = t.replace(/off.png/, "on.png");
                }
                else
                {
                        var t = image.src;
                        image.src = t.replace(/on.png/, "off.png");
                }
        }
}

function int_value_increase(id)
{
        //get current value
        var value = parseInt(document.getElementById("value_" + id).innerHTML);

        value = value + 1;

        send_async("../action.php?action=output&id="+id+"&value="+value, null_cb);

        setTimeout(function () { update_int_value(id) }, 1500);
}

function int_value_decrease(id)
{
        //get current value
        var value = parseInt(document.getElementById("value_" + id).innerHTML);

        value = value - 1;

        send_async("../action.php?action=output&id="+id+"&value="+value, null_cb);

        setTimeout(function () { update_int_value(id) }, 1500);
}

function update_int_value(id)
{
        send_async("../action.php?action=update&id="+id, update_int_value_cb);
}
function update_int_value_cb()
{
        if (async.readyState == 4 && async.status == 200)
        {
                var data = async.responseText;
                var val = data.split(' ');
                var value = val[1];

                var span = document.getElementById("value_" + val[0]);
                if (span == null)
                {
                        return;
                }

                span.innerHTML = value;
        }
}

function string_value_edit(id)
{
        var txt = prompt('Entrez le nouveau texte','');
        if (txt == '' || txt == null)
        {
                alert('Vous devez specifiez un texte !');
                return;
        }

        send_async("../action.php?action=output&id="+id+"&value="+txt, null_cb);

        setTimeout(function () { update_string_value(id) }, 1500);
}

function update_string_value(id)
{
        send_async("../action.php?action=update&id="+id, update_string_value_cb);
}
function update_string_value_cb()
{
        if (async.readyState == 4 && async.status == 200)
        {
                var data = async.responseText;
                var val = data.split(' ');
                var value = val[1];

                var span = document.getElementById("value_" + val[0]);
                if (span == null)
                {
                        return;
                }

                span.innerHTML = unescape(value);
        }
}

function analog_value_edit(id)
{
        var txt = prompt('Entrez le nouveau texte','');
        if (txt == '' || txt == null || isNaN(txt))
        {
                alert('Vous devez specifiez une valeur !');
                return;
        }

        send_async("../action.php?action=output&id="+id+"&value="+txt, null_cb);

        setTimeout(function () { update_string_value(id) }, 1500);
}

function refresh_player()
{
        send_async("cmd.php?id=" + player_id + "&cmd=songinfo", update_audio_info_cb);

        /* reload cover */

        // The dummy above enforces a bypass of the browser image cache
        // Here we load the image
        var url = "music.php?player_id=" + player_id;
        date = new Date();
        url += '&dummy=' + date.getTime().toString(10);
        delete date;

        if (img_loader != null) delete img_loader;
        img_loader = new Image();

        //loading done callback
        img_loader.onload = function()
                {
                        var o = document.getElementById("player_single_" + player_id);
                        if (o == null) return;
                        o.src = img_loader.src;
                }

        img_loader.src = url;
}
function update_audio_info_cb()
{
        if (async.readyState == 4 && async.status == 200)
        {
                var o = document.getElementById("song_infos");
                if (o == null) return;

                o.innerHTML = async.responseText;
        }
}
