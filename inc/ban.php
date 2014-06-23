<?php session_Start();


					

############################################################


$fileban =  file('../data/chat/.ban');
$count_fileban = count($fileban);
for($i_f=0;$i_f<=$count_fileban-1;$i_f++){
	$value_f = $fileban[$i_f];
	if(strlen($value_f)>5){
		$x_f = explode('*|*',$value_f);
		$Ban_User[$i_f] = $x_f[1];
		#echo $Ban_User[$i_f].'</br>';
	}
}
#print_r($Ban_User);

$Ban_length = count($Ban_User);
$Ban_ID = in_array($_SESSION['user_id'],$Ban_User);
#echo $_SESSION['user_id'].'</br>';
#if ($Ban_ID){echo 'ok!!';} else{echo 'null';}


############################################################
 
 
 
 ?>