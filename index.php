<?php
session_start();

$_GLOBAL_MSG = '';

if ( isset($_REQUEST['css_type']) && $_REQUEST['css_type'] === '1' ) {
	$_FORM_TYPE = 1;// Vertical
} else {
	$_FORM_TYPE = 0;// Horizontal
}

// The fact we're not using the default visualCaptcha's fieldname is just to show part of visualCaptcha's flexibility
//$_FIELD_NAME = isset($_SESSION['visualCaptcha-fieldName']) ? $_SESSION['visualCaptcha-fieldName'] : uniqid();
require_once( dirname(__FILE__).'/includes/recaptchalib.php' );
//if ( isset($_REQUEST['form_submit']) && $_REQUEST['form_submit'] === '1' ) {
if ( isset( $_REQUEST['url'] ) && $_REQUEST['url'] != 'http://' ) {
	//recaptcha code
	 $privatekey = "6LfQBPISAAAAAP5N53TlNuTk-VrVrNwLA7UjpQAK";
	 $resp = recaptcha_check_answer ($privatekey,
	                                 $_SERVER["REMOTE_ADDR"],
	                                 $_POST["recaptcha_challenge_field"],
	                                 $_POST["recaptcha_response_field"]);
	 if (!$resp->is_valid) {
	   // What happens when the CAPTCHA was entered incorrectly	   
	   $_GLOBAL_MSG = "The CAPTCHA wasn't entered correctly. Try it again.";
	 }else{$_GLOBAL_MSG = '';} 
	/*if ( ! validCaptcha('frm_sample', $_FORM_TYPE, $_FIELD_NAME) ) {
		$_GLOBAL_MSG = 'Captcha error!';
	} else {
		//$_GLOBAL_MSG = 'Captcha valid!';
		$_GLOBAL_MSG = '';
	}*/

	// Generate a new fieldName
	//$_FIELD_NAME = uniqid();
}

//$_SESSION['visualCaptcha-fieldName'] = $_FIELD_NAME;

?>
<?php
//session_start();
/*
 * This is an example file for a public interface and a bookmarklet. It
 * is provided so you can build from it and customize to suit your needs.
 * It's not really part of the project. Don't submit feature requests 
 * about this file. It's _your_ job to make it what you need it to be :)
 *
 * Rename to .php
 *
 */

// Start YOURLS engine
require_once( dirname(__FILE__).'/includes/load-yourls.php' );

// Change this to match the URL of your public interface. Something like: http://yoursite.com/index.php
$page = YOURLS_SITE . '/index.php';
//$page = YOURLS_SITE . '/sample-public-front-page.php' ;

// Part to be executed if FORM has been submitted
//if ( isset( $_REQUEST['url'] ) && $_REQUEST['url'] != 'http://' ) {
if ( isset( $_REQUEST['url'] ) && $_REQUEST['url'] != 'http://' && $_GLOBAL_MSG=='') {
	//echo '<pre>';print_r($_REQUEST);exit;
	// Get parameters -- they will all be sanitized in yourls_add_new_link()
	$url     = $_REQUEST['url'];
	$keyword = isset( $_REQUEST['keyword'] ) ? $_REQUEST['keyword'] : '' ;
	$title   = isset( $_REQUEST['title'] ) ?  $_REQUEST['title'] : '' ;
	$text    = isset( $_REQUEST['text'] ) ?  $_REQUEST['text'] : '' ;

	// Create short URL, receive array $return with various information
	$return  = yourls_add_new_link( $url, $keyword, $title );
	
	$shorturl = isset( $return['shorturl'] ) ? $return['shorturl'] : '';
	$message  = isset( $return['message'] ) ? $return['message'] : '';
	$title    = isset( $return['title'] ) ? $return['title'] : '';
	$status   = isset( $return['status'] ) ? $return['status'] : '';
	
	// Stop here if bookmarklet with a JSON callback function ("instant" bookmarklets)
	if( isset( $_GET['jsonp'] ) && $_GET['jsonp'] == 'yourls' ) {
		$short = $return['shorturl'] ? $return['shorturl'] : '';
		$message = "Short URL (Ctrl+C to copy)";
		header('Content-type: application/json');
		echo yourls_apply_filter( 'bookmarklet_jsonp', "yourls_callback({'short_url':'$short','message':'$message'});" );
		
		die();
	}
}
// Insert <head> markup and all CSS & JS files
yourls_html_head();

// Display title
//echo "<h1>YOURLS - Your Own URL Shortener</h1>\n";

// Display left hand menu
//yourls_html_menu() ;

?>
<!-- Required CSS -->
<!-- <link rel="stylesheet" href="inc/visualcaptcha.css" type="text/css" media="all" />	
<link rel="stylesheet" href="sample.css" media="all" type="text/css" /> -->


<div class="contentarea">
	<div class="ltpannel center">
		<div class="moduler center">
				<div class="add">
					<!-- new code -->
					<script type="text/javascript">
						if (!window.OX_ads)
						{ OX_ads = []; }
						OX_ads.push(
						{ "auid" : "537094873" }
						);
					</script>
					<script type="text/javascript">
						document.write('<scr'+'ipt src="http://ox-d.lycos.com/w/1.0/jstag"><\/scr'+'ipt>');
					</script>
					<noscript><iframe id="14f66a6be9" name="14f66a6be9" src="http://ox-d.lycos.com/w/1.0/afr?auid=537094873&cb=INSERT_RANDOM_NUMBER_HERE"><a href="http://ox-d.lycos.com/w/1.0/rc?cs=14f66a6be9&cb=INSERT_RANDOM_NUMBER_HERE" ><img src="http://ox-d.lycos.com/w/1.0/ai?auid=537094873&cs=14f66a6be9&cb=INSERT_RANDOM_NUMBER_HERE" border="0" alt="Add Banner" class="banner"></a></iframe></noscript>
					<!-- end -->
				</div>
			</div>

		
			<p><img src="images/lycsoLogo.png" alt="lyc.so" /></p>
			<?php
			//if ( isset( $_REQUEST['url'] ) && $_REQUEST['url'] != 'http://' ) {
			if ( isset( $_REQUEST['url'] ) && $_REQUEST['url'] != 'http://' && $_GLOBAL_MSG=='') {
				// Display result message of short link creation
				($status == 'success')?$class = 'success':$class = 'warning';
				if( isset( $message ) && $class=='success') {					
					echo "<div class='success'>Long URL <span>$message</span></div>";
				}
				if( isset( $message ) && $class=='warning') {					
					echo "<div class='success'>Long URL <span>$url</span></div>";
				}
				if( isset($shorturl) && $shorturl!=''){
					echo "<div class='success'>Shorten URL <span>$shorturl</span></div>";
				}
				
				
				if( $status == 'success' ) {
					// Include the Copy box and the Quick Share box
					yourls_share_box( $url, $shorturl, $title, $text );
					
					// Initialize clipboard -- requires js/share.js and js/jquery.zclip.min.js to be properly loaded in the <head>
					echo "<script>init_clipboard();</script>\n";
				}
				echo "<span class='clear'></span><div><a href='/'><input type='submit' class='btn' value='Create More'></a></div>";

			}else{
			?>
			<?php
			if ( ! empty($_GLOBAL_MSG) ) {
			?>
				<div class="warning"><span><?php echo $_GLOBAL_MSG; ?></span></div>
			<?php
			}
			?>
			<script type="text/javascript">
			 var RecaptchaOptions = {
			    theme : 'blackglass'
			 };
			</script>			
			<form name="frm_sample" id="frm_sample" method="post" action="">				
				<div class="margin20_T">
					<label class="strong">Paste long URL here</label>
					<input type="text" name="url" class="span6 margin5_L"/>
					<input type="submit" name="submit-bt" class="btn" value="Shorten"/>
					<?php //printCaptcha( 'frm_sample', $_FORM_TYPE, $_FIELD_NAME ); ?>					
					<?php
				     $publickey = "6LfQBPISAAAAAJ0d8mY53fRkGl1fpZCymvgnJ5Vg"; // you got this from the signup page
				     echo recaptcha_get_html($publickey);
				    ?>	
				    <p><strong>Please enter the captcha <span class="red">*</span></strong></p>		
				</div>			
			</form>	
			<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
			<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
			<script src="inc/visualcaptcha.js"></script>
			<?php }?>
		
			
	</div>	


	
	
	   
</div>
<!--contentarea end-->
<?php

// Display page footer
yourls_html_footer();

// These functions aren't needed, but we recommend you to use them (or similar), so you can start/get multiple captcha instances with two simple functions.

function printCaptcha( $formId = NULL, $type = NULL, $fieldName = NULL, $accessibilityFieldName = NULL ) {
	require_once( 'inc/visualcaptcha.class.php' );	
	$visualCaptcha = new \visualCaptcha\Captcha( $formId, $type, $fieldName, $accessibilityFieldName );
	$visualCaptcha->show();
}

function validCaptcha( $formId = NULL, $type = NULL, $fieldName = NULL, $accessibilityFieldName = NULL ) {
	require_once( 'inc/visualcaptcha.class.php' );
	$visualCaptcha = new \visualCaptcha\Captcha( $formId, $type, $fieldName, $accessibilityFieldName );
	return $visualCaptcha->isValid();
}

?>	

