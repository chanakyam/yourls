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
	$lycos_user_exists = $obj_user->chk_lycos_user_with_email_pwd($_REQUEST['m_email'],$_REQUEST['m_password']);
	echo $code = $lycos_user_exists->result->status->code;
	//checcking if user exitst in lyc.so
	$exists = $obj_user->chk_user_with_email_pwd($_REQUEST['m_email'],md5($_REQUEST['m_password']));	
	if($exists){
		//checking with lycos.com		
		//echo $lycos_user_exists = $obj_user->chk_lycos_user_with_email_pwd($_REQUEST['m_email'],$_REQUEST['m_password']);
		if($code == 0){//user exists in lycos.com
			echo 'Login success redirect to home page';
			header('location: user.php?username='.$_REQUEST["m_email"].'&password='.$_REQUEST["m_password"]);exit;
		}else{
			echo $lycos_user_exists->result->status->message;

		}
	}else{
		//checking with lycos.com		
		//$lycos_user_exists = $obj_user->chk_lycos_user_with_email_pwd($_REQUEST['m_email'],$_REQUEST['m_password']);
		if($code == 0){
			//user exists in lycos.com but not exists in lyc.so
			echo 'add to lyc.so/need to register with lyc.so';
			//inserting into lyc.so
			$user_data['fname']     = $lycos_user_exists->data->user_data->first_name;
			$user_data['lname']     = $lycos_user_exists->data->user_data->last_name;
			$user_data['email']     = $lycos_user_exists->data->user_data->email;
			$user_data['password']  = md5($_REQUEST["m_password"]);
			$user_data['status']    = 'Active';
			$user_data['role']      = 'User';
			$user_data['signature'] = yourls_auth_signature_new_user( $_REQUEST["m_email"]);
			//echo '<pre>';print_r($user_data);
			$user_reg = $obj_user->ws_register($user_data);
			if(is_numeric($user_reg) && $user_reg!='' && $user_reg>0) {				
				echo 'Successfully registered redirect to login/home page';
			}
		}else{
			echo 'failure';
		}
	}
}
	

?>