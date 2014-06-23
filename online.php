<?php session_start();
include 'inc/config.php';
include 'inc/functions.php';
$room_md5 = md5($_SESSION['user_room']);
$content =  file('room/'.$room_md5.'.txt');
$time_now = time();
#tính từ thời điểm hiện tại trở về trước 15 phút
$end_time = $time_now - 60*$config['onlstats']; //15 phut

$count = count($content);
for($i=$count-1;$i>=0;$i--){
	$x = explode('*|*',$content[$i]);
	$time = $x[1];
	$name = $x[2];
	$ip	= $x[9];
	if($time >= $end_time){
		$arr_onl[$name] = $name;
	}

}
#smilies/lol.png
#http://l.yimg.com/us.yimg.com/i/mesg/emoticons7/1.gif
#http://img375.imageshack.us/img375/8412/onlineu.png
$template = '<a href=http://fb.com/{$ip}/ target=_black><img src="smilies/lol.png"> {$name} </a> | ';
if($arr_onl){
	foreach($arr_onl as $name)
		eval('$onlines .= "'.addslashes($template).'";');
}
else { $onlines = 'Không ai chat cả.. chán quá.. TT~TT '; }
echo '<ul>| '.$onlines.'</ul>';

?>