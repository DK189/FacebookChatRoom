

<?php session_start();
#if(!defined('index')) die ('Troll Pro..!! <img src="http://us.i1.yimg.com/us.yimg.com/i/mesg/emoticons7/10.gif"></img>');
require 'room.php';
require 'config.php';
echo '<div id="sign">';
switch($_GET['act']):


	case 'in':
							$userid = $facebook->getUser();

					if ($userid) {
					  try {
						// Proceed knowing you have a logged in user who's authenticated.
						$user = $facebook->api('/me');
						$_SESSION['user_login'] = $user['name'];
						$_SESSION['user_id'] = $userid;
						if($_SESSION['user_room']=='') { $_SESSION['user_room'] = $room[0]; } ;
						echo '<center>';
						echo 'Thông tin tài khoản: </br>';
						#var_dump ($user);
						echo 'name: ';
						echo '<a href="http://fb.com/',$userid,'" >',$user['name'],'</a>';
						echo '</br> id: ',$userid;
						echo '</br> Bạn hiện đang ở [ <b>'.$_SESSION['user_room'].'</b> ]!!';
						## echo '</br>Chi tiết: </br> ';
						## print htmlspecialchars(print_r($user, true));
						## echo '</br></br></br><a href='.$link_app.' >Nhấn đây để về phòng chat!</>';
						echo '<META http-equiv="refresh" content="0;URL='.$link_app.'">';
						
						echo '</center>';
					  } catch (FacebookApiException $e) {
						exit('Lỗi: '.$e->getMessage());
						
					  }
					}
					else
					{
						$loginUrl = $facebook->getLoginUrl();
						
						exit('<h3><a href='.$loginUrl.'" target=_top>Vui lòng nhấn vào đây để đăng nhập bằng facebook</h3></a>');
					}

					if ($user) {
					  $logoutUrl = $facebook->getLogoutUrl();
					  # echo '</br><a title="Khi nhấn nút này, facebook của bạn có thể bị thoát" href=',$logoutUrl,'>Logout</a>';
					} else {
					  $statusUrl = $facebook->getLoginStatusUrl();
					  echo '</br></br>',$statusUrl;
					  
					}
					
	break;
	
	case 'room':
		
			echo '<center>';
		
		if ($room_id == null) {
				$room_id = 0; } 
		# echo '<form name="room" method="post" action="javascript:check_room();">';	
		echo '<form name="room" method="post" target="_top" action="sign.php?act=chon_room">';			
		
		
		
		for($x=0;$x<$RoomLength;$x++) {
					  echo '<input type=radio name="room" id="room" value="'.$room[$x].'">'.$room[$x].'</input>';
					  echo "<br>";
										}
		echo '<input type="submit" id="chon_phong" value="Vào!" /></br>';
		$_SESSION['user_room'] = $room[$room_id];
		
		# echo = $_SESSION['user_room'];
		
		
		echo '</form>';
			echo '</center>';
		
		
		
		
	break;
	case 'chon_room':
	
	
		if ($_POST['room']) {
		$_SESSION['user_room'] = $_POST[room];
		echo 'Phòng chát hiện tại là: [ '.$_SESSION['user_room'].' ]'; 
		#echo '</br></br></br><a href='.$link_app.' >Nhấn đây để về phòng chat!</>';
		}
		else{$_SESSION['user_room'] = $room[0];
		Echo 'Bạn chọn phòng chát không hợp lệ, phòng chat hiện tại là: [ '.$_SESSION['user_room'].' ]';
		#echo '</br></br></br><a href='.$link_app.' >Nhấn đây để về phòng chat!</>';
		}
		echo '</br>Vui lòng đợi 2 giây để load dũ liệu!!   </br> ';
		sleep(2);
		echo 'data load success!! '; sleep(0.2); echo '  => Index';
		echo '<META http-equiv="refresh" content="0;URL='.$link_app.'">';
	
	break;
	
	case 'edit':	
		include 'inc/mod.php';
		if ($MOD_ID){
				// configuration
				$url = 'index.php';
				$file = '../data/chat/.ban';
				
				// check if form has been submitted
				if (isset($_POST['text']))
							{
				// save the text contents
					file_put_contents($file, $_POST['text']);
						// redirect to form again
					header(sprintf('Location: %s', $url,'?'));
					printf('</br><a href="%s">Refresh</a>.', htmlspecialchars($url));
					exit();
				}
					// read the textfile
					$text = file_get_contents($file);
					
					?>
					<!--Script show/hidden hướng dẫn-->
					
					<!-- HTML form -->
					
					<script>
					function how(){
						window.open("hd.php?how=mod", "", "location=no,scrollbars=yes,width=500,height=500");
}
					</script>
					<center>					
					<div>
					
					
					<form action="sign.php?act=edit" method="post">
					<div id="editarea" >	<textarea style="width:400px; height:300px;"  name="text"><?php echo htmlspecialchars($text) ?></textarea>	</div>
						
												
					</br><input type="submit" /></div>
					</br>
					</form>
					<a onclick="signpopup();" href="javascript:ajax_load('sign.php?act=mod','main')">Bấm đây để đọc hướng dẫn</a>
					</center>
<?php 	} else die ('Hello pro!');

	break;
	
	
	case 'mod':


include 'inc/mod.php';
		if ($MOD_ID){ ?>
		
						<div id="info" ><center><h4>
								Hướng dẫn edit ban list đối với các mod uỷ quyền: </br>
								(1): Lấy User ID của người cần ban: </br>
								chuột phải vào tên người dùng rồi chọn "copy link address" </br>
								            Lúc này ta sẽ có 1 đường dẫn có dạng " http://fb.com/[User_ID] </br>
											VD: với đường dẫn của bạn là: http://fb.com/<?=$_SESSION['user_id']?> </br>
											   thì User ID của bạn là <?=$_SESSION['user_id']?> !! tương tự với các tài khoản khác!!</br>
								</br>
								(2): Soạn thảo dữ liệu chứa ban user id : </br>
								Trước hết bạn cần phải biết dữ liệu được lưu với cấu trúc nào: </br>
								Trong cơ sở dũ liệu của ứng dụng Chat NOW mà cụ thể tại đây là ban list. </br>
									cấu trúc như sau: mỗi 1 dòng tương ứng 1 tài khoản bị đình chỉ hoạt động, </br>
									trong mỗi dòng đó lại gồm 2 phần *cú pháp* và *user id*!!</br>
									*user id* thì các bạn đã biết thồng qua bước 1..!! </br>
									còn *cú pháp* là phần chỉ cho ứng dụng thấy trong đó có chứa id tài khoản cần đình chỉ</br>
									*cú pháp* có dạng như sau: *|*[user id]*|*    trong đó *|* là thẻ code tag.. như bb tag hay html tag</br>
									[user id] là id của tài khoản cần ban!! </br></br>
									VD: nếu bạn muốn ban chính bạn thì thêm 1 dòng mới có dạng: *|*<?=$_SESSION['user_id']?>*|* </br>
									Bạn hoàn toàn yên tâm, việc ban này chỉ có tác dụng ngăn chặn người khác nhắn tin trong Chat NOW </br>
									chứ không có tác dụng xoá bạn ra khoả MOD list!! việc kiểm soát MOD list chỉ được thực hiện bởi người sử hữu ứng dụng là mình</br>
									nên bạn dù bạn có tự ban chính mình thì bạn cũng chỉ bị tạm khoá nhắn tin trên Chat NOW,và bạn vẫn còn quyền chình sửa ban list này!! </br>
									<h3>[Lưu ý]: nếu trong trường hợp bạn không thể nhìn thấy hay truy cập vào ban list, có nghĩa là bạn đã bị xoá tên xoá tên khỏi MOD list!!</h3>
								</h4>
								<a onclick="signpopup();" href="javascript:ajax_load('sign.php?act=edit','main')">Bấm đây để quay lại</a>
						</center>		
						</div>
		<?php } break; ?>

		
		
		
		
		<?php
		case 'admin':	
		
		$ADMIN = array('303356639834442','100004803250208');
		
		if (in_array ($_SESSION['user_id'],$ADMIN)){
				// configuration
				$url_admin = 'sign.php?act=admin';
				$file1 = '../data/chat/.moder';
				$file2 = '../data/chat/.ban.ok';
				$file_room = 'room.php';
				$file_chat = '../data/chat/room/'.md5($_SESSION['user_room']).'.txt';
				// check if form has been submitted
				
					// read the textfile
					$text1 = file_get_contents($file1);
					$text2 = file_get_contents($file2);
					$text_chat = file_get_contents($file_chat);
					$text_room = file_get_contents($file_room);
					echo '<a href="index.php" target=_TOP>Về phòng chat</a></br>';
					echo '<a href="sign.php?act=admin&edit=chat">Edit DATA room chat</a> | <a href="sign.php?act=admin&edit=banok">Edit thông báo ban</a> | <a href="sign.php?act=admin&edit=mod">Edit mod list</a> | <a href="sign.php?act=admin&edit=room">Edit room list</a> | <a href="sign.php?act=admin&edit=root">root</a>'; 
					
					switch($_GET['edit']):
							case 'mod': if (isset($_POST['text1'])){ echo '</br>Ok!'; file_put_contents($file1, $_POST['text1']); header(sprintf('Location: %s'.$url_admin.'&edit=mod?'.rand(1,100))); printf('</br><a href="%s">Refresh</a>.', htmlspecialchars($url_admin.'&edit=mod')); exit(); }
							
							?>
									<!--Script show/hidden hướng dẫn-->
									
									<!-- HTML form -->
									
									
									<center>					
									<div>
									
									
									<form target="_top" action="sign.php?act=admin&edit=mod" method="post">
									Mod list id:</br>
									<textarea style="width:1200px; height:300px;"  name="text1"><?php echo htmlspecialchars($text1) ?></textarea>
									</br></br>------------------------------------------------------------------------------
									</br></br>
									
									</br><input type="submit" />
									</br>
									</form>
									
									
									
									</div>
									</center><?php
							break;
							case 'banok': if (isset($_POST['text2'])){ echo '</br>Ok!'; file_put_contents($file2, $_POST['text2']); header(sprintf('Location: %s'.$url_admin.'&edit=banok?'.rand(1,100))); printf('</br><a href="%s">Refresh</a>.', htmlspecialchars($url_admin.'&edit=banok')); exit(); }
								?>
									<!--Script show/hidden hướng dẫn-->
									
									<!-- HTML form -->
									
									
									<center>					
									<div>
									
									
									<form  target="_top" action="sign.php?act=admin&edit=banok" method="post">
									Thông báo ban user: </br>
									<textarea style="width:1200px; height:300px;"  name="text2"><?php echo htmlspecialchars($text2) ?></textarea>
									</br></br>------------------------------------------------------------------------------
									</br></br>
									
									
									</br><input type="submit" />
									</br>
									</form>
									
									
									
									</div>
									</center><?php
							break;
							case 'chat': if (isset($_POST['text_chat'])){ echo '</br>Ok!'; file_put_contents($file_chat, $_POST['text_chat']); header(sprintf('Location: %s'.$url_admin.'&edit=chat?'.rand(1,100))); printf('</br><a href="%s">Refresh</a>.', htmlspecialchars($url_admin.'&edit=chat')); exit(); }
									?>
								<!--Script show/hidden hướng dẫn-->
								
								<!-- HTML form -->
								
								
								<center>					
								<div>
								
								
								<form target="_top"  action="sign.php?act=admin&edit=chat" method="post">
								DATA chat of [ <?=$_SESSION['user_room']?> ]: </br>
								<textarea style="width:1200px; height:300px;"  name="text_chat"><?php echo htmlspecialchars($text_chat) ?></textarea>
								</br></br>------------------------------------------------------------------------------
									</br></br>
								</br><input type="submit" />
								</br>
								</form>
								</br>
								| <a href="<?echo '../data/chat'?>">DATA chat</a> |</br>
								
								
								</div>
								</center><?php
							break;
							
							case 'room': if (isset($_POST['text_room'])){ echo '</br>Ok!'; file_put_contents($file_room, $_POST['text_room']); header(sprintf('Location: %s'.$url_admin.'&edit=room?'.rand(1,100))); printf('</br><a href="%s">Refresh</a>.', htmlspecialchars($url_admin.'&edit=room')); exit(); }
									?>
								<!--Script show/hidden hướng dẫn-->
								
								<!-- HTML form -->
								
								
								<center>					
								<div>
								
								
								<form target="_top"  action="sign.php?act=admin&edit=room" method="post">
								Phòng chat: </br>
								<textarea style="width:1200px; height:300px;"  name="text_room"><?php echo htmlspecialchars($text_room) ?></textarea>
								</br></br>------------------------------------------------------------------------------
									</br></br>
								</br><input type="submit" />
								</br>
								</form>
								</br>
								| <a href="<?echo '../data/chat'?>">DATA chat</a> |</br>
								
								
								</div>
								</center><?php
							break;
							case 'root':
							echo '</br></br>';
								$dir = '.';

									// Open a directory, and read its contents
									if (is_dir($dir)){
									  if ($dh = opendir($dir)){
										while (($file = readdir($dh)) !== false){
										  echo 'tên tệp: <a href="sign.php?act=admin&edit=root&file_root='.$file.'">'.$file.'</a><br>';
										}
										closedir($dh);
									  }}
									  if ($_GET['file_root']){ 
											$url_admin = 'sign.php?act=admin'; 
											$file_root = $_GET['file_root']; 
											$text_root = file_get_contents($file_root); 
											if (isset($_POST['text_root'])){ echo '</br>Ok!'; file_put_contents($file_root, $_POST['text_root']); header(sprintf('Location: %s'.$url_admin.'&edit=root&file_root='.$_GET['file_root'])); printf('</br><a href="%s">Refresh</a>.', htmlspecialchars($url_admin.'&edit=root&file_root='.$_GET['file_root'])); exit(); }
											
											
													?>
													<!--Script show/hidden hướng dẫn-->
													
													<!-- HTML form -->
													
													
													<center>					
													<div>
													
													
													<form target="_top"  action="sign.php?act=admin&edit=root&file_root=<?=$file_root?>" method="post">
													<?=$file_root?>: </br>
													<textarea style="width:1200px; height:600px;"  name="text_root"><?php echo htmlspecialchars($text_root) ?></textarea>
													</br></br>------------------------------------------------------------------------------
														</br></br>
													</br><input type="submit" />
													</br>
													</form>
													</br>
													
													
													
													</div>
													</center>
													<?php
											
											
											}
							break;
											
					
							
					endswitch;
		} else die ('null');

	break;
	
endswitch;?>
