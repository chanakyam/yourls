<?php
//include_once "header.php";
//include_once "config.php";
//include_once "session.php";
?>
<?php

// Start YOURLS engine
require_once( dirname(__FILE__).'/includes/load-yourls.php' );

// Change this to match the URL of your public interface. Something like: http://yoursite.com/index.php
$page = YOURLS_SITE . '/index.php';
//$page = YOURLS_SITE . '/sample-public-front-page.php' ;


// Insert <head> markup and all CSS & JS files
yourls_html_head();

?>
	
			<script type="text/javascript">		

				function validatePassword(){		
					//validate new password
			    	if(document.forms["resetpwd"]["newpwd"]){
			   			var newpwd=document.forms["resetpwd"]["newpwd"].value;
			  			if (newpwd==null || newpwd==""){
			    			errorMessage("label_newpwd", "(This field is required)");
			    			invalid = true;
			   			}else{
			   			errorMessage("label_newpwd", "");
			   			}
			    	}			

			 		//confirm password validation (cnew)
			    	if(document.forms["resetpwd"]["cnew"]){			

			      		var cnew=document.forms["resetpwd"]["cnew"].value;
			      		if (cnew != newpwd){
			          		errorMessage("label_cnew", "(Password not matching)");
			          		invalid = true;
			      		}else{
			        		errorMessage("label_cnew", "");
			      		}
			    	}			

			    	if(invalid){
			 			return false;
			 		}
				}			

				function errorMessage(label_id, message){
			    	// document.getElementById("label_fusername").innerHTML = "Please enter username";
			    	document.getElementById(label_id).innerHTML = message;			

			    }	
			
			</script>
		</head>
		<!-- Required CSS -->	
		<div class="contentarea">
				

			<div  class="signup">
			

				<form name="resetpwd" method="post" action="process.php" onsubmit="return validatePassword();" >
				<div class="title">Reset Password</div>
						<?php 
							if (isset($_REQUEST['status']) && $_REQUEST['status']== 1 ){
 	 			       		echo "<center><strong>Password resetted succesfully.</strong></center>";
 	 			       		}
 	 			       	?>
	
				 		<?php 
				 			if (isset($_REQUEST['status']) && $_REQUEST['status']== 0 ){
 	 			       		echo "<center><strong>Password resetting failed.</strong></center>";
 	 			       		}
 	 			       	?>

						<table>
							<tr>
								<td>New Password<span class="error_message" >*</span>:</td>
								<td>
									<input type="password" id="newpwd" name="newpwd">
									<span class="error_message" id="label_newpwd"></span>
								</td>
							</tr>

							<tr>
								<td>Confirm New Password<span class="error_message" >*</span>:</td>
								<td>
									<input type="password" id="cnew" name="cnew">
									<span class="error_message" id="label_cnew"></span>
								</td>
							</tr>

							<tr>
								<td></td>
								<td>
									<input type="hidden" id="user_id" name="user_id" value=<?php echo $_GET['user_id'] ?>/>
									<input type="submit" name="form_type" value="Reset">
								</td>

										

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

<?php

// Display page footer
yourls_html_footer();


?>	