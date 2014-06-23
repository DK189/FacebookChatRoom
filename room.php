<?php

$fileroom =  file('../data/chat/.room');
$count_fileroom = count($fileroom);
for($i_room=0;$i_room<=$count_fileroom-1;$i_room++){
	$value_room = $fileroom[$i_room];
	if(strlen($value_room)>0){
		$x_room = explode('*|*',$value_room);
		$room[$i_room] = $x_room[1];
		#echo $Ban_User[$i_room].'</br>';
	}
}



$RoomLength = count($room);

?>
				