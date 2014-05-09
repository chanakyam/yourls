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

	//forgotpassword
	public function forgotpassword($email_to=""){

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
		    	$header="from: Admin <admin@xyz.com>"; 
		    	$link ="<a href='".$domain."resetpwd.php?user_id=".base64_encode($user_id)."'>Please Click Here To Reset Your Password.</a>";
		    	$messages= "Hi ".$name.",<br><br>";
		    	//echo $messages=	"Your Password is:".$your_password.".<br><br><br>";
		    	$messages.= $link."<br><br><br>";
		    	$messages.= "Thanks,<br>";
		    	$messages.= "Admin <br>";
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
			$update_query= "UPDATE yourls_users set password= '".$newpassword."' WHERE user_id='".$user_id."'";
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
		
		$query="SELECT user_id, password FROM yourls_users WHERE password='".$oldpassword."'";
		$result=mysql_query($query);
		$count=mysql_num_rows($result);
		if($count==true){
			$rows=mysql_fetch_array($result);
			$user_id=$rows['user_id'];
			echo$update_query= "UPDATE yourls_users set password= '".$newpassword."' WHERE user_id='".$user_id."'";
			$results=mysql_query($update_query);
			
			if($results){
		 		header('location: changepwd.php?status=1');exit;
				//echo "Added Succesfully.";
	    	}else{
	    		header('location: changepwd.php?status=0');exit;
	    		//return -1;
	    	}
		}
	
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
}