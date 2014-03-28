<?php
session_start();
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
require_once( dirname(__FILE__).'/includes/captcha.php' );

// Change this to match the URL of your public interface. Something like: http://yoursite.com/index.php
$page = YOURLS_SITE . '/index.php';
//$page = YOURLS_SITE . '/sample-public-front-page.php' ;

// Part to be executed if FORM has been submitted
if ( isset( $_REQUEST['url'] ) && $_REQUEST['url'] != 'http://' ) {
	// Get parameters -- they will all be sanitized in yourls_add_new_link()
	$url     = $_REQUEST['url'];
	$keyword = isset( $_REQUEST['keyword'] ) ? $_REQUEST['keyword'] : '' ;
	$title   = isset( $_REQUEST['title'] ) ?  $_REQUEST['title'] : '' ;
	$text    = isset( $_REQUEST['text'] ) ?  $_REQUEST['text'] : '' ;

	// Create short URL, receive array $return with various information
	$return  = yourls_add_new_link( $url, $keyword, $title );
	//echo '<pre>';print_r($return);exit();
	
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
}else{
	$cap = simple_php_captcha();
	//echo '<pre>';print_r($cap);
	$captcha = $_SESSION['captcha'] = $cap['code'];
	$image_src = $_SESSION['image_src'] = $cap['image_src'];
}

// Insert <head> markup and all CSS & JS files
yourls_html_head();

// Display title
//echo "<h1>YOURLS - Your Own URL Shortener</h1>\n";

// Display left hand menu
//yourls_html_menu() ;

?>

<div class="contentarea">
	<div class="ltpannel">
		<div class="content">
			<p><img src="images/lycsoLogo.png" align="absmiddle" /></p>
			<?php
			if ( isset( $_REQUEST['url'] ) && $_REQUEST['url'] != 'http://' ) {
				// Display result message of short link creation
				($status == 'success')?$class = 'success':$class = 'warning';
				if( isset( $message ) ) {					
					echo "<h2 class='".$class."'><span>$message</span></h2>";
				}
				
				if( $status == 'success' ) {
					// Include the Copy box and the Quick Share box
					yourls_share_box( $url, $shorturl, $title, $text );
					
					// Initialize clipboard -- requires js/share.js and js/jquery.zclip.min.js to be properly loaded in the <head>
					echo "<script>init_clipboard();</script>\n";
				}
				echo "<span class='clear'></span><div class='left margin20_L margin20_T'><a href='/'><input type='submit' class='btn' value='Create More'></a></div>";

			}else{
			?>
			<form method="post" action="">
			<p class="margin20_T">
			<input type="text" name="url" placeholder="Paste long URL here" class="span6"/>
			<input type="submit" class="btn" value="Shorten"/></p>
			<div id="status-message"></div>
            <div id="sample-captcha"></div>
			</form>	
			<?php }?>
		</div>
	</div>	
	<div class="rtpannel">
		<div class="module">
		<p>
		<span class="left">
		<script type="text/javascript">
			if (!window.OX_ads) { OX_ads = []; }
			OX_ads.push({ "auid" : "556161" });
		</script>
		<script type="text/javascript">
			document.write('<scr'+'ipt src="http://ox-d.lycos.com/w/1.0/jstag"><\/scr'+'ipt>');
		</script>
		<noscript>
			<iframe id="5332eeccbb979" name="5332eeccbb979" src="http://ox-d.lycos.com/w/1.0/afr?auid=556161&cb=INSERT_RANDOM_NUMBER_HERE" frameborder="0" scrolling="no" width="300" height="600">
				<a href="http://ox-d.lycos.com/w/1.0/rc?cs=5332eeccbb979&cb=INSERT_RANDOM_NUMBER_HERE" >
					<img src="http://ox-d.lycos.com/w/1.0/ai?auid=556161&cs=5332eeccbb979&cb=INSERT_RANDOM_NUMBER_HERE" border="0" alt="">
				</a>
			</iframe>
		</noscript>
		</span> 
		</div>
	</div>    
</div>
<!--contentarea end-->
<?php

// Display page footer
yourls_html_footer();	
