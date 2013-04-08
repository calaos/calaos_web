
function graphs()
{
    $("#content").load("graphs.php");
}

function ShowRoom(room, count)
{
    $("#content").load("room.php?room_id=0&room_type="+room);
}
