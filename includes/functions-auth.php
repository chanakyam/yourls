<?php
/**
 * Check for valid user via login form or stored cookie. Returns true or an error message
 *
 */
function yourls_is_valid_user() {
	static $valid = false;
	
	if( $valid )
		return true;
		
	// Allow plugins to short-circuit the whole function
	$pre = yourls_apply_filter( 'shunt_is_valid_user', null );
	if ( null !== $pre ) {
		$valid = ( $pre === true ) ;
		return $pre;
	}
	
	// $unfiltered_valid : are credentials valid? Boolean value. It's "unfiltered" to allow plugins to eventually filter it.
	$unfiltered_valid = false;

	// Logout request
	if( isset( $_GET['action'] ) && $_GET['action'] == 'logout' ) {
		yourls_do_action( 'logout' );
		yourls_store_cookie( null );
		//new code for validating username & pwd from db
		 // session_start();
	  //    session_unset();
		session_destroy();
		//return yourls__( 'Logged out successfully' );
		header('location: '.yourls_site_url());exit;
	}
	
	// Check cookies or login request. Login form has precedence.

	yourls_do_action( 'pre_login' );

	// Determine auth method and check credentials
	if
		// API only: Secure (no login or pwd) and time limited token
		// ?timestamp=12345678&signature=md5(totoblah12345678)
		( yourls_is_API() &&
		  isset( $_REQUEST['timestamp'] ) && !empty($_REQUEST['timestamp'] ) &&
		  isset( $_REQUEST['signature'] ) && !empty($_REQUEST['signature'] )
		)
		{
			yourls_do_action( 'pre_login_signature_timestamp' );
			$unfiltered_valid = yourls_check_signature_timestamp();
		}
		
	elseif
		// API only: Secure (no login or pwd)
		// ?signature=md5(totoblah)
		( yourls_is_API() &&
		  !isset( $_REQUEST['timestamp'] ) &&
		  isset( $_REQUEST['signature'] ) && !empty( $_REQUEST['signature'] )
		)
		{
			yourls_do_action( 'pre_login_signature' );
			$unfiltered_valid = yourls_check_signature();
		}
	
	elseif
		// API or normal: login with username & pwd
		( isset( $_REQUEST['username'] ) && isset( $_REQUEST['password'] )
		  && !empty( $_REQUEST['username'] ) && !empty( $_REQUEST['password']  ) )
		{
			yourls_do_action( 'pre_login_username_password' );
			$unfiltered_valid = yourls_check_username_password();
		}
	
	elseif
		// Normal only: cookies
		( !yourls_is_API() && isset( $_COOKIE['yourls_username'] ) )
		{
			yourls_do_action( 'pre_login_cookie' );
			$unfiltered_valid = yourls_check_auth_cookie();
			$unfiltered_valid = true;
		}
	
	// Regardless of validity, allow plugins to filter the boolean and have final word
	$valid = yourls_apply_filter( 'is_valid_user', $unfiltered_valid );

	// Login for the win!
	if ( $valid ) {
		yourls_do_action( 'login' );
		
		// (Re)store encrypted cookie if needed
		if ( !yourls_is_API() ) {
			yourls_store_cookie( YOURLS_USER );

			// Login form : redirect to requested URL to avoid re-submitting the login form on page reload
			if( isset( $_REQUEST['username'] ) && isset( $_REQUEST['password'] ) && isset( $_SERVER['REQUEST_URI'] ) ) {
				$url = $_SERVER['REQUEST_URI'];				
				yourls_redirect( $url );
			}
		}
		
		// Login successful
		return true;
	}
	
	// Login failed
	yourls_do_action( 'login_failed' );

	if ( isset( $_REQUEST['username'] ) || isset( $_REQUEST['password'] ) ) {
		return yourls__( 'Invalid email address or password' );
	} else {
		// return yourls__( 'Please log in' );
		return yourls__( 'Please Sign In' );
	}
}

/**
 * Check auth against list of login=>pwd. Sets user if applicable, returns bool
 *
 */
function yourls_check_username_password() {
	global $yourls_user_passwords;
	global $ydb;
	//new code for validating username & pwd from db	
	$_REQUEST['password'] = md5($_REQUEST['password']);
	$user_results = $ydb->get_results( "SELECT * FROM yourls_users WHERE email='".$_REQUEST['username']."' AND password='".$_REQUEST['password']."' AND status='Active'" );	
	if($user_results[0]->email == $_REQUEST['username'] && $user_results[0]->password == $_REQUEST['password']){
		//assigning username to session
		$_SESSION['username'] = $_REQUEST['username'];
		$_SESSION['role'] = $user_results[0]->role;
		$_SESSION['name'] = $user_results[0]->firstname.' '.$user_results[0]->lastname;
		yourls_set_user( $_REQUEST['username'] );		
		return true;
	}else{
		return false;
	}
	//end	
}

/**
 * Check a submitted password sent in plain text against stored password which can be a salted hash
 *
 */
function yourls_check_password_hash( $user, $submitted_password ) {
	global $yourls_user_passwords;
	
	if( !isset( $yourls_user_passwords[ $user ] ) )
		return false;
	
	if ( yourls_has_phpass_password( $user ) ) {
		// Stored password is hashed with phpass
		list( , $hash ) = explode( ':', $yourls_user_passwords[ $user ] );
		$hash = str_replace( '!', '$', $hash );
		return ( yourls_phpass_check( $submitted_password, $hash ) );
	} else if( yourls_has_md5_password( $user ) ) {
		// Stored password is a salted md5 hash: "md5:<$r = rand(10000,99999)>:<md5($r.'thepassword')>"
		list( , $salt, ) = explode( ':', $yourls_user_passwords[ $user ] );
		return( $yourls_user_passwords[ $user ] == 'md5:'.$salt.':'.md5( $salt . $submitted_password ) );
	} else {
		// Password stored in clear text
		return( $yourls_user_passwords[ $user ] == $submitted_password );
	}
}

/**
 * Overwrite plaintext passwords in config file with phpassed versions.
 *
 * @since 1.7
 * @param string $config_file Full path to file
 * @return true if overwrite was successful, an error message otherwise
 */
function yourls_hash_passwords_now( $config_file ) {
	if( !is_readable( $config_file ) )
		return 'cannot read file'; // not sure that can actually happen...
		
	if( !is_writable( $config_file ) )
		return 'cannot write file';	
	
	// Include file to read value of $yourls_user_passwords
	// Temporary suppress error reporting to avoid notices about redeclared constants
	$errlevel = error_reporting();
	error_reporting( 0 );
	require $config_file;
	error_reporting( $errlevel );
	
	$configdata = file_get_contents( $config_file );
	if( $configdata == false )
		return 'could not read file';

	$to_hash = 0; // keep track of number of passwords that need hashing
	foreach ( $yourls_user_passwords as $user => $password ) {
		if ( !yourls_has_phpass_password( $user ) && !yourls_has_md5_password( $user ) ) {
			$to_hash++;
			$hash = yourls_phpass_hash( $password );
			// PHP would interpret $ as a variable, so replace it in storage.
			$hash = str_replace( '$', '!', $hash );
			$quotes = "'" . '"';
			$pattern = "/[$quotes]${user}[$quotes]\s*=>\s*[$quotes]" . preg_quote( $password, '-' ) . "[$quotes]/";
			$replace = "'$user' => 'phpass:$hash' /* Password encrypted by YOURLS */ ";
			$count = 0;
			$configdata = preg_replace( $pattern, $replace, $configdata, -1, $count );
			// There should be exactly one replacement. Otherwise, fast fail.
			if ( $count != 1 ) {
				yourls_debug_log( "Problem with preg_replace for password hash of user $user" );
				return 'preg_replace problem';
			}
		}
	}
	
	if( $to_hash == 0 )
		return 0; // There was no password to encrypt
	
	$success = file_put_contents( $config_file, $configdata );
	if ( $success === FALSE ) {
		yourls_debug_log( 'Failed writing to ' . $config_file );
		return 'could not write file';
	}
	return true;
}

/**
 * Hash a password using phpass
 *
 * @since 1.7
 * @param string $password password to hash
 * @return string hashed password
 */
function yourls_phpass_hash( $password ) {
	$hasher = yourls_phpass_instance();
	return $hasher->HashPassword( $password );
}

/**
 * Check a clear password against a phpass hash
 *
 * @since 1.7
 * @param string $password clear (eg submitted in a form) password
 * @param string $hash hash supposedly generated by phpass
 * @return bool true if the hash matches the password once hashed by phpass, false otherwise
 */
function yourls_phpass_check( $password, $hash ) {
	$hasher = yourls_phpass_instance();
	return $hasher->CheckPassword( $password, $hash );
}

/**
 * Helper function: create new instance or return existing instance of phpass class
 *
 * @since 1.7
 * @param int $iteration iteration count - 8 is default in phpass
 * @param bool $portable flag to force portable (cross platform and system independant) hashes - false to use whatever the system can do best
 * @return object a PasswordHash instance
 */
function yourls_phpass_instance( $iteration = 8, $portable = false ) {
	$iteration = yourls_apply_filter( 'phpass_new_instance_iteration', $iteration );
	$portable  = yourls_apply_filter( 'phpass_new_instance_portable', $portable );

	if( !class_exists( 'PasswordHash' ) ) {
		require_once( YOURLS_INC.'/phpass/PasswordHash.php' );
	}

	static $instance = false;
	if( $instance == false ) {
		$instance = new PasswordHash( $iteration, $portable );
	}
	
	return $instance;
}


/**
 * Check to see if any passwords are stored as cleartext.
 * 
 * @since 1.7
 * @return bool true if any passwords are cleartext
 */
function yourls_has_cleartext_passwords() {
	global $yourls_user_passwords;
	foreach ( $yourls_user_passwords as $user => $pwdata ) {
		if ( !yourls_has_md5_password( $user ) && !yourls_has_phpass_password( $user ) ) {
			return true;
		}
	}
	return false;
}

/**
 * Check if a user has a hashed password
 *
 * Check if a user password is 'md5:[38 chars]'.
 * TODO: deprecate this when/if we have proper user management with password hashes stored in the DB
 *
 * @since 1.7
 * @param string $user user login
 * @return bool true if password hashed, false otherwise
 */
function yourls_has_md5_password( $user ) {
	global $yourls_user_passwords;
	return(    isset( $yourls_user_passwords[ $user ] )
	        && substr( $yourls_user_passwords[ $user ], 0, 4 ) == 'md5:'
		    && strlen( $yourls_user_passwords[ $user ] ) == 42 // http://www.google.com/search?q=the+answer+to+life+the+universe+and+everything
		   );
}

/**
 * Check if a user's password is hashed with PHPASS.
 *
 * Check if a user password is 'phpass:[lots of chars]'.
 * TODO: deprecate this when/if we have proper user management with password hashes stored in the DB
 *
 * @since 1.7
 * @param string $user user login
 * @return bool true if password hashed with PHPASS, otherwise false
 */
function yourls_has_phpass_password( $user ) {
	global $yourls_user_passwords;
	return( isset( $yourls_user_passwords[ $user ] )
	        && substr( $yourls_user_passwords[ $user ], 0, 7 ) == 'phpass:'
	);
}

/**
 * Check auth against encrypted COOKIE data. Sets user if applicable, returns bool
 *
 */
function yourls_check_auth_cookie() {
	// global $yourls_user_passwords;
	// foreach( $yourls_user_passwords as $valid_user => $valid_password ) {
	// 	if ( yourls_salt( $valid_user ) == $_COOKIE['yourls_username'] ) {
	// 		yourls_set_user( $valid_user );
	// 		return true;
	// 	}
	// }
	// return false;

	//new code for validating username & pwd from db	
	//if(yourls_check_username_password())
		//yourls_set_user( $_COOKIE['yourls_username'] );
		session_start();
		yourls_set_user( $_SESSION['username'] );
		return true;
	//return false;
}

/**
 * Check auth against signature and timestamp. Sets user if applicable, returns bool
 *
 */
function yourls_check_signature_timestamp() {
	// Timestamp in PHP : time()
	// Timestamp in JS: parseInt(new Date().getTime() / 1000)
	global $yourls_user_passwords;
	foreach( $yourls_user_passwords as $valid_user => $valid_password ) {
		if (
			(
				md5( $_REQUEST['timestamp'].yourls_auth_signature( $valid_user ) ) == $_REQUEST['signature']
				or
				md5( yourls_auth_signature( $valid_user ).$_REQUEST['timestamp'] ) == $_REQUEST['signature']
			)
			&&
			yourls_check_timestamp( $_REQUEST['timestamp'] )
			) {
			yourls_set_user( $valid_user );
			return true;
		}
	}
	return false;
}

/**
 * Check auth against signature. Sets user if applicable, returns bool
 *
 */
function yourls_check_signature() {
	global $yourls_user_passwords;
	global $ydb;
	//new code for validating user signature from db	
	//echo "SELECT signature FROM yourls_users WHERE signature='".$_REQUEST['signature']."'  AND active='A' LIMIT 0,1";exit;
	$signature_results = $ydb->get_results( "SELECT signature FROM yourls_users WHERE signature='".$_REQUEST['signature']."'  AND status='Active' LIMIT 0,1" );	
	if($signature_results[0]->signature == $_REQUEST['signature'] ){
		//yourls_set_user( $_REQUEST['username'] );
		return true;
	}else{
		return false;
	}

	/*foreach( $yourls_user_passwords as $valid_user => $valid_password ) {
		if ( yourls_auth_signature( $valid_user ) == $_REQUEST['signature'] ) {
			yourls_set_user( $valid_user );
			return true;
		}
	}
	return false;
	*/
}

/**
 * Generate secret signature hash
 *
 */
function yourls_auth_signature( $username = true ) {
	if( !$username && defined('YOURLS_USER') ) {
		$username = YOURLS_USER;
	}
	return ( $username ? substr( yourls_salt( $username ), 0, 10 ) : 'Cannot generate auth signature: no username' );
}


// Signature for new user for API
function yourls_auth_signature_new_user( $username) {
	return ( $username ? substr( yourls_salt( $username ), 0, 10 ) : 'Cannot generate auth signature: no username' );
}

/**
 * Check if timestamp is not too old
 *
 */
function yourls_check_timestamp( $time ) {
	$now = time();
	// Allow timestamp to be a little in the future or the past -- see Issue 766
	return yourls_apply_filter( 'check_timestamp', abs( $now - $time ) < YOURLS_NONCE_LIFE, $time );
}

/**
 * Store new cookie. No $user will delete the cookie.
 *
 */
function yourls_store_cookie( $user = null ) {
	if( !$user ) {
		$pass = null;
		$time = time() - 3600;
	} else {
		// global $yourls_user_passwords;
		// if( isset($yourls_user_passwords[$user]) ) {
		// 	$pass = $yourls_user_passwords[$user];
		// } else {
		// 	die( 'Stealing cookies?' ); // This should never happen
		// }
		//new code for validating username & pwd from db
		$pass = $_REQUEST['username'];
		$time = time() + YOURLS_COOKIE_LIFE;
	}
	
	$domain   = yourls_apply_filter( 'setcookie_domain',   parse_url( YOURLS_SITE, 1 ) );
	$secure   = yourls_apply_filter( 'setcookie_secure',   yourls_is_ssl() );
	$httponly = yourls_apply_filter( 'setcookie_httponly', true );

	// Some browser refuse to store localhost cookie
	if ( $domain == 'localhost' ) 
		$domain = '';
   
	if ( !headers_sent() ) {
		// Set httponly if the php version is >= 5.2.0
		if( version_compare( phpversion(), '5.2.0', 'ge' ) ) {
			setcookie('yourls_username', yourls_salt( $user ), $time, '/', $domain, $secure, $httponly );
		} else {
			setcookie('yourls_username', yourls_salt( $user ), $time, '/', $domain, $secure );
		}
	} else {
		// For some reason cookies were not stored: action to be able to debug that
		yourls_do_action( 'setcookie_failed', $user );
	}
}

/**
 * Set user name
 *
 */
function yourls_set_user( $user ) {
	if( !defined( 'YOURLS_USER' ) )
		define( 'YOURLS_USER', $user );
}

// function yourls_set_name( $name ) {
// 	if( !defined( 'YOURLS_NAME' ) ){
// 		define( 'YOURLS_NAME', $name );
// 	}
// }

