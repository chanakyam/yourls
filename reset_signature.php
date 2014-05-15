<?php
session_start();
define( 'YOURLS_ADMIN', true );
require_once ( dirname(__FILE__).'/includes/load-yourls.php' );
yourls_maybe_require_auth();
$page = YOURLS_SITE . '/index.php';
// Insert <head> markup and all CSS & JS files
yourls_html_head();
//getting api signature
include_once "user.class.php";  
$obj_user = new user();
$signature = $obj_user->get_user_signature();
?>
 
<script type="text/javascript" src="js/validation.js"></script>
<div class="contentarea">

    <div id="myDiv" class="signup span5">

        <form  method="post" name="Registration" action="process.php" onsubmit="return validateForm();">
        <div class="title">API Signature</div> 
          <?php if (isset($_REQUEST['status']) && $_REQUEST['status']== 1 ){
          echo "<div class='success'>API key generated successfully</div>";
          }?>

          <?php if (isset($_REQUEST['status']) && $_REQUEST['status']== 0 ){
          echo "<div class='errormessage'>Failed to generate API key.</div>";
          }?>      
        
        <table border="0" cellpadding="3" cellspacing="0" width="100%">
        <tr>
          <td width="35%">Current API Key<span class="error_message" ></span>:</td>
          <td>
            <input type="text" readonly="readonly" name="firstname" id="firstname" value="<?php echo $signature;?>">
            
          
            <span class="error_message" id="label_firstname"></span>
          </td>
        </tr> 
        <tr>
          <td></td>
          <td>            
            <input  type = "submit" value="Reset Key" name="form_type" id="reset">
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
