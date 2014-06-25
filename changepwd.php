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
