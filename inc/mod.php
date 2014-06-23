<?php session_start();

$filemod =  file('../data/chat/.moder');
$count_filemod = count($filemod);
for($i_mod=0;$i_mod<=$count_filemod-1;$i_mod++){
	$value_mod = $filemod[$i_mod];
	if(strlen($value_mod)>5){
		$x_mod = explode('*|*',$value_mod);
		$MOD[$i_mod] = $x_mod[1];
		#echo $Ban_User[$i_mod].'</br>';
	}
}





$MOD_length = count($MOD);

$MOD_ID = in_array ($_SESSION['user_id'],$MOD);

?>