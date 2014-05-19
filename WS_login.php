<?php
/*
* This file is used to handle the webservice for login process
* It checks if valid user or not
*/
// Start YOURLS engine
require_once( dirname(__FILE__).'/includes/load-yourls.php' );	
//include user.class.php file
include_once "user.class.php";	
$obj_user = new user();
if(isset($_REQUEST['m_email']) && $_REQUEST['m_email']!='' && isset($_REQUEST['m_password']) && $_REQUEST['m_password']!=''){
	//checcking if user exitst in lyc.so
	$exists = $obj_user->chk_user_with_email_pwd($_REQUEST['m_email'],$_REQUEST['m_password']);	
	if($exists){
		//checking with lycos.com		
		$lycos_user_exists = $obj_user->chk_lycos_user_with_email_pwd($_REQUEST['m_email'],$_REQUEST['m_password']);
		if($lycos_user_exists == 'Available'){
			echo 'available/login success redirect';
		}
	}else{
		//checking with lycos.com		
		$lycos_user_exists = $obj_user->chk_lycos_user_with_email_pwd($_REQUEST['m_email'],$_REQUEST['m_password']);
		if($lycos_user_exists == 'Available'){
			echo 'add to lyc.so';
		}else{
			echo 'failure';
		}
	}
}
	

?>