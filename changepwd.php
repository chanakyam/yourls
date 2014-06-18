<?php
session_start();
define( 'YOURLS_ADMIN', true );
require_once ( dirname(__FILE__).'/includes/load-yourls.php' );
yourls_maybe_require_auth();
$page = YOURLS_SITE . '/index.php';
// Insert <head> markup and all CSS & JS files
yourls_html_head();
?>

<!-- Required CSS -->	
<div class="contentarea">
	<title>Change Password</title>
	<script type="text/javascript">
	$(document).ready(function(){
   		$("form[name='changepwd'] input").on('blur',validatechangepwd);
	});
	
	function validatechangepwd(){		
		//validate old password
		if(document.forms["changepwd"]["password"]){
   			var password=document.forms["changepwd"]["password"].value;
   			password = $.trim(password);
      		$("#password").val(password);
      		var password_max_length= 64;
      		var password_min_length= 6;
  			if (password==null || password==""){
    			//errorMessage("label_password", "(This field is required)");
    			$('input#password').addClass('required');
    			invalid = true;
   			}else if(password.length > password_max_length || password.length < password_min_length){
 				$('input#password').addClass('required');
 				errorMessage("label_password", "(Required minimum "+ password_min_length +" characters)");
 				invalid = true;
			}//else if(!password.match(/^.*(?=.{6,})(?=.*[a-zA-Z])(?=.*\d)(?=.*[_.!@#$%^()+ "]).*$/)){
 			 else if(!password.match(((/^\S |^[a-zA-Z0-9~!@#$%^&*\(\)_+}{\|\":?><`\-=\\\]\[';\/\.\,]{6,}$/)))){
 				//errorMessage("label_newpwd", "Please enter valid password");
 				$('input#password').addClass('required');
 				invalid = true;
 			}else{
   				errorMessage("label_password", "");
   				$('input#password').removeClass('required');
   			}
    	}
		//validate new password
    	if(document.forms["changepwd"]["newpwd"]){
   			var newpwd=document.forms["changepwd"]["newpwd"].value;
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
    	//special characters validation for password
      
      // for (var i = 0; i < document.changepwd.newpwd.value.length; i++) {
      //   if (iChars.indexOf(document.changepwd.newpwd.value.charAt(i)) != -1) 
      //     {
      //       errorMessage("label_newpwd", "(Special characters are not allowed)");
      //       return false;
      //     }
      // }

 		//confirm password validation (cnew)
    	if(document.forms["changepwd"]["cnew"]){

      		var cnew=document.forms["changepwd"]["cnew"].value;
      		if (cnew != newpwd){
          		//errorMessage("label_cnew", "(Password not matching)");
          		$('input#cnew').addClass('required');
          		invalid = true;
      		}else{
        		//errorMessage("label_cnew", "");
        		$('input#cnew').removeClass('required');
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
	
	<!-- body -->
	<div class="signup changepwd">

<form name="changepwd" method="post" action="process.php" onsubmit="return validatechangepwd();">
<div class="title">Change Password</div>
          <?php if (isset($_REQUEST['status']) && $_REQUEST['status']== 1 ){
          echo "<div class='success'>Changed Password succesfully.</div>";
          }?>

          <?php if (isset($_REQUEST['status']) && $_REQUEST['status']== 0 ){
          //echo "<div class='errormessage'>Changing Password Failed.</div>";
          echo "<div class='errormessage'>Your Old Password Doesnt Match.</div>";
          }?> 
				<table  width="100%" border="0" cellpadding="0" cellspacing="0"e>
				<tr>
					<td width="35%">Old Password<!-- <span class="error_message" >*</span> -->:</td>
					<td>
						<input class="width96" type="password" id="password" name="password" >
						<span class="error_message" id="label_password"></span>
					</td>
				</tr>
				<tr>
					<td>New Password<!-- <span class="error_message" >*</span> -->:</td>
					<td>
						<input class="width96" type="password" id="newpwd" name="newpwd" >
						<span class="error_message" id="label_newpwd"></span>
					</td>
				</tr>
				<tr>
					<td>Confirm New Password<!-- <span class="error_message" >*</span> -->:</td>
					<td>
						<input class="width96" type="password" id="cnew" name="cnew" >
						<span class="error_message" id="label_cnew"></span>
					</td>
				</tr>

				<tr>
	<td></td>
	<td><input type="submit" id="change" name="form_type" value="Change">  <input type="reset" id="reset" name="form_type" value="Reset">

	</td>
</tr>
				<!-- <input type="hidden" id="user_id" name="user_id" value=value=<?php //echo $_GET['user_id'] ?> -->
				

			</table>
			</div>
		</div>
	</form>
	</div>
	<!--end body  -->



    
</div>
<!--contentarea end-->
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
