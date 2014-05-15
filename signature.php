<?php
session_start();
define( 'YOURLS_ADMIN', true );
require_once ( dirname(__FILE__).'/includes/load-yourls.php' );
yourls_maybe_require_auth();
$page = YOURLS_SITE . '/index.php';
// Insert <head> markup and all CSS & JS files
yourls_html_head();
//getting API Key
include_once "user.class.php";  
$obj_user = new user();
$signature = $obj_user->get_user_signature();
?>
 
<script type="text/javascript" src="js/validation.js"></script>
<div class="contentarea">

    <div id="myDiv" class="signup">

       

        <div class="title">API Key Usage <a href="reset_signature.php" class="white right">Reset Key</a></div>    
        
        <table border="0" cellpadding="3" cellspacing="0" width="100%">        
        <tr>
        <td>


        <div style="display: block;">


<p class="heading">LYCSO API Key Usage Guidelines</p>

<p class="subheading">Description:</p>

<!-- <p class="subheading">What is LYCSO?</p>

<p class="intro"> LYCSO allow you to run your own URL shortening service (TinyURL or bitly).</p>
<p class="intro">Running your own short URL is fun, geeky and useful: you own your data and don't depend on third party services. It's also a great way to add branding to your short URLs, instead of using the same public URL shortener everyone uses.</p>

<p class="subheading">Features</p>

<ul class="bulletlist">
  <li>Generate or get existing short URLs, with sequential or custom keyword</li>

  <li>Output format: JSON or XML</li>

  <li>Authentify either with login/password or using a secure passwordless mechanism</li>
</ul>


<p class="subheading">Usage</p>

<div class="margin20_L">

  <p class="subheading">1. Authentifying with Login/Password:</p>

  <p class="margin20_L">You need to send parameters to<br/>

  http://hostname/api.php via GET (remember to URL encode parameters). These are the parameters with valid username and password:</p>


  <ul class="listcontent">

    <li>1. username: Your Username</li>
    <li>2. Password: Your Password</li>
    <li>3. action: shorturl</li>
    <li>4. url: longurl</li>
    <li>5. format: json or xml</li>

  </ul>


  <p class="innertitle margin20_L">Example:</p>

  <div class="margin50_L">
    <p><a href="http://local.lycos.com/api.php">http://hostname/api.php?username=yourusername&amp;password=yourpassword&amp;action=shorturl&amp;url=http://example.com&amp;format=json</a></p>
  </div>

  <p class="innertitle margin20_L">Output:</p>

  <div class="margin50_L">
    <div class="margin20_L">
      {<br/>
        <span class="margin10_L">
          "url": {
        </span><br/>

          <div class="margin20_L">
            "keyword": "t4be6",<br/>
            "url": "http://example.com",<br/>
            "title": "Example Domain",<br/>
            "date": "2014-04-15 23:25:03",<br/>
            "ip": "your ip address"<br/>
            },<br/>
            "status": "success",<br/>
            "message": "http://example.com added to database",<br/>
            "title": "Example Domain",<br/>
            "shorturl": "http://hostname/t4be6",<br/>
            "statusCode": 200
          </div>

      }
    </div>
  </div>

</div> -->

<div class="margin20_L">

  <p class="subheading">Secure passwordless API call</p>
  <p class="subintro">LYCSO allows API calls the old fashioned way, using username and password parameters. If you're worried about sending your credentials into the wild, you can also make API calls without using your login or your password, using a secret API key.<br/>
  Your secret API key: <?php echo $signature;?>(It's a secret. Keep it secret)<br/>
  This signature token can only be used with the API, not with the admin interface.</p>

  <p class="smallheading">a.Usage of the signature token</p>
  <div class="margin20_L">
    Simply use parameter signature in your API requests.<br/>
    * <u>signature=<?php echo $signature;?></u><br/>
    Example:<br/>
    <u>http://hostname.com/api.php?signature=<?php echo $signature;?>&action=shorturl&url=http://hostname.com&format=xml</u><br>
    Format can be of xml/json
  </div>

  <!-- <p class="smallheading">b. Usage of a time limited signature token</p>
  <div class="margin20_L">
    &lt;?php<br/>
$timestamp = time();<br/>
// actual value: $time = 1397580453<br/>
$signature = md5( $timestamp . 'e5c8ef4272' ); <br/>
// actual value: $signature = "e32274d309aae5ae32221f4d556e5cdd"<br/>
?&gt; 
  </div>

  Now use parameters signature and timestamp in your API requests.

  <p class="smallheading">Example:</p>
  <div class="margin20_L">
    http://hostname/api.php?timestamp=$timestamp&signature=$signature&action=...<br/>
<strong>Actual values:</strong><br/>
http://hostname/api.php?timestamp=1397580453&signature=e32274d309aae5ae32221f4d556e5cdd&action=...

<br/>
This URL would be valid for only 43200 seconds -->
  </div>



</div>



</div>
</td></tr>
        </table>


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
