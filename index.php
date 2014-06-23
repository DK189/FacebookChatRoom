<?php session_start();
## Đọc file chống hack
define('index',true);
## 


include 'inc/config.php';
include 'inc/functions.php';
require 'room.php';
include 'inc/ban.php';
include 'inc/mod.php';







#$MOD = array('303356639834443');
#$MOD_ID = in_array ($_SESSION['user_id'],$MOD);
#if ($Ban_ID){echo 'ok!!';} else{echo 'null';}

#if(!$_SESSION['user_room'] == '') {  $_SESSION['user_room'] = $room[0] };

?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>


<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&appId=468480436572496&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>


<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Chat <?include '.ver'?></title>
<link href="ShoutCloud-min.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="js/function.js"></script>
<script type="text/javascript">
function ajax_load(url,id){
	var ajax = new AJAX_Handler();
	ajax.onreadystatechange(change_);
	ajax.send(url);		
	function change_(){
		if(ajax.handler.readyState == 4 && ajax.handler.status == 200)
			document.getElementById(id).innerHTML = ajax.handler.responseText;
	}
}
function _load(value){
	if(value == 'content'){
		ajax_load('content.php','content');
		var objDiv = document.getElementById("main");
		objDiv.scrollTop = objDiv.scrollHeight;
	}
	else if(value == 'online')
		ajax_load('online.php','userlist');	
}

function _refresh(value){
	if(value == 'content')
		setTimeout("_load('content');_refresh('content');", <?php echo $config['refresh_content']?>000);	
	else if(value == 'online')	
		setTimeout("_load('online');_refresh('online');", <?php echo $config['refresh_online']?>000);
	
}
var objDiv = document.getElementById("main");
objDiv.scrollTop = objDiv.scrollHeight;

</script>
</head>

<body>
<?php
switch($_GET['param']):
	case 'smilies': include 'smilies.php'; break;
	case 'sign': include 'sign.php'; break;
	case 'logout': session_destroy(); print_cp_mess('./','',0);break;
	default:
?>
<div id="wrapper">
	<div id="nav"> 
    	<div id="nav_link">
        	| <a href="./">Home</a> | 
          <?php if($_SESSION['user_login']==''){ ?><a onclick="signpopup();" href="javascript:ajax_load('sign.php?act=in','main')">Đăng nhập</a> | <?php } ?>
			<a onclick="signpopup();" href="javascript:ajax_load('sign.php?act=room','main')">Chọn phòng chat</a> |
			<?php if(in_array($_SESSION['user_id'],$MOD)){ ?><a onclick="signpopup();" href="javascript:ajax_load('sign.php?act=edit','main')">Quản lý Ban ID</a> | <?php } ?>
			
        </div>
        <div id="welcome">
        <?php if($_SESSION['user_login']) 
			echo '<div class="fb-share-button" data-href="https://apps.facebook.com/chat-in-web/" data-type="button_count"></div> | Chào mừng <a target="black" href="http://fb.com/'.$_SESSION['user_id'].'" >'.$_SESSION['user_login'].'</a>! bạn đang ở [ <b>'.$_SESSION['user_room'].'</b> ] <a href="?param=logout">Thoát</a>';
		?>
        </div>
    </div><!--/#nav-->
    <div id="main">
   		<div id="content">
        	Loading...
    	</div><!--/#content-->
    </div><!--/#main-->
    
    <div id="userlist">
   		Loading...
    </div><!--/#userlist-->
	
	<script>
_load('content');
_load('online');
_refresh('content');
_refresh('online');
</script>
	
     <?php if($Ban_ID){ echo 'ID:  [<b> '; echo $_SESSION['user_id']; echo ' </b>]'; include '../data/chat/.ban.ok';
 exit();}  ?>						
	 
    <div id="shoutform">
   		<form name="chatform" method="post" action="javascript:check_form();">
        <div id="upstyle">
            <input id="upb" onclick="upstyle('b')" type="button" class="sbutton" style="font-weight:bold" value="B" />
            <input id="upi" onclick="upstyle('i')" type="button" class="sbutton" style="font-style:italic" value="I" />
            <input id="upu" onclick="upstyle('u')" type="button" class="sbutton" style="text-transform:uppercase" value="U" />
            <select id="upfont">
                <option value="">Default</option>
                <option value="Arial" style="font-family:Arial">Arial</option>
                <option value="Arial Black" style="font-family:Arial Black">Arial Black</option>
                <option value="Book Antiqua" style="font-family:Book Antiqua">Book Antiqua</option>
                <option value="Century Gothic" style="font-family:Century Gothic">Century Gothic</option>
                <option value="Comic Sans MS" style="font-family:Comic Sans MS">Comic Sans MS</option>
                <option value="Courier New" style="font-family:Courier New">Courier New</option>
                <option value="Impact" style="font-family:Impact">Impact</option>
                <option value="Tahoma" style="font-family:Tahoma">Tahoma</option>
                <option value="Times New Roman" style="font-family:Times New Roman">Times New Roman</option>
                <option value="Trebuchet MS" style="font-family:Trebuchet MS">Trebuchet MS</option>
                <option value="Verdana" style="font-family:Verdana">Verdana</option>
            </select>
            <select id="upcolor">
                <option value="">Default</option>
                <option style="background: Gold;" value="Gold">Gold</option>
                <option style="background: Khaki;" value="Khaki">Khaki</option>
                <option style="background: Orange;" value="Orange">Orange</option>
                <option style="background: LightPink;" value="LightPink">LightPink</option>
                <option style="background: Salmon;" value="Salmon">Salmon</option>
                <option style="background: Tomato;" value="Tomato">Tomato</option>
                <option style="background: Red;" value="Red">Red</option>
                <option style="background: Brown;" value="Brown">Brown</option>
                <option style="background: Maroon;" value="Maroon">Maroon</option>
                <option style="background: DarkGreen;" value="DarkGreen">DarkGreen</option>
                <option style="background: DarkCyan;" value="DarkCyan">DarkCyan</option>
                <option style="background: LightSeaGreen;" value="LightSeaGreen">LightSeaGreen</option>
                <option style="background: LawnGreen;" value="LawnGreen">LawnGreen</option>
                <option style="background: MediumSeaGreen;" value="MediumSeaGreen">MediumSeaGreen</option>
                <option style="background: BlueViolet;" value="BlueViolet">BlueViolet</option>
                <option style="background: Cyan;" value="Cyan">Cyan</option>
                <option style="background: Blue;" value="Blue">Blue</option>
                <option style="background: DodgerBlue;" value="DodgerBlue">DodgerBlue</option>
                <option style="background: LightSkyBlue;" value="LightSkyBlue">LightSkyBlue</option>
                <option style="background: White;" value="White">White</option>
                <option style="background: DimGray;" value="DimGray">DimGray</option>
                <option style="background: DarkGray;" value="DarkGray">DarkGray</option>
                <option style="background: Black;" value="Black">Black</option>
            </select>
            <input type="button" class="sbutton" value="Smilies" onclick="smiliepopup();" />
        </div> <!--/#upstyle-->
        <div id="upwrite">
        	<div ><input maxlength="255" type="text" name="uptext" id="uptext"/><div id="Text-Counter">0/500</div></div>
            <div ><input type="submit" id="submitform" value="Send" /></div>
			<input type="hidden" id="name" value="<?php echo $_SESSION['user_login'] ?>" />
            <input type="hidden" id="ip" value="<?php echo $_SESSION['user_id'] ?>" />
		</div><!--/#upwrite-->
        </form>
			
		</div><!--/#shoutform-->

</div><!--/#wrapper-->


<?php endswitch;?>
</body>
</html>