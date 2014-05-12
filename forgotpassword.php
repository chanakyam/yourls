
<?php
//session_start();

// Start YOURLS engine
require_once ( dirname(__FILE__) .'/includes/load-yourls.php' );

// Change this to match the URL of your public interface. Something like: http://yoursite.com/index.php
$page = YOURLS_SITE . '/index.php';
//$page = YOURLS_SITE . '/sample-public-front-page.php' ;


// Insert <head> markup and all CSS & JS files
yourls_html_head();

?>

<!-- content start -->	
<div class="contentarea homecontent">
	<div class="homeinner">
		

		<div class="signup">
		<?php
			//include_once "config.php";
			//include_once "session.php";
		?>
		
			<script src="<?php yourls_site_url(); ?>/js/jquery-1.8.2.min.js?v=<?php echo YOURLS_VERSION; ?>" type="text/javascript"></script>
			<script type="text/javascript">
			//email validation
			function emailvalidation(){
				var email=document.forms["forgotpassword"]["email"].value;
					if (email==null || email=="")
			  		{
			  			errorMessage("label_email", "(This field is required)");
			  			invalid = true;
			 		}else if(!email.match(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/)){
			 			errorMessage("label_email", "Please enter valid Email");
			 			invalid = true;
			 		}else{
			 			errorMessage("label_email", "");
			 			invalid = false;
			 		}				

			 		if(invalid){
			 			return false;
			 		}else{
			 			return true;
			 		}				

			 }				

			 	//dynamic error message 
			    function errorMessage(label_id, message){
			    	// document.getElementById("label_fusername").innerHTML = "Please enter username";
			    	document.getElementById(label_id).innerHTML = message;
			    }				

			</script>	
			<p class="center"><a href="<?php yourls_site_url(); ?>" title="lyc.so"><img src="images/lycsoLogo.png" alt="lyc.so" title="lyc.so" /></a></p>
			
			<form name="forgotpassword" method="post" action="process.php" onsubmit="return emailvalidation();">
				<div class="title">Forgot Password</div>
				
				<?php if (isset($_REQUEST['status']) && $_REQUEST['status']== 1 ){
				 echo "<div class='success'>Please Check your email to reset your password.</div>";
				 }?>
				 <?php if (isset($_REQUEST['status']) && $_REQUEST['status']== 0 ){
				 echo "<div class='errormessage'>Email not found in our database.</div>";
				 }?>

				<table width="100%">
					<tr>
						<td width="25%">Please Enter your Email id<span class="error_message" >*</span>:</td>
						<td>
							<input type="text" name="email" id="email" class="width90">
							<span class="error_message" id="label_email"></span>
						</td>
					</tr>
					<tr>
						<td></td>
						<td><input type="submit" name="form_type" value="Submit" id="submit"></td>
					</tr>
					
				</table>
			</form>
		
	</div>
	</div>

	<div class="add center">
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
<!--contentarea end-->
<?php

// Display page footer
yourls_html_footer();

?>	

