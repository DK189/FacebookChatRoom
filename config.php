<?php

	include '../config.php';
	include 'inc/config.php';
	include 'inc/functions.php';
	
		$userid = $facebook->getUser();
		$paramsp['redirect_uri'] = 'http://kingdark.hopto.org/facebook/Chat';
		$link_app = '/facebook/chat';
	

?>