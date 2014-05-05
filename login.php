
<?php
session_start();

// Start YOURLS engine
require_once ( dirname(__FILE__) .'/includes/load-yourls.php' );

// Insert <head> markup and all CSS & JS files
yourls_html_head();

?>

<!-- Required CSS -->	
<div class="contentarea">
	
	
				<script src="<?php yourls_site_url(); ?>/js/jquery-1.8.2.min.js?v=<?php echo YOURLS_VERSION; ?>" type="text/javascript"></script>
				<script type="text/javascript">
				//email validation
				function loginvalidation(){

					var email=document.forms["login_form"]["email"].value;
						if (email==null || email=="")
				  		{
				  			errorMessage("label_email", "(This field is required)");
				  			invalid = true;
				 		}else if(!email.match(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/)){
				 			errorMessage("label_email", "Please enter valid Email");
				 			invalid = true;
				 		}else{
				 			errorMessage("label_email", "");
				 		}					

				 	
				 	//validate password
					    if(document.forms["login_form"]["password"]){
					   	var password=document.forms["login_form"]["password"].value;
					  		if (password==null || password==""){
					    			errorMessage("label_password", "(This field is required)");
					    			invalid = true;
					   		}else{
					   			errorMessage("label_password", "");
					   		}
					    }

					    if(invalid){
				 			return false;
				 		}					

				}				

			 	//dynamic error message 
			    function errorMessage(label_id, message){
			    	// document.getElementById("label_fusername").innerHTML = "Please enter username";
			    	document.getElementById(label_id).innerHTML = message;
			    }				

			</script>
				
			<!--<script type="text/javascript" src="http://code.jquery.com/jquery-1.10.2.js"></script>-->
				<script type="text/javascript">
				/*$(document).ready(function() {
			            $('#login').click(function() {
			                $.ajax({
			                    type: "POST",
			                  	url: 'process.php',
			                  	datatype: 'Json',
			                    data:{'email' : $('#email').val(), 'password' : $('#password').val(), 'form_type' : $('#login').val()},
			                    success : function (data){
								
								}
		    					
			                });
			                
			            return false;
			            });
			    	});*/
				</script>
			
			<div class="signup">
				<form name="login_form" method="post" action="process.php" onsubmit="return loginvalidation();">
				
					<div class="title">Login Here</div>
					<table border="0" cellpadding="3" cellspacing="0" width="100%">
						
						<tr>
							<td width="20%">Email<span class="error_message" >*</span>:</td>
							<td>
								<input type="text" id="email" name="email">
								
							
						    	<span class="error_message" id="label_email"></span>	
							</td>
						</tr>
						<tr>
							<td>Password<span class="error_message" >*</span>:</td>
							<td>
								<input type="password" id="password" name="password">
							
								<span class="error_message" id="label_password"></span>	
							</td>
						</tr>
						<tr>
							<td></td>
							<td>
								<input type="submit" name="form_type" value="Login" id="login" class="login">
								<input type="reset"  name="form_type" value="Reset" id="cancel">
							</td>
						</tr>
						<tr></tr>
						<tr>
							<td></td>
							<td><a href="forgotpassword.php" name="forgotpassword" value="forgotpassword">Forgot Password</a></td>
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

