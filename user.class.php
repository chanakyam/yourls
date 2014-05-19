<?php
require_once( dirname(__FILE__).'/includes/load-yourls.php' );

	$gaSql= array();
	$gaSql['user']       = YOURLS_DB_USER;
	$gaSql['password']   = YOURLS_DB_PASS;
	$gaSql['db']         = YOURLS_DB_NAME;
	$gaSql['server']     = YOURLS_DB_HOST;
	
	/* 
	 * MySQL connection
	 */
	if ( ! $gaSql['link'] = mysql_pconnect( $gaSql['server'], $gaSql['user'], $gaSql['password']  ) )
	{
		fatal_error( 'Could not open connection to server' );
	}

	if ( ! mysql_select_db( $gaSql['db'], $gaSql['link'] ) )
	{
		fatal_error( 'Could not select database ' );
	}



class user{
	// variables

	private $user_id;
	private $email;
	private $password;
	
	
	// constructor
	public function __construct(){
		// auto load

	}

	// register
	public function signup($data){

		//$domain     = yourls_site_url();
		$firstname    = $data["firstname"];
		$lastname     = $data["lastname"];
		$email        = $data["email"];
		$password     = $data["password"];
		$md5		  = md5($password);
		$status		  = "inactive";
		$role		  = "User";
		$signature	  = $data["signature"];

		// If user already exist skip registration.
		$q1 = "SELECT user_id from yourls_users WHERE email = '".$email."' ";
		$sql = mysql_query($q1);
	
		if (mysql_num_rows($sql) == 0){
			// insert query
			$query="INSERT INTO yourls_users (firstname, lastname, email, password, status, signature, role) VALUES ('".$firstname."','".$lastname."','".$email."','".$md5."','".$status."','".$signature."','".$role."')";
			$result=mysql_query($query) ;
			$user_id= mysql_insert_id();		 	
			if($result){
				if($user_id){
				return $user_id;
	    		}
	    	}else{
	    		return -1;
	    	}
			
		}else{
			return FALSE;
		}

	}
	
	//send email to user activate account
	public function send_email($user_id, $email, $firstname){
		// send email to validate email
		$domain = '';
		$domain = $_SERVER['SERVER_NAME'];
 	    $to=$email;
		$name=$firstname; 
    	$subject="Activate your account";
    	$header = "Content-Type: text/html; charset=ISO-8859-1\r\n"; 
    	$header.="from: Admin <noreply-lycso@lycos-inc.com>"; 
    	$link ="<a href='http://".$domain."/process.php?form_type=authenticate&id=".base64_encode($user_id)."'>Please Click Here To Activate Your Account.</a>";
    	$messages = "Hi ".$name.",<br><br>";
    	$messages .= $link."<br><br><br>";
    	$messages .= "Thanks,<br>";
    	$messages .= "Admin <br>";
    	$messages .= "lyc.so <br>";
    	// send email 
    	$sentmail = mail($to,$subject,$messages,$header);
	}

	//useractive
	public function useractive($user_id){
		//checking if already activated
		$chk_act_qry = "SELECT user_id FROM yourls_users WHERE user_id='".$user_id."' AND status='Active'";
		$act_res=mysql_query($chk_act_qry);
		if(mysql_num_rows($act_res)==0){
			// $status="active";
			$update_query="UPDATE yourls_users SET status='Active' WHERE user_id='".$user_id."'" ;
			$result=mysql_query($update_query);
			return true;
		}else{
			return false;
		}
	}

		// register
	public function Add($user){

		//$domain     = yourls_site_url();

		$firstname    = $user["firstname"];
		$lastname     = $user["lastname"];
		$email        = $user["email"];
		$password     = $user["password"];
		$md5		  = md5($password);
		$status		  = "active";
		$role		  = "Admin_User";
		$signature	  = $user["signature"];

		// If user already exist skip registration.
		$q1 = "SELECT user_id from yourls_users WHERE email = '".$email."' ";
		$sql = mysql_query($q1);
	
		if (mysql_num_rows($sql) == 0){
			// insert query
			$query="INSERT INTO yourls_users (firstname, lastname, email, password, status, signature, role) VALUES ('".$firstname."','".$lastname."','".$email."','".$md5."','".$status."','".$signature."','".$role."')";
			$result=mysql_query($query);
			if($result)
				return true;
			else
				return false;		 	
		}else{
			return FALSE;
		}

	}

	// login
	// public function login($email,$password)
	// {
	// 	// Here login code
 			
 // 			$query= "SELECT user_id, email FROM yourls_users WHERE email='".$email."' and password='".$password."' and status='Active'";
 // 			//print_r($query);
 // 			$result=mysql_query($query);
 // 			$rows=mysql_num_rows($result);
 // 			//$data = mysql_fetch_assoc($result);
 // 			//print_r($rows);
 // 			if($rows==1 ){
	//  			$_SESSION['signup']=$data['email'];
	//  			$_SESSION['user_id']=$data['user_id'];
	//  			header('location: /user.php');
	// 		}else{
	// 			header('location: user.php?status=2');exit;
	// 		}	
	// }

	// logout
	public function logout(){
		// Here logout code
		$_SESSION['Login'] = FALSE;
		header('location: login.php');
		session_destroy();
	}

	public function getUserDetails(){
		$query= "SELECT * FROM yourls_users WHERE email='".$_SESSION['username']."'";
		$result=mysql_query($query);
		$data = mysql_fetch_assoc($result);
		return $data;
	}
	//new function for inserting userid through api call
	public function getUserDetailsThruSignature(){
		$query  = "SELECT user_id FROM yourls_users WHERE signature='".$_REQUEST['signature']."'";
		$result = mysql_query($query);
		$data   = mysql_fetch_assoc($result);
		return $data;
	}

	//forgotpassword
	public function forgotpassword($email_to=""){
			$domain = '';
			$domain = $_SERVER['SERVER_NAME'];
			$email_to=$_POST['email'];
			$query="SELECT user_id, password, firstname FROM yourls_users WHERE email='".$email_to."'";
			$result=mysql_query($query);
			$count=mysql_num_rows($result);

	 		if($count==1){
				$rows=mysql_fetch_array($result);
				$your_password=$rows['password'];
				$name=$rows['firstname'];
				$user_id=$rows['user_id'];
				$to=$email_to; 
		    	$subject="Reset Password";
		    	$header = "Content-Type: text/html; charset=ISO-8859-1\r\n"; 
    			$header.="from: Admin <noreply-lycso@lycos-inc.com>";  
		    	$link ="<a href='".$domain."/resetpwd.php?user_id=".base64_encode($user_id)."'>Please Click Here To Reset Your Password.</a>";
		    	$messages= "Hi ".$name.",<br><br>";
		    	//echo $messages=	"Your Password is:".$your_password.".<br><br><br>";
		    	$messages.= $link."<br><br><br>";
		    	$messages.= "Thanks,<br>";
		    	$messages.= "Admin <br>";
		    	$messages.= "lyc.so <br>";
		    	// send email 
		    	$sentmail = mail($to,$subject,$messages,$header);
		    	
		    	header('location: forgotpassword.php?status=1');exit;

	    	}else {
	    		header('location: forgotpassword.php?status=0');exit;
	    		//echo "Not found your email in our database";
	   		}
	}

	// destructor
	public function __destruct(){
		
	}

//reset password
	public function resetpassword($newpassword=""){
			$newpassword=$_POST['newpwd'];
			$user_id = base64_decode($_POST['user_id']);
			$update_query= "UPDATE yourls_users set password= '".md5($newpassword)."' WHERE user_id='".$user_id."'";
			$result=mysql_query($update_query);
			
			if($result){
		 		header('location: resetpwd.php?status=1');exit;
				//echo "Added Succesfully.";
	    	}else{
	    		header('location: resetpwd.php?status=0');exit;
	    		//return -1;
	    	}
	}

	//change password
	public function changepassword($oldpassword,$newpassword){		
		$query="SELECT user_id, password FROM yourls_users WHERE password='".$oldpassword."' and email='".$_SESSION['username']."'";
		$result=mysql_query($query);
		$count=mysql_num_rows($result);
		if($count==true){
			$rows=mysql_fetch_array($result);
			$user_id=$rows['user_id'];
			$update_query= "UPDATE yourls_users set password= '".$newpassword."' WHERE user_id='".$user_id."'";
			$results=mysql_query($update_query);
			return true;
		}
		return false;
	}

	//ajax showUser with class showuser for editprofile.php
	public function showuser(){
		$user_name	  = $_SESSION['username'];
		$firstname    = $_POST['firstname'];
		$lastname     = $_POST['lastname'];
		$email        = $_POST['email'];
		//$password     = $_POST['password'];
		//$status		  = "active";

		$query = "UPDATE yourls_users SET firstname= '".$firstname."', lastname ='".$lastname."', email ='".$email."' WHERE email = '".$user_name."'";
		$result = mysql_query($query);
		return $result;
	}

	//getting user signature
	public function get_user_signature(){
		$query  = "SELECT signature FROM yourls_users WHERE email='".$_SESSION['username']."'";
		$result = mysql_query($query);
		$data   = mysql_fetch_assoc($result);
		return $data['signature'];
	}

	
	//generating random string
	function generateRandomString($length = 10) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, strlen($characters) - 1)];
	    }
	    return $randomString;
	}
	
	//updating the suer signature
	public function update_user_signature($signature){
		$update_query="UPDATE yourls_users SET signature='".$signature."' WHERE email='".$_SESSION['username']."'" ;
		$result=mysql_query($update_query);
		return true;

	}

	/* functions related to webservices */

	//checking user through  webservice
	public function chk_user_exists($username){
		$user_chk_qry = "select user_id from yourls_users where email='".$username."'";
		$result = mysql_query($user_chk_qry);
		if(mysql_num_rows($result) ==0){
			return false;
		}else{
			return true;
		}
	}

	//checking user through  webservice
	public function chk_user_with_email_pwd($username,$pwd){
		$user_chk_qry = "select user_id from yourls_users where email='".$username."' and password='".$password."'";
		$result = mysql_query($user_chk_qry);
		if(mysql_num_rows($result) ==0){
			return false;
		}else{
			return true;
		}
	}

	//checking user of lycos.com through webservice
	public function chk_lycos_user($email){
		$ws_call = "https://registration.lycos.com/usernameassistant.php?validate=1&m_U=".$email;
		$available = file_get_contents($ws_call);
		return $available;
	}

	//checking user of lycos.com through webservice
	public function chk_lycos_user_with_email_pwd($email,$pwd){
		$ws_call = "https://registration.lycos.com/usernameassistant.php?validate=1&m_U=".$email."&m_AID=".$pwd;
		$available = file_get_contents($ws_call);
		return $available;
	}

	// user register through webservice
	public function ws_register(){
		$firstname    = $_REQUEST["u_fname"];
		$lastname     = $_REQUEST["u_lname"];
		$email        = $_REQUEST["u_email"];
		$password     = $_REQUEST["u_password"];
		$md5		  = md5($password);
		$status		  = "inactive";
		$role		  = "User";
		$signature	  = yourls_auth_signature_new_user( $_REQUEST["u_email"]);		
		// insert query
		$query   = "INSERT INTO yourls_users (firstname, lastname, email, password, status, signature, role) VALUES ('".$firstname."','".$lastname."','".$email."','".$md5."','".$status."','".$signature."','".$role."')";
		$result  = mysql_query($query);
		$user_id = mysql_insert_id();		
		if(is_numeric($user_id) && $user_id!='' && $user_id>0){
			return $user_id;
		}else{
			return -1;
		}

	}

	//validating data
	public function check_valid_data(){
		$err_msg = array();
		//checking empty fname
		if(empty($_REQUEST['m_fname']))
			$err_msg['fname_empty'] ='First name cant not be empty';		

		//checking first name (min(2) & max length(64))
		if(strlen($_REQUEST['m_fname'])<2 && strlen($_REQUEST['m_fname'])>64)
			$err_msg['fname_min_max'] = 'First name should be minimum of 2 and maxjmum of 64 characters';

		//checking empty for lname
		if(empty($_REQUEST['m_lname']))
			$err_msg['lname_empty'] ='Last name cant not be empty';

		//checking last name (min(2) & max length(64))
		if(strlen($_REQUEST['m_lname'])<2 && strlen($_REQUEST['m_lname'])>64)
			$err_msg['lname_min_max'] = 'Last name should be of min of 2 and max of 64';

		//validating email address
		if (!filter_var($_REQUEST['m_email'], FILTER_VALIDATE_EMAIL)) {
			$err_msg['email_error'] = 'Not a valid email address';
		}else{
			//checking email address min max
			if(strlen($_REQUEST['m_email'])<7 && strlen($_REQUEST['m_email'])>128)
				$err_msg['email_min_max'] = 'Email address should be minimun of 7 and maximum of 128 characters';

		}
		//validating password
		//checking empty
		if(empty($_REQUEST['m_password'])){
			$err_msg['pwd_empty'] = 'Password can not be empty';
		}else{
			if (!preg_match("/[0-9a-zA-Z_.!@#$%^()+]/", $_REQUEST['m_password'])) {
				$err_msg['pwd_empty'] = 'Invalid password';
			}
		}

		if(empty($err_msg))
			return true;
		else
			return $err_msg;

	}
}