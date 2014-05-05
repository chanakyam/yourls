<?php
session_start();
define( 'YOURLS_ADMIN', true );
require_once ( dirname(__FILE__).'/includes/load-yourls.php' );
yourls_maybe_require_auth();

// Insert <head> markup and all CSS & JS files
yourls_html_head();
?>

<!-- Required CSS -->	
<div class="contentarea">
	
<?php
include_once "user.class.php";
$obj_user = new user();
$user_data = $obj_user->getUserDetails();
?>

<script type="text/javascript" src="assets/script.js"></script>

<div id="myDiv" class="signup">


<form name="Registration" method="post" action="process.php" enctype="multipart/form-data" onSubmit="return showUser()">
<div class="title">My Profile</div>
		<?php 
			if (isset($_REQUEST['status']) && $_REQUEST['status']== 1 ){
	        	echo "<center><strong>Updated succesfully.</strong></center>";
	        }
	    ?>

		<?php 
			if (isset($_REQUEST['status']) && $_REQUEST['status']== 0 ){
	        	echo "<center><strong>Please try again.</strong></center>";
	        }
	    ?>


<table border="0" cellpadding="0" cellspacing="0">

<tr>
	<td>First Name<span class="error_message" >*</span>:</td>
	<td>
		<input type="text" name="firstname" id="firstname" placeholder="Enter Firstname" value="<?php echo $user_data['firstname']; ?>">
		<span class="error_message" id="label_firstname"></span>	
	</td>
</tr>
<tr>
	<td>Last Name<span class="error_message" >*</span>:</td>
	<td>
		<input type="text" name="lastname" id="lastname" placeholder="Enter Lastname" value="<?php echo $user_data['lastname']; ?>">
		<span class="error_message" id="label_lastname"></span>
	</td>
</tr>
<tr>
	<td>Email<span class="error_message" >*</span>:</td>
	<td>
		<input type="text" name="email" id="email" placeholder="Enter Lastname" value="<?php echo $user_data['email']; ?>">
		<span class="error_message" id="label_email"></span>
	</td>
</tr>
<tr>
	<td></td>
	<td>
		<input type="submit" value="Update" name="form_type" id="submit">

	</td>
</tr>
</table>
</form>
</div>


<!--contentarea end-->
				
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
