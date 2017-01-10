<?php

function checklogin(){
	$username = I('request.username','');
	$token = I('request.token','');
	$cache = S('login_'.$username);
	if($cache['token']==$token){
		return true;
	}else{
		return false;
	}	
}

?> 