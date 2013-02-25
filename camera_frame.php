<?php
        //Check user identity
        require "auth.php";

        $cam_id = @$_GET['cam_id'];
        if (!isset($cam_id))
                die ("Error: cam_id not set...");
        $ptz = @$_GET['ptz'];
        if (!isset($ptz))
                die ("Error: ptz not set...");
?>

<img style="width:480px; height:360px;" id="ipcam_frame_<?php echo $cam_id; ?>"
 alt="camera" src="camera.php?camera_id=<?php echo $cam_id; ?>"/>
<div id="camid_loading_<?php echo $cam_id; ?>" style="position:absolute; top: 330px; left: 150px; display: none;">
<img alt="load" src="img/loading.gif"/>
</div>
<table><tr>
<td>Refresh time (ms):</td>
<td><input dojoType="IntegerSpinner" value="5000" max="60000" signed="never" maxlength="5" widgetId="spinner_<?php echo $cam_id; ?>"></td>
</tr></table>

<?php
        if ($ptz == "true")
        {
?>
<div style="position: absolute; top: 260px; left: 390px;">
<table style="text-align: center;" border="0" cellpadding="0" cellspacing="0">
<tbody><tr>
        <td></td>
        <td><a href="javascript:CameraMove('<?php echo $cam_id; ?>','up');"><img alt="up" src="img/cam_up.gif"/></a></td>
        <td></td>
</tr>
<tr>
        <td><a href="javascript:CameraMove('<?php echo $cam_id; ?>','left');"><img alt="left" src="img/cam_left.gif"/></a></td>
        <td><a href="javascript:CameraMove('<?php echo $cam_id; ?>','home');"><img alt="home" src="img/cam_home.gif"></a></td>
        <td><a href="javascript:CameraMove('<?php echo $cam_id; ?>','right');"><img alt="right" src="img/cam_right.gif"></a></td>
</tr>
<tr>
        <td></td>
        <td><a href="javascript:CameraMove('<?php echo $cam_id; ?>','down');"><img alt="down" src="img/cam_down.gif"></a></td>
        <td></td>
</tr></tbody>
</table>
</div>
<table><tr>
<td><select id="control_<?php echo $cam_id; ?>" dojoType="Select" autocomplete="false">
<option value="1">Position 1</option><option value="2">Position 2</option>
<option value="3">Position 3</option><option value="4">Position 4</option>
<option value="5">Position 5</option><option value="6">Position 6</option>
<option value="7">Position 7</option><option value="8">Position 8</option>
<option value="9">Position 9</option><option value="10">Position 10</option>
<option value="11">Position 11</option><option value="12">Position 12</option>
<option value="13">Position 13</option><option value="14">Position 14</option>
<option value="15">Position 15</option><option value="16">Position 16</option>
</select></td>
<td><button dojoType="Button" onclick="CameraRecall('<?php echo $cam_id; ?>'); return true;">
<div class="inside_button">Recall</div></button></td>
<td><button dojoType="Button" onclick="CameraSave('<?php echo $cam_id; ?>'); return true;">
<div class="inside_button">Save</div></button></td>
</tr></table>

<?php
        }
?>