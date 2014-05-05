<?php
session_start();	
define( 'YOURLS_USER', true );	
// Start YOURLS engine
require_once( dirname(__FILE__).'/includes/load-yourls.php' );	
/* this file is to process submitted data */
include_once "user.class.php";	
$obj_user = new user();
//var_dump($_POST);

// if request comes from registration form
if(isset($_REQUEST["form_type"]) && $_REQUEST["form_type"]=="Signup"){
	// prepare data array
	$data['firstname'] 	  = $_POST['firstname'];
 	$data['lastname']	  = $_POST['lastname'];
 	$data['email'] 		  = $_POST['email'];
    $data['password'] 	  = $_POST['password'];
	$data['signature']	  = yourls_auth_signature_new_user( $data['email']);
							
	 
	
	$response        	  = $obj_user-> signup($data);
	if($response){
		$obj_user->send_email($response, $data['email'], $data['firstname']);
	}else{
		// already exist
		header('location: register.php?status=2');exit;
	}	
	// echo $message;
	// return $response;
	
	//edited

	// if($response){
		
	// 	if($obj_user->send_email($response, $data['email'], $data['firstname'])){
	// 		header('location: register.php?status=1');exit;
	// 		//echo "Activated Succesfully.";
	// 	}else{
	// 		header('location: register.php?status=0');exit;
	// 		//echo "Failed to activate.";
	// 	}
	// }else{
	// 	// already exist
	// 	$message = "User Already Exist";
	// }	
	// echo $message;
	// return $response;
}	

// authenticate
if(isset($_REQUEST["form_type"]) && $_REQUEST["form_type"]=="authenticate"){
	$user_id = base64_decode($_REQUEST["id"]);
	if($user_id){
		$response = $obj_user-> useractive($user_id);
		if($response){
			header('location: user.php?status=1');exit;
			//echo "Activated Succesfully.";
		}else{
			header('location: user.php?status=0');exit;
			//echo "Failed to activate.";
		}
		
	}
	
}	

//if request comes from admin user to add_user
if(isset($_REQUEST["form_type"]) && $_REQUEST["form_type"]=="Add"){	
	// prepare user array
	$user['firstname'] 	  = $_POST['firstname'];
 	$user['lastname']	  = $_POST['lastname'];
 	$user['email'] 		  = $_POST['email'];
    $user['password'] 	  = $_POST['password'];
	$user['signature']	  = yourls_auth_signature_new_user( $user['email']);
							
	$response        	  = $obj_user-> Add($user);
}	

// if request comes from login form
//print_r($_POST);
// if(isset($_POST['form_type']) && $_POST['form_type']=="Login"){
// 	$email=$_POST['email'];
//  	$password=md5($_POST['password']);
// 	$response = $obj_user->login($email, $password);
// }	

//Edit
if(isset($_REQUEST["form_type"]) && $_REQUEST["form_type"]=="Update"){
	$response = $obj_user->showuser();
	if($response){
		header('location: myprofile.php?status=1');exit;
			//echo "Updated Succesfully!!";
		}else{
			header('location: myprofile.php?status=0');exit;
			//echo "Updation Failed!!";
		}
}	

// if request comes from logout
if(isset($_REQUEST["form_type"]) && $_REQUEST["form_type"]=="Logout"){	
	$response = $obj_user->logout();
}	

// if request comes from forgot password
if(isset($_POST["form_type"]) && $_POST["form_type"]=="Submit"){
$response = $obj_user-> forgotpassword();	
}	

//if request comes from resetpwd.php(through email)
if(isset($_POST["form_type"]) && $_POST["form_type"]=="Reset"){
	$response = $obj_user-> resetpassword();
}	

//if request comes from changepwd.php
if(isset($_POST["form_type"]) && $_POST["form_type"]=="Change"){
	if(isset($_POST["form_type"])){
		$response = $obj_user-> changepassword(md5($_POST['password']), md5($_POST['newpwd']));
	}
}

?>


