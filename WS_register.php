<?php
/*
* This file is used to handle the webservice for registration process
*It checks if user already exists if not user will be registered and
*an acitvation link is send to the user
*/
// Start YOURLS engine
require_once( dirname(__FILE__).'/includes/load-yourls.php' );	
//include user.class.php file
include_once "user.class.php";	
$obj_user = new user();
if(isset($_REQUEST['m_fname']) && $_REQUEST['m_fname']!='' && isset($_REQUEST['m_lname']) && $_REQUEST['m_lanme']!='' && isset($_REQUEST['m_email']) && $_REQUEST['m_email']!='' && isset($_REQUEST['m_password']) && $_REQUEST['m_password']!=''){
	//checcking if user exitst in lyc.so
	$exists = $obj_user->chk_user_exists($_REQUEST['m_email']);	
	if($exists){
		//checking with lycos.com
		$lycos_user_exists = $obj_user->chk_lycos_user($_REQUEST['m_email']);
		if($lycos_user_exists=='Available'){
			echo 'success';
		}else{
			echo 'Not a registered user of lycos.com';
		}
	}else{
		/*if it comes here then the user does not exists in lyc.so then register 
		if user does not exists in lycos.com*/
		$lycos_user_exists = $obj_user->chk_lycos_user($_REQUEST['m_email']);
		if($lycos_user_exists=='Available'){
			//validating user data
			$valid_user = $obj_user->check_valid_data();
			if(is_array($valid_user))
				echo json_encode($valid_user);
			//add user to lyc.so(register)
			$user_reg = $obj_user->ws_register();
			if(is_numeric($user_reg) && $user_reg!='' && $user_reg>0) {
				$sent = $obj_user->send_email($user_reg, $_REQUEST['email'], $_REQUEST['u_fname']);
				if($sent)
					echo 'success';
			}
		}else{
			echo 'Not a registered user of lycos.com';
		}
	}
	
}else{
	echo 'not register';
}
?>