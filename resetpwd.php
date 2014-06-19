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
				$(document).ready(function(){
   					$("form[name='resetpwd'] input").on('blur',validatePassword);
				});

				function validatePassword(){		
					//validate new password
					var invalid = '';
			    	if(document.forms["resetpwd"]["newpwd"]){
			   		var newpwd=document.forms["resetpwd"]["newpwd"].value;
			   		newpwd = $.trim(newpwd);
      				$("#newpwd").val(newpwd);
      				var newpwd_max_length= 64;
      				var newpwd_min_length= 6;
			  			if (newpwd==null || newpwd==""){
			    			//errorMessage("label_newpwd", "(This field is required)");
			    			$('input#newpwd').addClass('required');
			    			invalid = true;
			   			}else if(newpwd.length > newpwd_max_length || newpwd.length < newpwd_min_length){
 					        $('input#newpwd').addClass('required');
 					        errorMessage("label_newpwd", "(Required minimum "+ newpwd_min_length +" characters)");
 					        invalid = true;
					
 					    }//else if(!newpwd.match(/^.*(?=.{6,})(?=.*[a-zA-Z])(?=.*\d)(?=.*[_.!@#$%^()+ "]).*$/)){
 					     else if(!newpwd.match(((/^\S |^[a-zA-Z0-9~!@#$%^&*\(\)_+}{\|\":?><`\-=\\\]\[';\/\.\,]{6,}$/)))){
 					        //errorMessage("label_newpwd", "Please enter valid password");
 					        $('input#newpwd').addClass('required');
 					        invalid = true;
 					    }else{
			   				errorMessage("label_newpwd", "");
			   				$('input#newpwd').removeClass('required');
			   			}
			    	}			

			 		//confirm password validation (cnew)
			    	if(document.forms["resetpwd"]["cnew"]){			

			      		var cnew=document.forms["resetpwd"]["cnew"].value;
			      		if (cnew != newpwd){
			          		//'errorMessage("label_cnew", "(Password not matching)");
			          		$('input#cnew').addClass('required');
			          		invalid = true;
			      		}else{
			        		//errorMessage("label_cnew", "");
			        		$('input#cnew').removeClass('required');

			      		}
			    	}			

			    	if(invalid){
			 			return false;
			 		}else{
			 			return true;
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
			<div  class="signup reset">
				<form name="resetpwd" method="post" action="process.php" onsubmit="return validatePassword();" >
						<div class="title">Reset Password</div>
						<?php 
							if (isset($_REQUEST['status']) && $_REQUEST['status']== 1 ){
 	 			       		echo "<div class='success'>Password reset succesfully.</div>";
 	 			       		}
 	 			       	?>
	
				 		<?php 
				 			if (isset($_REQUEST['status']) && $_REQUEST['status']== 0 ){
 	 			       		echo "<div class='errormessage'>Password reset failed.</div>";
 	 			       		}
 	 			       	?>

						<table width="100%">
							<tr>
								<td width="35%">New Password<!-- <span class="error_message" >*</span> -->:</td>
								<td>
									<input type="password" id="newpwd" class="fullwidth" name="newpwd">
									<span class="error_message" id="label_newpwd"></span>
								</td>
							</tr>

							<tr>
								<td>Confirm New Password<!-- <span class="error_message" >*</span> -->:</td>
								<td>
									<input type="password" id="cnew" class="fullwidth" name="cnew">
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