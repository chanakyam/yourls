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

	function validatechangepwd(){		
		//validate old password
		if(document.forms["changepwd"]["password"]){
   			var password=document.forms["changepwd"]["password"].value;
  			if (password==null || password==""){
    			errorMessage("label_password", "(This field is required)");
    			invalid = true;
   			}else{
   			errorMessage("label_password", "");
   			}
    	}
		//validate new password
    	if(document.forms["changepwd"]["newpwd"]){
   			var newpwd=document.forms["changepwd"]["newpwd"].value;
  			if (newpwd==null || newpwd==""){
    			errorMessage("label_newpwd", "(This field is required)");
    			invalid = true;
   			}else{
   			errorMessage("label_newpwd", "");
   			}
    	}
    	//special characters validation for password
      
      for (var i = 0; i < document.changepwd.newpwd.value.length; i++) {
        if (iChars.indexOf(document.changepwd.newpwd.value.charAt(i)) != -1) 
          {
            errorMessage("label_newpwd", "(Special characters are not allowed)");
            return false;
          }
      }

 		//confirm password validation (cnew)
    	if(document.forms["changepwd"]["cnew"]){

      		var cnew=document.forms["changepwd"]["cnew"].value;
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
	
	<!-- body -->
	<div class="signup">

<form name="changepwd" method="post" action="process.php" onsubmit="return validatechangepwd();">
<div class="title">Change Password</div>
          <?php if (isset($_REQUEST['status']) && $_REQUEST['status']== 1 ){
          echo "<center><strong>Changed Password succesfully.</strong></center>";
          }?>

          <?php if (isset($_REQUEST['status']) && $_REQUEST['status']== 0 ){
          echo "<center><strong>Changing Password Failed.</strong></center>";
          }?> 
				<table  width="100%" border="0">
				<tr>
					<td>Old Password<span class="error_message" >*</span>:</td>
					<td>
						<input type="password" id="password" name="password" placeholder="Enter Old Password">
						<span class="error_message" id="label_password"></span>
					</td>
				</tr>
				<tr>
					<td>New Password<span class="error_message" >*</span>:</td>
					<td>
						<input type="password" id="newpwd" name="newpwd" placeholder="Enter New Password">
						<span class="error_message" id="label_newpwd"></span>
					</td>
				</tr>
				<tr>
					<td>Confirm New Password<span class="error_message" >*</span>:</td>
					<td>
						<input type="password" id="cnew" name="cnew" placeholder="Re-Enter New Password">
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

<style>
#wrap{ width: 100%; max-width: 100%; padding: 0;}
.add {
    background-color: #000000;
    bottom: 37px;
    line-height: 0;
    padding: 10px 0;
    position: fixed;
    width: 100%;
}
.center {
    text-align: center;
}
</style>