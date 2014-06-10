<?php
/*
* This file is used to handle the webservice for registration process
* It checks if user already exists if not user will be registered and
* an acitvation link is send to the user.
*/
// Start YOURLS engine
require_once( dirname(__FILE__).'/includes/load-yourls.php' );	
//include user.class.php file
include_once "user.class.php";	
$obj_user = new user();
if(isset($_REQUEST['m_fname']) && $_REQUEST['m_fname']!='' && isset($_REQUEST['m_lname']) && $_REQUEST['m_lname']!='' && isset($_REQUEST['m_email']) && $_REQUEST['m_email']!='' && isset($_REQUEST['m_password']) && $_REQUEST['m_password']!=''){
	$lycos_user_exists = $obj_user->chk_lycos_user($_REQUEST['m_fname'],$_REQUEST['m_email'],$_REQUEST['m_password']);
	echo $code = $lycos_user_exists->result->status->code;
	//checcking if user exists in lyc.so
	$exists = $obj_user->chk_user_exists($_REQUEST['m_email']);	
	if($exists){//exists in lyc.so
		echo 'user exists in lyc.so<br>';
		//checking with lycos.com
		//$lycos_user_exists = $obj_user->chk_lycos_user($_REQUEST['m_fname'],$_REQUEST['m_email'],$_REQUEST['m_password']);
		//$code = $lycos_user_exists->result->status->code;
		if($code!=0){
			//user Available to register(does not exists in lycos.com)
			//echo 'Not a registered user of lycos.com please register';
			echo $lycos_user_exists->result->status->message;
		}else{
			//echo 'User exists in both lyc.so & lycos.com';
			echo $lycos_user_exists->result->status->message;
		}
	}else{
		/*if it comes here then the user does not exists in lyc.so ,
		then register if user does not exists in lycos.com*/
		//$lycos_user_exists = $obj_user->chk_lycos_user($_REQUEST['m_email']);
		if($code==0){//user registered in lycos.com			
			//validating user data
			$valid_user = $obj_user->check_valid_data();
			if(is_array($valid_user)){
				echo json_encode($valid_user);
			}else{
				//adding registered user of lycos.com to lyc.so(register)
				$user_data['fname']     = $_REQUEST["m_fname"];
				$user_data['lname']     = $_REQUEST["m_lname"];
				$user_data['email']     = $_REQUEST["m_email"];
				$user_data['password']  = md5($_REQUEST["m_password"]);
				$user_data['status']    = 'Active';
				$user_data['role']      = 'User';
				$user_data['signature'] = yourls_auth_signature_new_user( $_REQUEST["m_email"]);
				$user_reg = $obj_user->ws_register($user_data);
				if(is_numeric($user_reg) && $user_reg!='' && $user_reg>0) {
					// $sent = $obj_user->send_email($user_reg, $_REQUEST['m_email'], $_REQUEST['m_fname']);
					// if($sent)
					// 	echo 'Success, mail sent to activate the link';
					header('location: user.php');exit;
				}
			}
		}else{//user Unavailable i.e user name exists
			echo 'User does not exists in lyc.so but exists in lycos.com<br>';
			//get the details from lycos.com and insert in lyc.so
			$lycos_user_data = $obj_user->get_lycos_user_data();
			//inserting into lyc.so
			$user_data['fname']     = $lycos_user_data->data->user_data->first_name;
			$user_data['lname']     = $lycos_user_data->data->user_data->last_name;
			$user_data['email']     = $lycos_user_data->data->user_data->email;
			$user_data['password']  = md5($_REQUEST["m_password"]);
			$user_data['status']    = 'Active';
			$user_data['role']      = 'User';
			$user_data['signature'] = yourls_auth_signature_new_user( $_REQUEST["m_email"]);
			//echo '<pre>';print_r($user_data);
			$user_reg = $obj_user->ws_register($user_data);
			if(is_numeric($user_reg) && $user_reg!='' && $user_reg>0) {				
				echo 'Successfully registered redirect to login page';
				header('location: user.php');exit;
			}
			//echo $lycos_user_exists->result->status->message;
		}
	}
	
}else{
	echo 'Invalid arguments';
}
?>