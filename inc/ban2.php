<?php session_Start();

include '.ban.user.php';
					
$Ban_length = count($Ban_User);
$Ban_ID = in_array($_SESSION['user_id'],$Ban_User);

// end code ban file //
#if ($Ban_ID){echo 'ok!!';} else{echo 'null';}

 ?>