<?php
session_start();
define( 'YOURLS_ADMIN', true );
require_once ( dirname(__FILE__).'/includes/load-yourls.php' );
yourls_maybe_require_auth();
$page = YOURLS_SITE . '/index.php';
// Insert <head> markup and all CSS & JS files
yourls_html_head();
?>
 
<script type="text/javascript" src="js/validation.js"></script>
<div class="contentarea">

    <div id="myDiv" class="signup">

        <form  method="post" name="Registration" action="process.php" onsubmit="return validateForm();">
        <div class="title">Add User</div> 
          <?php if (isset($_REQUEST['status']) && $_REQUEST['status']== 1 ){
          echo "<div class='success'>Added Successfully</div>";
          }?>

          <?php if (isset($_REQUEST['status']) && $_REQUEST['status']== 0 ){
          echo "<div class='errormessage'>Failed to add user.</div>";
          }?>      
        
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
          <td width="20%">First Name<span class="error_message" >*</span>:</td>
          <td>
            <input class="width50" type="text" name="firstname" id="firstname" placeholder="Enter Firstname">
            
          
        		<span class="error_message" id="label_firstname"></span>
        	</td>
        </tr>       

        <tr>
          <td>Last Name<span class="error_message" >*</span>:</td>
          <td>
            <input class="width50" type="text" name="lastname" id="lastname" placeholder="Enter Lastname">
         
            	<span class="error_message" id="label_lastname"></span>	
        	</td>
        </tr>       

        <tr>
          <td>Email Address<span class="error_message" >*</span>:</td>
          <td>
            <input class="width50" type="text" name="email"  id="email" placeholder="example@gmail.com">
          
            	<span class="error_message" id="label_email"></span>
        	</td>
        </tr>       

        <tr>
          <td>Password<span class="error_message" >*</span>:</td>
          <td>
            <input class="width50" type="password" name="password" id="password" placeholder="Enter Password">
          
            	<span class="error_message" id="label_password"></span>
        	</td>
        </tr>       

        <tr>
          <td>Confirm Password<span class="error_message" >*</span>:</td>
          <td>
            <input class="width50" type="password" name="confpassword" id="confpassword" placeholder="Confirm Password">
          
            	<span class="error_message" id="label_confpassword"></span>
        	</td>
        </tr>       

        <tr>
          <td></td>
          <td>
          	<input  type="submit" value="Add" name="form_type" id="submit">
          	<input  type = "reset" value="Reset" name="form_type" id="reset">
          </td>
        </tr>
        </table>
        </form>
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

<?php
// Display page footer
yourls_html_footer();

?>
