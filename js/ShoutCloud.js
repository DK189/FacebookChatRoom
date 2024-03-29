﻿/*
# ShoutCloud - Flexible PHP Shoutbox
# File: ShoutCloud.js
# Author: Big Ross Labs
# Version: 1.2.9
# Date: 10/20/2010
# Copyright (c) 2010 Big Ross Labs. All Rights Reserved.
# Part of the ShoutCloud package sold only on codecanyon.net!
*/
$(document).ready(function() {
	$('#shoutform').ShoutCloud({
		refreshTime: 4000,
		shoutcloudFile: 'index.php',
	  	nameText: 'Tên bạn',
	  	messageText: 'Message'	
	});
});

(function($) {
  $.fn.ShoutCloud = function(options) {
    options = $.extend({
      refreshTime: 4000,
      shoutcloudFile: 'shoutcloud.php',
	  nameText: 'Tên bạn',
	  messageText: 'Message'
    }, options);

  $(this).each(function() {
		var obj = $(this);
		var opts = options;
		var shout_refresh = setInterval(function() { refreshShouts(opts,'ajax'); }, opts.refreshTime);
		var msgsbox =  $('#content',obj);
		var userbox = $('#ShoutCloud-User',obj);
		var colorbtn = $('#ShoutCloud-Color',obj);
		var swatchbox = $('.ShoutCloud-Swatches',obj);
		var msgbox = $('#uptext',obj);
		var shoutbtn = $('#ShoutCloud-Shout',obj);
		var timer=null;
		animateMsgs(msgsbox);
		$('div.shout-msg:odd',msgsbox).addClass('shouteven');
		
		if(userbox.val()=='') {
			userbox.val(opts.nameText);
		}
		msgbox.val(opts.messageText);
		
		msgbox.bind('change keyup',function() {
			if($(this).val()!==opts.messageText) {
				var charLength = $(this).val().length;
				$('div#Text-Counter').html(charLength + '/500 characters');
			}
		});
		
		userbox.focus(function() {
			if($(this).val() == '' || $(this).val() == opts.nameText) $(this).val('');
		}).blur(function() {
			if($(this).val() == '' || $(this).val() == opts.nameText || $(this).val() == ' ') $(this).val(opts.nameText);
		});
		msgbox.focus(function() {
			if($(this).val() == '' || $(this).val() == opts.messageText) $(this).val('');
		}).blur(function() {
			if($(this).val() == '' || $(this).val() == opts.messageText || $(this).val() == ' ') $(this).val(opts.messageText);
		});
		userbox.keypress(function(e) {
			code = (e.keyCode ? e.keyCode : e.which);
			if(code == 13) msgbox.focus();
		});
		msgbox.keypress(function(e) {
			code = (e.keyCode ? e.keyCode : e.which);
			if(code == 13) shoutbtn.trigger('click');
		});
		$('#ShoutCloud-Pass',obj).live('keypress',function(e) {
			code = (e.keyCode ? e.keyCode : e.which);
			if(code == 13) $('#ShoutCloud-Admin-Login',obj).trigger('click');
		});
		
		shoutbtn.live('click', function(e) {
			e.preventDefault();
			var userboxVal = userbox.val();
			var msgboxVal = msgbox.val();
			if(userboxVal=='' || userboxVal==opts.nameText) {
				userbox.focus();
				shoutError('Vui lòng nhập tên bạn!');
				return false;
			}
			if(msgboxVal=='' || msgboxVal==opts.messageText) {
				shoutcloud_msg.focus();
				shoutError('Đùng bỏ trống nội dung!');
				return false;
			}
			if(msgboxVal=='!admin') {
				$('#ShoutCloud-InputBox',obj).append('<div id="ShoutCloud-Login"><div id="ShoutCloud-Input-Wrapper" class="margintop"><strong>Password</strong><br />'+
												 '<input type="password" name="ShoutCloud-Pass" id="ShoutCloud-Pass" /></div>'+
												 '<input type="button" name="ShoutCloud-Admin-Login" id="ShoutCloud-Admin-Login" value="Login" /></div><div class="clear"></div>');
				msgbox.val(opts.messageText);
				$('#ShoutCloud-Pass',obj).focus();
				return false;
			} else if(msgboxVal=='!help') {
				msgsbox.append('<div id="ShoutCloud-Help" class="shout-system-msg"><strong id="shouthelp">!help</strong><br />'+
							   '<span class="title">Bold:</span><span class="contents">[your text] produces <b>your text</b></span><div class="clear"></div>'+
							   '<span class="title">Italics:</span><span class="contents">{your text} produces <i>your text</i></span><div class="clear"></div>'+
							   '<span class="title">Underline:</span><span class="contents">_your text_ produces <u>your text</u></span><div class="clear"></div>'+
							   '<span class="title">Links:</span><span class="contents">Just enter a url</span><div class="clear"></div>'+
							   '<span class="title">Reply:</span><span class="contents">Click on a user\'s name to reply</span><div class="clear"></div>'+
							   '<span class="title">Multi-Lines:</span><span class="contents">Put 2 spaces in-between words</span><div class="clear"></div></div>');
				animateMsgs(msgsbox);
				msgbox.val(opts.messageText);
				return false;
			} else if(msgbox.val().length > 500) {
				shoutError('Please limit your message to 500 characters.');
				return false;
			} else {
				clearInterval(shout_refresh);
				var swatch = $('span.ShoutCloud-Swatch.sel',obj).attr('title');
				$.post(opts.shoutcloudFile, { sc_com:'post', name:userboxVal, color:swatch, msg:msgboxVal }, function(data) {
					if(data.error) {
						msgbox.focus();
						shoutError(data.error);
					} else if(data.status=='posted') {
						msgbox.val('').focus();
						refreshShouts(opts,'ajax');
					}
				}, 'json');
				shout_refresh = setInterval(function() { refreshShouts(opts,'ajax'); }, opts.refreshTime);
			}
		});
		
		colorbtn.live('click', function() {
			if(swatchbox.is(':visible')) {
				swatchbox.slideUp('normal');
			} else {
				swatchbox.slideDown('normal');
			}
		});
		$('span.ShoutCloud-Swatch',obj).live('click', function() {
			$('span.ShoutCloud-Swatch',obj).each(function() {
				$(this).removeClass('sel');
			});
			$(this).addClass('sel');
		});
		
		$('div.shout-msg a').live('click', function(e) {
			e.preventDefault();
			var lnk = $(this).attr('href');
			var conf = confirm("Are you sure you want to visit/n"+lnk+"?");
			if(conf)
				window.open(lnk,'blank');
		});
		
		$('img.ShoutCloud-Smilie',obj).live('click', function() {
			var acii = $(this).attr('id');
			if(msgbox.val()==opts.messageText) 
				msgbox.val(acii+' ').focus();
			else 
				msgbox.val(msgbox.val()+' '+acii+' ').focus();
		});
		
		$('.ShoutCloud-Reply',obj).live('click', function(e) {
			var username = $(this).attr('id');
			if(msgbox.val()==opts.messageText) 
				msgbox.val('[@'+username+'] - ').focus();
			else 
				msgbox.val(msgbox.val()+' [@'+username+'] - ').focus();
		});
		$('.ShoutCloud-Admin-Reply',obj).live('click', function(e) {
			var userdata = $(this).parent('span.shout-user-opts').parent();
			userdata = userdata.metadata();
			if(msgbox.val()==opts.messageText) 
				msgbox.val('[@'+userdata.name+'] - ').focus();
			else 
				msgbox.val(msgbox.val()+' [@'+userdata.name+'] - ').focus();
		});
		$('span.shout-user-opts .shout-del',obj).live('click', function(e) {
			var shoutid = $(this).parent('span.shout-user-opts').parent('.ShoutCloud-Admin-User-Controls').parent('div.shout-msg').attr('id');
			var conf = confirm("Are you sure you want to delete this post?");
			if(conf) {
				$.post(opts.shoutcloudFile, { sc_com:'delete', sid:shoutid }, function(data) {
					if(data.error) {
						shoutError(data.error);
					} else if(data.status=='deleted') {
						$("div.shout-msg[id='"+shoutid+"']",obj).fadeOut('normal');
						alert('Successfully deleted the requested post!');
						refreshShouts(opts,'admin');
					}
				}, 'json');
			}
		});
		$('.shout-admin-user',obj).live('click', function(e) {
			$('.ShoutCloud-Admin-User-Controls').fadeOut('fast');
			$(this).parent('div.shout-msg').find('.ShoutCloud-Admin-User-Controls').fadeIn('fast');
		});
		$('.ShoutCloud-Admin-User-Controls').hover(function() {
			if(timer!==null)
			clearTimeout(timer);
		}, function() {
			timer = setTimeout(function() { $('.ShoutCloud-Admin-User-Controls').fadeOut('fast'); }, 1000);
		});
		
		$('#ShoutCloud-Admin-Login',obj).live('click', function(e) {
			e.preventDefault();
			var passVal = $('#ShoutCloud-Pass',obj).val();
			var userVal = userbox.val();
			if(passVal=='') {
				$('#ShoutCloud-Pass',obj).focus();
				shoutError('Please enter the password!');
			} else {
				$.post(opts.shoutcloudFile, { sc_com:'login', pass:passVal, name:userVal }, function(data) {
					if(data.error) {
						shoutError(data.error);
						$('#ShoutCloud-Pass',obj).focus();
					} else if(data.status=='loggedin') {
						$('#ShoutCloud-Login',obj).slideUp('fast');
						if(!$('#ShoutCloud-Admin-Panel').is(':visible')) {
						$('#ShoutCloud-InputBox',obj).after('<div id="ShoutCloud-Admin-Panel"><span class="admin-btn shout-on" id="ShoutCloud-InputsPage">Shout</span>'+
														'<span class="admin-btn" id="ShoutCloud-BanList">Bans</span><span class="admin-btn" id="ShoutCloud-ClearChat">Clear All</span>'+
														'<span class="admin-btn" id="ShoutCloud-Admin-Logout">Logout</span></div><div class="clear"></div>');
						refreshShouts(opts,'admin');
						}
					}
				}, 'json');
			}
		});
		$('#ShoutCloud-Admin-Logout',obj).live('click', function(e) {
			e.preventDefault();
			$.post(opts.shoutcloudFile, { sc_com:'logout' }, function(data) {
				if(data.status=='loggedout') 
					$('#ShoutCloud-Admin-Panel, span.shout-ban',obj).remove();
					window.location.reload();
			}, 'json');
		});
		$('#ShoutCloud-InputsPage',obj).live('click', function() {
			$('#ShoutCloud-Admin-Panel span.admin-btn',obj).each(function() {
				$(this).removeClass('shout-on');
			});
			$(this).addClass('shout-on');
			$('#ShoutCloud-Wrapper-Admin',obj).hide();
			$('#ShoutCloud-Wrapper',obj).show();
		});
		$('#ShoutCloud-BanList',obj).live('click', function() {
			$('#ShoutCloud-Admin-Panel span.admin-btn',obj).each(function() {
				$(this).removeClass('shout-on');
			});
			$(this).addClass('shout-on');
			$('#ShoutCloud-Wrapper, #ShoutCloud-Wrapper-Admin',obj).hide();
			$.post(opts.shoutcloudFile, { display:'banlist' }, function(data) {
				if(data.error) 
					shoutError(data.error);
				else 
					$('#ShoutCloud-Error',obj).after(data.content);
			}, 'json');
		});
		$('#ShoutCloud-ClearChat',obj).live('click', function() {
			$('#ShoutCloud-Admin-Panel span.admin-btn',obj).each(function() {
				$(this).removeClass('shout-on');
			});
			$(this).addClass('shout-on');
			$('#ShoutCloud-Wrapper, #ShoutCloud-Wrapper-Admin',obj).hide();
			$.post(opts.shoutcloudFile, { display:'clear' }, function(data) {
				if(data.error) 
					shoutError(data.error);
				else 
					$('#ShoutCloud-Error',obj).after(data.content);
			}, 'json');
		});
		$('#ShoutCloud-DoClear',obj).live('click', function() {
			$.post(opts.shoutcloudFile, { sc_com:'clear' }, function(data) {
				if(data.error) {
					shoutError(data.error);
				} else if(data.status=='cleared') {
					alert('The shoutbox has been cleared!');
					refreshShouts(opts,'refresh');
				}
			}, 'json');
		});
		$('span.shout-ban',obj).live('click', function() {
			var expireTime = $(this).attr('id');
			var userdata = $(this).parent('span.shout-ban-opts').parent();
			userdata = userdata.metadata();
			var confirm_ban = confirm("Are you sure you want to ban "+userdata.name+"?");
			if(confirm_ban) {
				$.post(opts.shoutcloudFile, { sc_com:'ban-user', user:userdata.name, ip:userdata.ip, expire:expireTime }, function(data) {
					if(data.status=='banned') {
						alert(userdata.name+' has been successfully banned!');
					} else {
						shoutError('Unable to ban '+userdata.name+' at this time. Please try again.');
					}
				}, 'json');
			}
		});
		$('.ShoutCloud-BannedUser div.ShoutCloud-UnBan',obj).live('click', function() {
			var unbanip = $(this).attr('id');
			var confirm_unban = confirm("Remove this user from the Ban List?");
			if(confirm_unban) {
				$.post(opts.shoutcloudFile, { sc_com:'unban-user', ip:unbanip }, function(data) {
					if(data.status=='removed') {
						alert('User has been removed from the Ban List');
						$(".ShoutCloud-BannedUser[id='"+unbanip+"']",obj).fadeOut('normal');
					} else if(data.error) {
						shoutError(data.error);
					} else {
						shoutError('Unable to remove the ban at this time. Please try again.');
					}
				}, 'json');
			}
		});
    });
  }
  function animateMsgs(msgsbox) {
	  msgsbox.animate({ scrollTop: msgsbox.attr("scrollHeight") }, 500);
  }
  function refreshShouts(opts,type) {
	  var msgsbox = $('#ShoutCloud-MsgBox');
	  var shoutMsg = $('div.shout-msg');
	  var lastShout = shoutMsg.filter(':last').attr('id');
	  if(type=='admin') {
		  lastShout = '-1';
	  }
	  if(type=='refresh') {
		  type = 'ajax';
		  lastShout = '-1';
		  $("div.shout-msg[id!='shoutid-0']").each(function() {
			  $(this).fadeOut('fast');
		  });
	  }
	  removeShouts();
	  $.post(opts.shoutcloudFile, { sc_com:type, last:lastShout }, function(data) {
		  if(data && data.msgs) {
			  if(type=='admin') {
				  msgsbox.empty();
			  }
			  msgsbox.append(data.msgs);
			  removeShouts();
			  if(shoutMsg.size() > 50) {
				  var diff = shoutMsg.size() - 50;
				  var i=0;
				  while(diff>=i) {
					  shoutMsg.first().remove();
					  i++;
				  }
			  }
			  $('div.shout-msg:odd',msgsbox).addClass('shouteven');
			  msgsbox.animate({ scrollTop: msgsbox.attr("scrollHeight") }, 500);
		  }
	  }, 'json');
  }
  function removeShouts() {
	  var seen = {};
	  $('div.shout-msg').each(function() {
		  var post = $(this).attr('id');
		  if(seen[post]) {
			  $(this).fadeOut('fast');
			  $('div.shout-msg:odd',msgsbox).addClass('shouteven');
		  } else {
			  seen[post] = true;
		  }
	  });
  }
  function shoutError(err) {
	  $('#ShoutCloud-Error').html('<strong>Error!</strong>'+err).stop().slideDown('fast').delay(4000).slideUp('fast');
  }
})(jQuery);

/*
 * Metadata - jQuery plugin for parsing metadata from elements
 * Copyright (c) 2006 John Resig, Yehuda Katz, Paul McLanahan
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 * Revision: $Id: jquery.metadata.js 4187 2007-12-16 17:15:27Z joern.zaefferer $
 */
(function($){$.extend({metadata:{defaults:{type:"attr",name:"data",cre:/({.*})/,single:"metadata"},setType:function(type,name){this.defaults.type=type;this.defaults.name=name},get:function(elem,opts){var settings=$.extend({},this.defaults,opts);if(!settings.single.length){settings.single="metadata"}var data=$.data(elem,settings.single);if(data){return data}data="{}";if(settings.type=="class"){var m=settings.cre.exec(elem.className);if(m){data=m[1]}}else{if(settings.type=="elem"){if(!elem.getElementsByTagName){return}var e=elem.getElementsByTagName(settings.name);if(e.length){data=$.trim(e[0].innerHTML)}}else{if(elem.getAttribute!=undefined){var attr=elem.getAttribute(settings.name);if(attr){data=attr}}}}if(data.indexOf("{")<0){data="{"+data+"}"}data=eval("("+data+")");$.data(elem,settings.single,data);return data}}});$.fn.metadata=function(opts){return $.metadata.get(this[0],opts)}})(jQuery);