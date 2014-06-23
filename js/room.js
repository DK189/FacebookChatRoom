function check_room()
{
	if(document.getElementById('room').value == "")
	{
		alert("Bạn chưa chọn room!!");
		document.getElementById('room').focus();
	}
	
	else
		send_room();
}



function send_room(){
	var room = document.getElementById('room').value;
	
	ajax.onreadystatechange(send_chat_done);
	ajax.send("sign.php?act=chon_room",room);
	document.getElementById('room').value='';			
	
	var objDiv = document.getElementById("main");
	objDiv.scrollTop = objDiv.scrollHeight;
}






var AJAX_Handler=function()
{this.xmlHttp=false;try{this.xmlHttp=new XMLHttpRequest();}catch(e){try{this.xmlHttp=new ActiveXObject('Microsoft.XMLHTTP');}catch(e){try{this.xmlHttp=new ActiveXObject('Msxml2.XMLHTTP');}catch(e){alert('Your browser does not support AJAX');return;}}}
this.onreadystatechange=function(updateFunc){this.updateFunc=updateFunc;}
this.send=function(url,param){param=param?param:"";this.xmlHttp.onreadystatechange=this.updateFunc;this.xmlHttp.open("POST",url,true);this.xmlHttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");this.xmlHttp.setRequestHeader("Content-length",param.length);this.xmlHttp.setRequestHeader("Connection","close");this.xmlHttp.send(encodeURI(param));this.handler=this.xmlHttp;}}