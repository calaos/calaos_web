/*
        Main js
        Copyright 2007 Calaos
*/

var status_timeout = null;
var loader;
dojo.addOnLoad(init);
function init()
{
        loader = new Array();

        var content = dojo.widget.byId("content");
        if (content != null)
        {
                content.onDownloadStart = onLoadingStart;
                content.onDownloadEnd = onLoadingEnd;
        }
}

function ShowLoading(val)
{
        if (val)
                dojo.byId("loading").style.display = 'block';
        else
                dojo.byId("loading").style.display = 'none';
}

/* Show the mutlimedia menu */
function MenuMultimedia()
{
        var docPane = dojo.widget.byId("content");
        docPane.setUrl("menu_multi.php");
}

function MenuHome()
{
        var docPane = dojo.widget.byId("content");
        docPane.setUrl("menu_home.php");
}

function MenuConfig()
{
        var docPane = dojo.widget.byId("content");
        docPane.setUrl("menu_config.php");
}

function ShowCameras()
{
        var docPane = dojo.widget.byId("content");
        docPane.setUrl("camera.php");
}

function ShowIR()
{
        var docPane = dojo.widget.byId("content");
        docPane.setUrl("infrared.php");
}

function ShowIO()
{
        var docPane = dojo.widget.byId("content");
        docPane.setUrl("config_room.php");
}

function ShowRules()
{
        var docPane = dojo.widget.byId("content");
        docPane.setUrl("config_rules.php");
}

function SaveConfig()
{
        var docPane = dojo.widget.byId("content");
        docPane.setUrl("save_config.php");
}

function RebootMenu()
{
        var docPane = dojo.widget.byId("content");
        docPane.setUrl("reboot_menu.php");
}

function NetworkConfig()
{
        var docPane = dojo.widget.byId("content");
        docPane.setUrl("network_config.php");
}

function SSHConfig()
{
        var docPane = dojo.widget.byId("content");
        docPane.setUrl("ssh_config.php");
}

function UserConfig()
{
        var docPane = dojo.widget.byId("content");
        docPane.setUrl("user_config.php");
}

function FWUpdate()
{
        var docPane = dojo.widget.byId("content");
        docPane.setUrl("firmware_update.php");
}

function ShowSyslog()
{
        var docPane = dojo.widget.byId("content");
        docPane.setUrl("show_syslog.php");
}

function Reboot(_type)
{
        dojo.io.bind({
               url: "action.php?action=reboot&value=" + _type,
               load: function(type, data, evt){ dialog.hide(); EditRule(_type, id, nb); }
        });

        ShowStatus("Red&eacute;marrage effectu&eacute;...", true);
}

function Save(w)
{
        if (w == "standard")
        {
                dojo.io.bind({
                       url: "action.php?action=save_standard"
                   });

                ShowStatus("Sauvegarde effectu&eacute;...", true);
        }
        else if (w == "default")
        {
                dojo.io.bind({
                       url: "action.php?action=save_default"
                   });

                ShowStatus("Sauvegarde par d&eacute;faut effectu&eacute;...", true);
        }
}

function SSHDeleteKey()
{
        dojo.io.bind({
                url: "action.php?action=delete_ssh",
                load: function(type, data, evt) { SSHConfig(); }
        });
        
        ShowLoading(true);
}

function UpdateSSHConfig()
{
        var v = "false";
        if (dojo.byId("ssh_enable").checked)
                v = "true";
        
        dojo.io.bind({
                url: "action.php?action=ssh&value="+v,
                load: function(type, data, evt) { SSHConfig(); }
        });
        
        ShowLoading(true);
}

function AddRoom()
{
        dojo.io.bind({
                       url: "action.php?action=add_room&room_type=misc&room_id=NOUVELLE_PIECE" ,
                       load: function(type, data, evt){ ShowIO(); }
                    });
}

function room_edit_cb(newval, oldval, id)
{
        if (newval == oldval) return;
        dojo.debug("Saving "+newval+" id:"+id);
        var opt = id.split(",");
        dojo.io.bind({
                       url: "action.php?action=edit_room&opt="+opt[0]+"&value="+newval+"&room_type="+opt[1]+"&room_id="+opt[2]
                    });
}

function ioparam_edit_cb(newval, oldval, id)
{
        if (newval == oldval) return;
        dojo.debug("Saving "+newval+" id:"+id);
        var opt = id.split(",");
        if (opt[0] != "input" && opt[0] != "output") return;
        dojo.io.bind({
                       url: "action.php?action="+opt[0]+"_param&opt="+opt[2]+"&value="+newval+"&id="+opt[1]
                    });
}

function EditRoomIO(room_type, room_id, room_name)
{
        var docPane = dojo.widget.byId("content");
        docPane.setUrl("edit_room.php?room_id="+room_id+"&room_type="+room_type+"&room_name="+room_name);
}

function EditInput(id, room_type, room_id, room_name)
{
        var docPane = dojo.widget.byId("content");
        docPane.setUrl("io_editor.php?id="+id+"&type=input&room_id="+room_id+"&room_type="+room_type+"&room_name="+room_name);
}

function EditOutput(id, room_type, room_id, room_name)
{
        var docPane = dojo.widget.byId("content");
        docPane.setUrl("io_editor.php?id="+id+"&type=output&room_id="+room_id+"&room_type="+room_type+"&room_name="+room_name);
}

function delete_room(room_type, room_id)
{
        if (!confirm("Voulez vous supprimer cette piece et son contenu?")) return;

        dojo.io.bind({
                       url: "action.php?action=delete_room&room_id="+room_id+"&room_type="+room_type,
                       load: function(type, data, evt){ ShowIO(); }
                    });
}

function delete_ioparam(io_type, id, param, room_type, room_id, room_name)
{
        if (!confirm("Voulez vous supprimer ce parametre?")) return;

        dojo.io.bind({
                       url: "action.php?action="+io_type+"_delparam&opt="+param+"&id="+id,
                       load: function(type, data, evt){ EditInput(id, room_type, room_id, room_name); }
                    });
}

function AddIOParam(io_type, id, room_type, room_id, room_name)
{
        var name = prompt('Entrez le nom du nouveau parametre :','');
        if (name == '' || name == null)
        {
                alert('Vous devez specifiez un nom !');
                return;
        }

        dojo.debug(io_type+","+id+","+room_type+","+room_id+","+room_name);
        dojo.io.bind({
                       url: "action.php?action="+io_type+"_param&opt="+name+"&id="+id+"&value="+name,
                       load: function(type, data, evt)
                             {
                                        if (io_type == "input")
                                                EditInput(id, room_type, room_id, room_name);
                                        else
                                                EditOutput(id, room_type, room_id, room_name);
                             }
                    });
}

function AddHour(day, id, room_type, room_id, room_name)
{
        dojo.io.bind({
                       url: "action.php?action=add_plage&opt="+day+"&param1=00:00:00&param2=01:00:00&id="+id,
                       load: function(type, data, evt){ EditInput(id, room_type, room_id, room_name); }
                    });
        dojo.debug("action.php?action=add_plage&opt="+day+"&param1=00:00:00&param2=01:00:00&id="+id);
}

function delete_plage(day, id, iid, room_type, room_id, room_name)
{
        if (!confirm("Voulez vous supprimer cette plage?")) return;

        dojo.io.bind({
                       url: "action.php?action=del_plage&opt="+day+"&id="+iid+"&value="+id,
                       load: function(type, data, evt){ EditInput(iid, room_type, room_id, room_name); }
                    });
}

function plage_edit_hour1_cb(newval, oldval, id)
{
        if (newval == oldval) return;
        dojo.debug("Saving "+newval+" id:"+id);
        var opt = id.split(",");
        dojo.io.bind({
                       url: "action.php?action=set_plage&opt="+opt[0]+"&value="+opt[1]+"&id="+opt[3]+"&param1="+newval+"&param2="+opt[2],
                       load: function(type, data, evt){ EditInput(opt[3], opt[4], opt[5], opt[6]); }
                    });
}

function plage_edit_hour2_cb(newval, oldval, id)
{
        if (newval == oldval) return;
        dojo.debug("Saving "+newval+" id:"+id);
        var opt = id.split(",");
        dojo.io.bind({
                       url: "action.php?action=set_plage&opt="+opt[0]+"&value="+opt[1]+"&id="+opt[3]+"&param2="+newval+"&param1="+opt[2],
                       load: function(type, data, evt){ EditInput(opt[3], opt[4], opt[5], opt[6]); }
                    });
}

function delete_input(id, room_type, room_id, room_name)
{
        if (!confirm("Voulez vous supprimer cette entree?\n\nAttention, toutes les regles utilisant cette entree\nseront egalement supprime.")) return;

        dojo.io.bind({
                       url: "action.php?action=delete_input&value="+id,
                       load: function(type, data, evt){ EditRoomIO(room_type, room_id, room_name); }
                    });
}

function delete_output(id, room_type, room_id, room_name)
{
        if (!confirm("Voulez vous supprimer cette sortie?\n\nAttention, toutes les regles utilisant cette sortie\nseront egalement supprime.")) return;

        dojo.io.bind({
                       url: "action.php?action=delete_output&value="+id,
                       load: function(type, data, evt){ EditRoomIO(room_type, room_id, room_name); }
                    });
}

function ShowRoom(room, count)
{
        if (count < 1)
        {
                dojo.debug("WARNING: ShowRoom("+room+","+count+"): count is out of bound !");
                return;
        }

        //if count is greater than 1, we must ask user
        //to choose the room he wants
        if (count > 1)
        {
                dojo.debug("ShowRoom(): " + room + ", count: " + count);

                var dialog = dojo.widget.byId("dialog");
                if (dialog == null)
                {
                        var div = document.createElement("div");
                        document.body.appendChild(div);
                        dialog = dojo.widget.createWidget("dojo:Dialog",
                                {
                                   toggle:"fade",
                                   toggleDuration:250,
                                   bgColor:"#afc3de",
                                   bgOpacity:"0.6",
                                   closeOnBackgroundClick:true,
                                   id:"dialog"
                                }, div);
                }
                dialog.setUrl("dialog_room.php?room_type="+room);
                dialog.show();
        }
        else
        {
                var docPane = dojo.widget.byId("content");
                docPane.setUrl("room.php?room_id=0&room_type="+room);
        }
}

function ShowRoomMultiple(room, num)
{
        var docPane = dojo.widget.byId("content");
        docPane.setUrl("room.php?room_id="+num+"&room_type="+room);
        dojo.widget.byId("dialog").hide();
}

function OpenCamera(camid, ctitle, ptz)
{
        var camera = dojo.widget.byId("camid_" + camid);

        if (camera == null)
        {
                //have to create a div element to contain the FloatingPane
                var div = document.createElement("div");
                div.style.position = "absolute";
                div.style.width = "520px";
                if (ptz)
                        div.style.height = "480px";
                else
                        div.style.height = "450px";
                div.style.top = "10px";
                div.style.left = "10px";
                div.innerHTML = "Loading...";
                document.body.appendChild(div);

                var camera = dojo.widget.createWidget("FloatingPane",
                        { title: ctitle,
                          hasShadow: true,
                          iconSrc: 'img/voir.gif',
                          resizable: false,
                          displayCloseAction: true,
                          id: "camid_" + camid,
                          href: "camera_frame.php?cam_id="+camid+"&ptz="+ptz,
                          cacheContent: false,
                          onLoad: function() { DisplayFrame(camid); }
                          }, div);
                camera.titleBarIcon.style.width = "16px";
                camera.titleBarIcon.style.height = "16px";

                dojo.debug("camera.create()");
        }
        else
        {
                dojo.debug("camera.show()");
                camera.show();
        }
}

function DisplayFrame(camid)
{
        dojo.debug("DisplayFrame(): Called with arg: " + camid);

        var camera = dojo.widget.byId("camid_" + camid);
        if (camera == null)
        {
                dojo.debug("DisplayFrame("+camid+"): Stop timer");
                document.getElementById("camid_loading_"+camid).style.display = "none";
                return ;
        }

        document.getElementById("camid_loading_"+camid).style.display = "block";

        var url = "camera.php?camera_id=" + camid;
        dojo.debug("url:" + url);
//         url += "&resolution=480x360&compression=50&clock=1&date=1";

        // The dummy above enforces a bypass of the browser image cache
        // Here we load the image
        date = new Date();
        url += '&dummy=' + date.getTime().toString(10);
        delete date;

        if (loader[camid] != null) delete loader[camid];
        loader[camid] = new Image();
        loader[camid].src = url;

        //loading finished callback
        loader[camid].onload = function()
        {
                if (dojo.widget.byId("camid_" + camid) == null)
                        return;

                //update frame
                document.getElementById("ipcam_frame_"+camid).src = loader[camid].src;
                document.getElementById("camid_loading_"+camid).style.display = "none";
                setTimeout("DisplayFrame(\"" + camid + "\")", document.getElementById("spinner_" + camid).value);
        }
}

function CameraRecall(camid)
{
        var pos = dojo.widget.byId("control_" + camid).comboBoxValue.value;
        dojo.io.bind({
                       url: "action.php?action=camera_recall&id="+camid+"&value="+pos
                    });
        dojo.debug("action.php?action=camera_recall&id="+camid+"&value="+pos);
}

function CameraSave(camid)
{
        var pos = dojo.widget.byId("control_" + camid).comboBoxValue.value;
        dojo.io.bind({
                       url: "action.php?action=camera_save&id="+camid+"&value="+pos
                    });
}

function CameraMove(camid, dir)
{
        dojo.io.bind({
                       url: "action.php?action=camera_move&id="+camid+"&value="+dir
                    });
}

function output_action(id, value)
{
        dojo.debug("output_action("+id+","+value+")");

        dojo.io.bind({
                       url: "action.php?action=output&id="+id+"&value="+value
                    });

        setTimeout(function () { update_img(id) }, 1500);
}

function input_action(id, value)
{
        dojo.debug("input_action("+id+","+value+")");

        dojo.io.bind({
                       url: "action.php?action=input&id="+id+"&value="+value
                    });
}

function update_img(id)
{
        dojo.io.bind({
                       url: "action.php?action=update&id="+id,
                       load: update_img_cb
                    });
}
function update_img_cb(type, data, evt)
{
        dojo.debug("update_img_cb("+type+","+data+")");

        if (type == 'error') return;
        var val = data.split(' ');
        var enabled = false;
        if (val[1] == 'true' || val[1] > 0)
                enabled = true;

        var image = dojo.byId("img_"+val[0]);
        if (image == null)
        {
                dojo.debug("image is null !");
                return;
        }

        if (enabled)
        {
                var t = image.src;
                image.src = t.replace(/off.png/, "on.png");
                dojo.debug("Image on !");
        }
        else
        {
                var t = image.src;
                image.src = t.replace(/on.png/, "off.png");
                dojo.debug("Image off !");
        }
}

function int_value_increase(id)
{
        dojo.debug("int_value_increase("+id+")");

        //get current value
        var value = parseInt(dojo.byId("value_" + id).innerHTML);

        value = value + 1;

        dojo.io.bind({
                       url: "action.php?action=output&id="+id+"&value="+value
                    });

        setTimeout(function () { update_int_value(id) }, 1500);
}

function int_value_decrease(id)
{
        dojo.debug("int_value_decrease("+id+")");

        //get current value
        var value = parseInt(dojo.byId("value_" + id).innerHTML);

        value = value - 1;

        dojo.io.bind({
                       url: "action.php?action=output&id="+id+"&value="+value
                    });

        setTimeout(function () { update_int_value(id) }, 1500);
}

function update_int_value(id)
{
        dojo.io.bind({
                       url: "action.php?action=update&id="+id,
                       load: update_int_value_cb
                    });
}
function update_int_value_cb(type, data, evt)
{
        dojo.debug("update_img_cb("+type+","+data+")");

        if (type == 'error') return;
        var val = data.split(' ');
        var value = val[1];

        var span = dojo.byId("value_" + val[0]);
        if (span == null)
        {
                dojo.debug("span is null !");
                return;
        }

        span.innerHTML = value;
}

function string_value_edit(id)
{
        dojo.debug("string_value_edit("+id+")");

        var txt = prompt('Entrez le nouveau texte','');
        if (txt == '' || txt == null)
        {
                alert('Vous devez specifiez un texte !');
                return;
        }

        dojo.io.bind({
                       url: "action.php?action=output&id="+id+"&value="+txt
                    });

        setTimeout(function () { update_string_value(id) }, 1500);
}

function update_string_value(id)
{
        dojo.io.bind({
                       url: "action.php?action=update&id="+id,
                       load: update_string_value_cb
                    });
}
function update_string_value_cb(type, data, evt)
{
        dojo.debug("update_string_value_cb("+type+","+data+")");

        if (type == 'error') return;
        var val = data.split(' ');
        var value = val[1];

        var span = dojo.byId("value_" + val[0]);
        if (span == null)
        {
                dojo.debug("span is null !");
                return;
        }

        span.innerHTML = decodeURIComponent(value);
}

function analog_value_edit(id)
{
        dojo.debug("analog_value_edit("+id+")");

        var txt = prompt('Entrez la nouvelle valeur','');
        if (txt == '' || txt == null || isNaN(txt))
        {
                alert('Vous devez specifiez une valeur !');
                return;
        }

        dojo.io.bind({
                       url: "action.php?action=output&id="+id+"&value="+txt
                    });

        setTimeout(function () { update_string_value(id) }, 1500);
}

function onLoadingStart(e)
{
        ShowLoading(true);
        e.returnValue = false;
}
function onLoadingEnd(url, data)
{
        data = this.splitAndFixPaths(data, url);
        this.setContent(data);
        ShowLoading(false);

        var log_area = dojo.byId("log1");
        if (log_area != null)
                log_area.scrollTop = log_area.scrollHeight;
}

function _cam_show(id)
{
        if (id == "cam_gadspot")
        {
                dojo.byId("cam_gadspot").style.display = 'block';
                dojo.byId("cam_axis").style.display = 'none';
                dojo.byId("cam_planet").style.display = 'none';
                dojo.byId("cam_mjpeg").style.display = 'none';
        }
        else if (id == "cam_axis")
        {
                dojo.byId("cam_gadspot").style.display = 'none';
                dojo.byId("cam_axis").style.display = 'block';
                dojo.byId("cam_planet").style.display = 'none';
                dojo.byId("cam_mjpeg").style.display = 'none';
        }
        else if (id == "cam_planet")
        {
                dojo.byId("cam_gadspot").style.display = 'none';
                dojo.byId("cam_axis").style.display = 'none';
                dojo.byId("cam_planet").style.display = 'block';
                dojo.byId("cam_mjpeg").style.display = 'none';
        }
        else if (id == "cam_mjpeg")
        {
                dojo.byId("cam_gadspot").style.display = 'none';
                dojo.byId("cam_axis").style.display = 'none';
                dojo.byId("cam_planet").style.display = 'none';
                dojo.byId("cam_mjpeg").style.display = 'block';
        }
}

function CreateIO(room_type, room_id, room_name)
{
        var dialog = dojo.widget.byId("dialog");
        if (dialog == null)
        {
                var div = document.createElement("div");
                document.body.appendChild(div);
                dialog = dojo.widget.createWidget("dojo:Dialog",
                        {
                           toggle:"fade",
                           toggleDuration:250,
                           bgColor:"#afc3de",
                           bgOpacity:"0.6",
                           closeOnBackgroundClick:true,
                           cacheContent: false,
                           id:"dialog",
                           executeScripts:"true"
                        }, div);
        }
        dialog.setUrl("new_io.php?room_type="+room_type+"&room_id="+room_id+"&room_name="+room_name);
        dialog.show();
}

function CreateState(etape, radio, room_type, room_id, room_name)
{
        var url = "";
        var dialog = dojo.widget.byId("dialog");
        var sel = 0;
        for (var i = 0;i < document.create_form.radio.length;i++)
        {
                if (document.create_form.radio[i].checked)
                        sel = i;
        }
        if (document.create_form.radio.length > 0)
                r = document.create_form.radio[sel].value;
        else
                r = document.create_form.radio.value;

        if (etape == 2)
        {
                url = "new_io.php?room_type="+room_type+"&room_id="+room_id+"&room_name="+room_name+"&radio="+r;
                dojo.widget.byId("dialog").setUrl(url);
        }
        else if (etape == 3)
        {
                if (r == "WIDigital" || r == "WIDigitalBP" || r == "WIDigitalTriple" ||
                    r == "scenario" || r == "WITemp" || r == "WODigital" || r == "WODigitalLight" ||
                    r == "WOVolet" || r == "WOVoletSmart" || r == "WONeon"
		   || r == "OWTemp")
                {
                        url = "action.php?action=create_wago&room_type="+room_type+"&room_id="+room_id;
                        url += "&param1=" + encodeURIComponent("name:"+document.create_form.name.value);
                        if (r == "WODigitalLight")
                                url += "&value=type%3AWODigital&param3=gtype%3Alight";
                        else
                                url += "&value="+ encodeURIComponent("type:" + r);

                        if (r != "scenario" && r != "WOVolet" && r != "WOVoletSmart")
                        {
                                url += "&param2=" + encodeURIComponent("var:"+document.create_form._var.value);
                        }

                        if (r == "WOVolet" || r == "WOVoletSmart")
                        {
                                url += "&param3=" + encodeURIComponent("var_up:"+document.create_form.var_up.value);
                                url += "&param4=" + encodeURIComponent("var_down:"+document.create_form.var_down.value);

                                if (r == "WOVolet")
                                {
                                        url += "&param5=" + encodeURIComponent("time:"+document.create_form.time.value);
                                }
                                else
                                {
                                        url += "&param5=" + encodeURIComponent("time_up:"+document.create_form.time_up.value);
                                        url += "&param6=" + encodeURIComponent("time_down:"+document.create_form.time_down.value);
                                }

                                if (r == "WOVoletSmart" && document.create_form.var_save.value != "")
                                {
                                        url += "&param7=" + encodeURIComponent("var_save:"+document.create_form.var_save.value);
                                }

                                if (r == "WOVoletSmart" && document.create_form.impulse_time.value != "")
                                {
                                        url += "&param8=" + encodeURIComponent("impulse_time:"+document.create_form.impulse_time.value);
                                }
                        }
                        else if (r == "WONeon")
                        {
                                url += "&param3=" + encodeURIComponent("var_relay:"+document.create_form.var_relay.value);
                        }
                }
                else if (r == "gadspot" || r == "axis" || r == "planet" || r == "standard_mjpeg")
                {
                        var p1, p2, p3, p4, p5, p6;

                        if (r == "gadspot")
                        {
                                p1 = document.create_form.name_gadspot.value;
                                p2 = document.create_form.host_gadspot.value;
                                p3 = document.create_form.port_gadspot.value;
                        }
                        else if (r == "axis")
                        {
                                p1 = document.create_form.name_axis.value;
                                p2 = document.create_form.host_axis.value;
                                p3 = document.create_form.port_axis.value;
                                p4 = document.create_form.model_axis.value;
                        }
                        else if (r == "planet")
                        {
                                p1 = document.create_form.name_planet.value;
                                p2 = document.create_form.host_planet.value;
                                p3 = document.create_form.port_planet.value;
                                p4 = document.create_form.model_planet.value;
                        }
                        else if (r == "standard_mjpeg")
                        {
                                p1 = document.create_form.name_mjpeg.value;
                                p2 = document.create_form.host_mjpeg.value;
                                p3 = document.create_form.port_mjpeg.value;
                                p4 = document.create_form.url_jpeg.value;
                                p5 = document.create_form.url_mjpeg.value;
                                p6 = document.create_form.url_mpeg.value;
                        }

                        url = "action.php?action=create_camera&room_type="+room_type+"&room_id="+room_id;
                        url += "&value=" + encodeURIComponent("type:"+r);
                        url += "&param1=" + encodeURIComponent("name:"+p1);
                        url += "&param2=" + encodeURIComponent("host:"+p2);
                        url += "&param3=" + encodeURIComponent("port:"+p3);
                        if (r == "axis" || r == "planet")
                                url += "&param4=" + encodeURIComponent("model:"+p4);
                        else if (r == "standard_mjpeg")
                        {
                                url += "&param4=" + encodeURIComponent("url_jpeg:"+p4);
                                url += "&param5=" + encodeURIComponent("url_mjpeg:"+p5);
                                url += "&param6=" + encodeURIComponent("url_mpeg:"+p6);
                        }
                }
                else if (r == "irtrans")
                {
                        url = "action.php?action=create_ir&room_type="+room_type+"&room_id="+room_id;
                        url += "&value=" + encodeURIComponent("type:"+r);
                        url += "&param1=" + encodeURIComponent("name:"+document.create_form.name.value);
                        url += "&param2=" + encodeURIComponent("host:"+document.create_form.host.value);
                        url += "&param3=" + encodeURIComponent("port:"+document.create_form.port.value);
                }
                else if (r == "InternalInt" || r == "InternalBool" || r == "InternalString")
                {
                        url = "action.php?action=create_internal&room_type="+room_type+"&room_id="+room_id;
                        url += "&value=" + encodeURIComponent("type:"+r);
                        url += "&param1=" + encodeURIComponent("name:"+document.create_form.name.value);
                }
                else if (r == "X10Output")
                {
                        url = "action.php?action=create_x10&room_type="+room_type+"&room_id="+room_id;
                        url += "&value=" + encodeURIComponent("type:"+r);
                        url += "&param1=" + encodeURIComponent("name:"+document.create_form.name.value);
                        url += "&param2=" + encodeURIComponent("code:"+document.create_form.code.value);
                }
                else if (r == "slim")
                {
                        url = "action.php?action=create_audio&room_type="+room_type+"&room_id="+room_id;
                        url += "&value=" + encodeURIComponent("type:"+r);
                        url += "&param1=" + encodeURIComponent("name:"+document.create_form.name.value);
                        url += "&param2=" + encodeURIComponent("host:"+document.create_form.host.value);
                        url += "&param3=" + encodeURIComponent("port:"+document.create_form.port.value);
                        url += "&param4=" + encodeURIComponent("id:"+document.create_form.mac.value);
                }
                else if (r == "InputTime" || r == "InputTimeDate" || r == "InputTimer" )
                {
                        url = "action.php?action=create_time&room_type="+room_type+"&room_id="+room_id;
                        url += "&value=" + encodeURIComponent("type:" + r);
                        url += "&param1=" + encodeURIComponent("name:"+document.create_form.name.value);
                        url += "&param2=" + encodeURIComponent("hour:"+document.create_form.hour.value);
                        url += "&param3=" + encodeURIComponent("min:"+document.create_form.min.value);
                        url += "&param4=" + encodeURIComponent("sec:"+document.create_form.sec.value);
                        if (r == "InputTimer")
                                url += "&param5=" + encodeURIComponent("msec:"+document.create_form.msec.value);

                        if (r == "InputTimeDate")
                        {
                                url += "&param5=" + encodeURIComponent("year:"+document.create_form.year.value);
                                url += "&param6=" + encodeURIComponent("month:"+document.create_form.month.value);
                                url += "&param7=" + encodeURIComponent("day:"+document.create_form.day.value);
                        }
                }
                else if (r == "InPlageHoraire")
                {
                        url = "action.php?action=create_plage&room_type="+room_type+"&room_id="+room_id;
                        url += "&value=" + encodeURIComponent("type:" + r);
                        url += "&param1=" + encodeURIComponent("name:"+document.create_form.name.value);
                }
                else if (r == "WODali" || r == "WODaliRVB")
                {
                        url = "action.php?action=create_wago&room_type="+room_type+"&room_id="+room_id;
                        url += "&param1=" + encodeURIComponent("name:"+document.create_form.name.value);
                        url += "&value="+ encodeURIComponent("type:" + r);

                        if (r == "WODali")
                        {
                                url += "&param2=" + encodeURIComponent("line:"+document.create_form.line.value);
                                if (document.create_form.group.checked)
                                        url += "&param3=" + encodeURIComponent("group:1");
                                else
                                        url += "&param3=" + encodeURIComponent("group:0");
                                url += "&param4=" + encodeURIComponent("address:"+document.create_form.address.value);
                                url += "&param5=" + encodeURIComponent("fade_time:"+document.create_form.fade_time.value);
                        }
                        else
                        {
                                //red
                                url += "&param2=" + encodeURIComponent("rline:"+document.create_form.rline.value);
                                if (document.create_form.rgroup.checked)
                                        url += "&param3=" + encodeURIComponent("rgroup:1");
                                else
                                        url += "&param3=" + encodeURIComponent("rgroup:0");
                                url += "&param4=" + encodeURIComponent("raddress:"+document.create_form.raddress.value);
                                url += "&param5=" + encodeURIComponent("rfade_time:"+document.create_form.rfade_time.value);

                                //green
                                url += "&param6=" + encodeURIComponent("gline:"+document.create_form.gline.value);
                                if (document.create_form.ggroup.checked)
                                        url += "&param7=" + encodeURIComponent("ggroup:1");
                                else
                                        url += "&param7=" + encodeURIComponent("ggroup:0");
                                url += "&param8=" + encodeURIComponent("gaddress:"+document.create_form.gaddress.value);
                                url += "&param9=" + encodeURIComponent("gfade_time:"+document.create_form.gfade_time.value);

                                //blue
                                url += "&param10=" + encodeURIComponent("bline:"+document.create_form.bline.value);
                                if (document.create_form.bgroup.checked)
                                        url += "&param11=" + encodeURIComponent("bgroup:1");
                                else
                                        url += "&param11=" + encodeURIComponent("bgroup:0");
                                url += "&param12=" + encodeURIComponent("baddress:"+document.create_form.baddress.value);
                                url += "&param13=" + encodeURIComponent("bfade_time:"+document.create_form.bfade_time.value);
                        }
                }

                dojo.debug(url);

                dojo.io.bind({
                       url: url,
                       load: function(type, data, evt){ dialog.hide(); EditRoomIO(room_type, room_id, room_name); }
                    });
        }
}

function ListRule(type, nb)
{
        var docPane = dojo.widget.byId("content");
        docPane.setUrl("config_rules_type.php?type="+type+"&nb="+nb);
}

function EditRule(type, id, nb)
{
        var docPane = dojo.widget.byId("content");
        docPane.setUrl("edit_rule.php?type="+type+"&id="+id+"&nb="+nb);
}

function delete_rule(_type, id, nb)
{
        if (!confirm("Voulez vous supprimer cette regle?")) return;

        dojo.io.bind({
               url: "action.php?action=delete_rule&param1="+_type+"&id="+id,
               load: function(type, data, evt){ ShowRules(); }
        });
}

function delete_condition(_type, id, cid, nb)
{
        if (!confirm("Voulez vous supprimer cette condition?")) return;

        dojo.io.bind({
               url: "action.php?action=delete_condition&param1="+_type+"&id="+id+"&param2="+cid,
               load: function(type, data, evt){ EditRule(_type, id, nb); }
        });
}

function delete_action(_type, id, aid, nb)
{
        if (!confirm("Voulez vous supprimer cette action?")) return;

        dojo.io.bind({
               url: "action.php?action=delete_action&param1="+_type+"&id="+id+"&param2="+aid,
               load: function(type, data, evt){ EditRule(_type, id, nb); }
        });
}

function EditCondition(_type, id, cid, nb)
{
        var dialog = dojo.widget.byId("dialog");
        if (dialog == null)
        {
                var div = document.createElement("div");
                document.body.appendChild(div);
                dialog = dojo.widget.createWidget("dojo:Dialog",
                        {
                           toggle:"fade",
                           toggleDuration:250,
                           bgColor:"#afc3de",
                           bgOpacity:"0.6",
                           closeOnBackgroundClick:true,
                           cacheContent: false,
                           id:"dialog"
                        }, div);
        }
        dialog.setUrl("edit_condition.php?type="+_type+"&id="+id+"&cid="+cid+"&nb="+nb);
        dialog.show();
}

function EditAction(_type, id, aid, nb)
{
        var dialog = dojo.widget.byId("dialog");
        if (dialog == null)
        {
                var div = document.createElement("div");
                document.body.appendChild(div);
                dialog = dojo.widget.createWidget("dojo:Dialog",
                        {
                           toggle:"fade",
                           toggleDuration:250,
                           bgColor:"#afc3de",
                           bgOpacity:"0.6",
                           closeOnBackgroundClick:true,
                           cacheContent: false,
                           id:"dialog"
                        }, div);
        }
        dialog.setUrl("edit_action.php?type="+_type+"&id="+id+"&aid="+aid+"&nb="+nb);
        dialog.show();
}

function EditCancel()
{
        var dialog = dojo.widget.byId("dialog");
        if (dialog != null) dialog.hide();
}

function EditConditionValid(_type, id, cid, nb)
{
        var dialog = dojo.widget.byId("dialog");
        var check = dojo.widget.byId("check_var_val");
        var editid = dojo.byId("edit_condition_id");
        var editoper = dojo.byId("edit_condition_oper");
        var editvarval = dojo.byId("edit_condition_val");
        var editval = dojo.byId("condition_value");

        if (check.checked)
                var var_val = encodeURIComponent("var_val:" + editvarval.options[editvarval.selectedIndex].value);
        else
                var val = encodeURIComponent("val:" + editval.value);
        var iid = encodeURIComponent("id:" + editid.options[editid.selectedIndex].value);
        var oper = encodeURIComponent("oper:" + editoper.options[editoper.selectedIndex].value);

        var url;
        if (check.checked)
                url = "action.php?action=set_condition&opt="+_type+"&id="+id+"&value="+cid+"&param1="+iid+"&param2="+oper+"&param3="+var_val;
        else
                url = "action.php?action=set_condition&opt="+_type+"&id="+id+"&value="+cid+"&param1="+iid+"&param2="+oper+"&param3="+val+"&param4=var_val%3A";

        dojo.io.bind({
               url: url,
               load: function(type, data, evt){ dialog.hide(); EditRule(_type, id, nb); }
        });
}

function EditActionValid(_type, id, cid, nb)
{
        var dialog = dojo.widget.byId("dialog");
        var editid = dojo.byId("edit_action_id");
        var editval = dojo.byId("action_value");
        var check = dojo.widget.byId("acheck_var_val");
        var editvarval = dojo.byId("edit_action_val");

        if (check.checked)
                var var_val = encodeURIComponent("var_val:" + editvarval.options[editvarval.selectedIndex].value);
        else
                var val = encodeURIComponent("val:" + editval.value);
        var oid = encodeURIComponent("id:" + editid.options[editid.selectedIndex].value);

        var url;
        if (check.checked)
                url = "action.php?action=set_action&opt="+_type+"&id="+id+"&value="+cid+"&param1="+oid+"&param2="+val+"&param3="+var_val;
        else
                url = "action.php?action=set_action&opt="+_type+"&id="+id+"&value="+cid+"&param1="+oid+"&param2="+val;

        dojo.debug(url);

        dojo.io.bind({
               url: url,
               load: function(type, data, evt){ dialog.hide(); EditRule(_type, id, nb); }
        });
}

function NewRule(_type)
{
        var dialog = dojo.widget.byId("dialog");
        if (dialog == null)
        {
                var div = document.createElement("div");
                document.body.appendChild(div);
                dialog = dojo.widget.createWidget("dojo:Dialog",
                        {
                           toggle:"fade",
                           toggleDuration:250,
                           bgColor:"#afc3de",
                           bgOpacity:"0.6",
                           closeOnBackgroundClick:true,
                           cacheContent: false,
                           id:"dialog"
                        }, div);
        }
        dialog.setUrl("new_rule.php?type="+_type);
        dialog.show();
}

function NewRuleValid()
{
        var dialog = dojo.widget.byId("dialog");
        var check = dojo.widget.byId("check_var_val");
        var acheck = dojo.widget.byId("acheck_var_val");
        var editid = dojo.byId("edit_condition_id");
        var editoper = dojo.byId("edit_condition_oper");
        var editvarval = dojo.byId("edit_condition_val");
        var aeditvarval = dojo.byId("edit_action_val");
        var editval = dojo.byId("condition_value");
        var editoid = dojo.byId("edit_action_id");
        var editoval = dojo.byId("action_value");
        var _type = dojo.byId("rule_type").value;
        var _name = dojo.byId("rule_name").value;

        if (check.checked)
                var var_val = encodeURIComponent("0:ivar_val:" + editvarval.options[editvarval.selectedIndex].value);
        else
                var val = encodeURIComponent("0:ival:" + editval.value);
        var iid = encodeURIComponent("0:iid:" + editid.options[editid.selectedIndex].value);
        var oper = encodeURIComponent("0:ioper:" + editoper.options[editoper.selectedIndex].value);
        if (acheck.checked)
                var ovar_val = encodeURIComponent("0:ovar_val:" + aeditvarval.options[aeditvarval.selectedIndex].value);
        else
                var oval = encodeURIComponent("0:oval:" + editoval.value);
        var oid = encodeURIComponent("0:oid:" + editoid.options[editoid.selectedIndex].value);

        var url;
        if (check.checked)
                url = "action.php?action=new_rule&opt="+_type+"&value="+_name+"&param1="+iid+"&param2="+oper+"&param3="+var_val+"&param4="+oid;
        else
                url = "action.php?action=new_rule&opt="+_type+"&value="+_name+"&param1="+iid+"&param2="+oper+"&param3="+val+"&param4="+oid;

        if (acheck.checked)
                url += "&param5="+ovar_val;
        else
                url += "&param5="+oval;

        dojo.debug(url);

        dojo.io.bind({
               url: url,
               load: function(type, data, evt){ dialog.hide(); ShowRules(); }
        });
}

function NewCondition(_type, id, nb)
{
        var dialog = dojo.widget.byId("dialog");
        if (dialog == null)
        {
                var div = document.createElement("div");
                document.body.appendChild(div);
                dialog = dojo.widget.createWidget("dojo:Dialog",
                        {
                           toggle:"fade",
                           toggleDuration:250,
                           bgColor:"#afc3de",
                           bgOpacity:"0.6",
                           closeOnBackgroundClick:true,
                           cacheContent: false,
                           id:"dialog"
                        }, div);
        }
        dialog.setUrl("new_condition.php?type="+_type+"&id="+id+"&nb="+nb);
        dialog.show();
}

function NewAction(_type, id, nb)
{
        var dialog = dojo.widget.byId("dialog");
        if (dialog == null)
        {
                var div = document.createElement("div");
                document.body.appendChild(div);
                dialog = dojo.widget.createWidget("dojo:Dialog",
                        {
                           toggle:"fade",
                           toggleDuration:250,
                           bgColor:"#afc3de",
                           bgOpacity:"0.6",
                           closeOnBackgroundClick:true,
                           cacheContent: false,
                           id:"dialog"
                        }, div);
        }
        dialog.setUrl("new_action.php?type="+_type+"&id="+id+"&nb="+nb);
        dialog.show();
}

function NewConditionValid(_type, id, nb)
{
        var dialog = dojo.widget.byId("dialog");
        var check = dojo.widget.byId("check_var_val");
        var editid = dojo.byId("edit_condition_id");
        var editoper = dojo.byId("edit_condition_oper");
        var editvarval = dojo.byId("edit_condition_val");
        var editval = dojo.byId("condition_value");

        if (check.checked)
                var var_val = encodeURIComponent("var_val:" + editvarval.options[editvarval.selectedIndex].value);
        else
                var val = encodeURIComponent("val:" + editval.value);
        var iid = encodeURIComponent("id:" + editid.options[editid.selectedIndex].value);
        var oper = encodeURIComponent("oper:" + editoper.options[editoper.selectedIndex].value);

        var url;
        if (check.checked)
                url = "action.php?action=add_condition&opt="+_type+"&id="+id+"&param1="+iid+"&param2="+oper+"&param3="+var_val;
        else
                url = "action.php?action=add_condition&opt="+_type+"&id="+id+"&param1="+iid+"&param2="+oper+"&param3="+val+"&param4=var_val%3A";

        dojo.debug(url);

        dojo.io.bind({
               url: url,
               load: function(type, data, evt){ dialog.hide(); EditRule(_type, id, nb); }
        });
}

function NewActionValid(_type, id, nb)
{
        var dialog = dojo.widget.byId("dialog");
        var editid = dojo.byId("edit_action_id");
        var editval = dojo.byId("action_value");
        var check = dojo.widget.byId("acheck_var_val");
        var editvarval = dojo.byId("edit_action_val");

        if (check.checked)
                var var_val = encodeURIComponent("var_val:" + editvarval.options[editvarval.selectedIndex].value);
        else
                var val = encodeURIComponent("val:" + editval.value);
        var oid = encodeURIComponent("id:" + editid.options[editid.selectedIndex].value);

        var url;
        if (check.checked)
                url = "action.php?action=add_action&opt="+_type+"&id="+id+"&param1="+oid+"&param2="+val+"&param3="+var_val;
        else
                url = "action.php?action=add_action&opt="+_type+"&id="+id+"&param1="+oid+"&param2="+val;

        dojo.io.bind({
               url: url,
               load: function(type, data, evt){ dialog.hide(); EditRule(_type, id, nb); }
        });
}

function ShowStatus(msg, success)
{
        var span = dojo.byId("status");
        var img = "success";
        if (!success) img = "error";
        if (msg == "Linux Everywhere !") img = "tux";
        span.innerHTML = '<img style="vertical-align: middle;" src="img/' + img + '.gif" alt="status" /> ';
        span.innerHTML += msg;

        if (status_timeout != null)
                clearTimeout(status_timeout);

        setTimeout(function () { span.innerHTML = ""; }, 15000);
}

function UpdateNetworkConfig()
{
        ShowLoading(true);
        var objs = new Array("eth0_dhcp", "eth0_address", "eth0_broadcast",
          "eth0_netmask", "eth0_gateway", "eth1_address", "eth1_broadcast",
          "eth1_netmask", "eth1_gateway", "dns_address");
        UpdateConfig_job(objs, 0, function()
                {
                        ShowLoading(false);
                        ShowStatus("La modification a &eacute;t&eacute; effectu&eacute; avec succ&egrave;s.", true);
                        alert("Vous devez effectuer un redemarrage pour que les modifications prennent effet.");
                });
}

function UpdateConfig_job(objs, id, end_func)
{
        if (id >= objs.length) return;

        var obj = objs[id];

        var jobj = dojo.byId(obj);
        if (jobj == null) return;

        var v;
        if (obj == "eth0_dhcp" && jobj.checked)
                v = "true";
        else if (obj == "eth0_dhcp")
                v = "false";
        else
                v = jobj.value;

        dojo.io.bind({
               url: "action.php?action=local_config&opt="+obj+"&value="+v,
               load: function(type, data, evt)
                        {
                                if (id == objs.length - 1)
                                        end_func.call();
                                else
                                        UpdateConfig_job(objs, id + 1, end_func);
                        }
        });
}

function UpdateUserConfig()
{
        var uobj = dojo.byId("calaos_user");
        if (uobj == null) return;
        var pobj = dojo.byId("calaos_password");
        if (pobj == null) return;
        var pobj2 = dojo.byId("calaos_password_bis");
        if (pobj2 == null) return;

        if (pobj.value != pobj2.value)
        {
                ShowStatus("Le mot de passe ne correspond pas !", false);

                return;
        }

        dojo.io.bind({
               url: "action.php?action=local_config&opt=calaos_user&value="+uobj.value+"&param1="+pobj.value,
               load: function(type, data, evt)
                        {
                                ShowStatus("La modification a &eacute;t&eacute; effectu&eacute; avec succ&egrave;s.", true);
                                location.href = 'index.php'; //reload
                        }
        });
}

function doUpdateFWurl()
{
        var uobj = dojo.byId("update_url");
        if (uobj == null) return;

        dojo.io.bind({
               url: "action.php?action=local_config&opt=update_url&value="+uobj.value,
               load: function(type, data, evt)
                        {
                                ShowStatus("La modification a &eacute;t&eacute; effectu&eacute; avec succ&egrave;s.", true);
                        }
        });
}

function doUpdateFW()
{
        document.getElementById("form_upload").style.display = 'none';
        document.getElementById("upload_load").style.display = 'block';

        //Uses the IFrameIO transport to actually upload the file
        dojo.io.bind({
                formNode: document.getElementById("uploadform"),
                mimetype: "text/html",
                handler: function (type, data, evt)
                        {
                                if (type == "error")
                                {
                                        document.getElementById("upload_load").innerHTML =
                                                "Une erreur est survenue lors de l'upload du fichier !";
                                }
                                else if (type == "load")
                                {
                                        var html;
                                        html = 'Le fichier a &eacute;t&eacute; transf&eacute;r&eacute; avec succ&egrave;s.<br/><br/>';
                                        html += 'Installation en cours...<br/>';
                                        html += '<img style="vertical-align: middle;" alt="loading" src="img/loading.gif" />';
                                        document.getElementById("upload_load").innerHTML = html;

                                        setTimeout("doUpdateFW_phase2();", 1000);
                                }
                        }

        });
}

function doUpdateFW_phase2()
{
        dojo.io.bind({ url: "action.php?action=update_fw" });

        alert("Le systeme va maintenant effectuer un redemarrage.");

        setTimeout("location.href = 'index.php';", 5000);
}

function HelpAction(select_id)
{
        var o = dojo.byId(select_id);
        var id = o.options[o.selectedIndex].value;

        window.open("action.php?action=help&opt=action&param1=output&id=" + id + "","Calaos::Help","width=640,height=480,toolbar=0,menubar=0,location=0,scrollbars=0,directories=0,status=0");
}

function HelpCondition(select_id)
{
        var o = dojo.byId(select_id);
        var id = o.options[o.selectedIndex].value;

        window.open("action.php?action=help&opt=action&param1=input&id=" + id + "","Calaos::Help","width=640,height=480,toolbar=0,menubar=0,location=0,scrollbars=0,directories=0,status=0");
}

