<?php
header('Cache-Control: max-age=900');
/**
 * Display <h1> header and logo
 *
 */

function yourls_html_logo() {
	yourls_do_action( 'pre_html_logo' );
	?>
	<h1>
		<a href="<?php echo yourls_admin_url( 'index.php' ) ?>" title="lyc.so"><span>Lycos</span>: <span>URL</span> <span>S</span>hortener<br/>
		<img src="<?php yourls_site_url(); ?>/images/yourls-logo.png" alt="lyc.so" title="lyc.so" border="0" style="border: 0px;" /></a>
	</h1>
	<?php
	yourls_do_action( 'html_logo' );
}

/**
 * Display HTML head and <body> tag
 *
 * @param string $context Context of the page (stats, index, infos, ...)
 * @param string $title HTML title of the page
 */
function yourls_html_head( $context = 'index', $title = '' ) {
	//if( yourls_is_admin() || $context =='infos'){echo 'admin';	}exit;

	yourls_do_action( 'pre_html_head', $context, $title );
	
	// All components to false, except when specified true
	$share = $insert = $tablesorter = $tabs = $cal = $charts = false;
	
	// Load components as needed
	switch ( $context ) {
		case 'infos':
			$share = $tabs = $charts = true;
			break;
			
		case 'bookmark':
			$share = $insert = $tablesorter = true;
			break;
			
		case 'index':
			$insert = $tablesorter = $cal = $share = true;
			break;
			
		case 'plugins':
		case 'tools':
			$tablesorter = true;
			break;
		
		case 'install':
		case 'login':
		case 'new':
		case 'upgrade':
			break;
	}
	
	// Force no cache for all admin pages
	if( yourls_is_admin() && !headers_sent() ) {
		header( 'Expires: Thu, 23 Mar 1972 07:00:00 GMT' );
		header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
		header( 'Cache-Control: no-cache, must-revalidate, max-age=0' );
		header( 'Pragma: no-cache' );
		yourls_content_type_header( yourls_apply_filters( 'html_head_content-type', 'text/html' ) );
		yourls_do_action( 'admin_headers', $context, $title );
	}
	
	// Store page context in global object
	global $ydb;
	$ydb->context = $context;
	
	// Body class
	$bodyclass = yourls_apply_filter( 'bodyclass', '' );
	$bodyclass .= ( yourls_is_mobile_device() ? 'mobile' : 'desktop' );
	
	// Page title
	//$_title = 'YOURLS &mdash; Your Own URL Shortener | ' . yourls_link();
	$_title = 'Lycos URL Shortener';
	$title = $title ? $title . " &laquo; " . $_title : $_title;
	$title = yourls_apply_filter( 'html_title', $title, $context );
	?>
<!DOCTYPE html>
<html <?php yourls_html_language_attributes(); ?>>
<head>
	<title><?php echo $title ?></title>
	<link rel="shortcut icon" href="<?php yourls_favicon(); ?>" />
	<meta charset="utf-8">
	<meta name="google-site-verification" content="1_GeXlg9wfqjtucydEYxWtoHxeb73hfOQLyqS5NkGF0" />	
	<script src="<?php yourls_site_url(); ?>/js/jquery-1.8.2.min.js?v=<?php echo YOURLS_VERSION; ?>" type="text/javascript"></script>
	<script src="<?php yourls_site_url(); ?>/js/validation.js?v=<?php echo YOURLS_VERSION; ?>" type="text/javascript"></script>
	<script src="<?php yourls_site_url(); ?>/js/common.js?v=<?php echo YOURLS_VERSION; ?>" type="text/javascript"></script>
	<script src="<?php yourls_site_url(); ?>/js/jquery.notifybar.js?v=<?php echo YOURLS_VERSION; ?>" type="text/javascript"></script>

<!-- my includes -->
<script src="<?php yourls_site_url(); ?>/js/jquery.dataTables.js?v=<?php echo YOURLS_VERSION; ?>" type="text/javascript"></script>
<link rel="stylesheet" href="<?php yourls_site_url(); ?>/css/tablesorter.css?v=<?php echo YOURLS_VERSION; ?>" type="text/css" media="screen" />
<!-- end my includes -->
<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro' rel='stylesheet' type='text/css'>


	<?php if( yourls_is_admin() || $context == 'infos' ||$context == 'login') {?>
	<link rel="stylesheet" href="<?php yourls_site_url(); ?>/css/adminstyle.css?v=<?php echo YOURLS_VERSION; ?>" type="text/css" media="screen" />
	<?php }else{ ?>
	<link rel="stylesheet" href="<?php yourls_site_url(); ?>/css/style.css?v=<?php echo YOURLS_VERSION; ?>" type="text/css" media="screen" />
	<?php }?>
	<?php if ( $tabs ) { ?>
		<link rel="stylesheet" href="<?php yourls_site_url(); ?>/css/infos.css?v=<?php echo YOURLS_VERSION; ?>" type="text/css" media="screen" />
		<script src="<?php yourls_site_url(); ?>/js/infos.js?v=<?php echo YOURLS_VERSION; ?>" type="text/javascript"></script>
	<?php } ?>
	<?php if ( $insert ) { ?>
		<script src="<?php yourls_site_url(); ?>/js/insert.js?v=<?php echo YOURLS_VERSION; ?>" type="text/javascript"></script>
	<?php } ?>
	<?php if ( $share ) { ?>
		<link rel="stylesheet" href="<?php yourls_site_url(); ?>/css/share.css?v=<?php echo YOURLS_VERSION; ?>" type="text/css" media="screen" />
		<script src="<?php yourls_site_url(); ?>/js/share.js?v=<?php echo YOURLS_VERSION; ?>" type="text/javascript"></script>
		<script src="<?php yourls_site_url(); ?>/js/jquery.zclip.min.js?v=<?php echo YOURLS_VERSION; ?>" type="text/javascript"></script>
	<?php } ?>
	<?php if ( $cal && yourls_is_admin()) { ?>
		<link rel="stylesheet" href="<?php yourls_site_url(); ?>/css/cal.css?v=<?php echo YOURLS_VERSION; ?>" type="text/css" media="screen" />
		<?php yourls_l10n_calendar_strings(); ?>
		<script src="<?php yourls_site_url(); ?>/js/jquery.cal.js?v=<?php echo YOURLS_VERSION; ?>" type="text/javascript"></script>
	<?php } ?>
	<?php if ( $charts ) { ?>
			<script type="text/javascript" src="https://www.google.com/jsapi"></script>
			<script type="text/javascript">
					 google.load('visualization', '1.0', {'packages':['corechart', 'geochart']});
			</script>
	<?php } ?>
	<script type="text/javascript">
	//<![CDATA[
		var ajaxurl  = '<?php echo yourls_admin_url( 'admin-ajax.php' ); ?>';
		var zclipurl = '<?php yourls_site_url(); ?>/js/ZeroClipboard.swf';
	//]]>
	</script>

	
	<?php if ( yourls_is_admin()) { ?>
	<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
				var oTable= $('#dashboard_main_table').dataTable({
					 aaSorting : [[2, 'desc']],
					 "bProcessing": true,
					 "bServerSide": true,
					 "sPaginationType": "full_numbers",
                     "sAjaxSource": '/ajax/server_processing.php',
                     "aoColumnDefs": [

								    { "fnRender": function ( oObj ) {
								        if ( oObj.aData[3] != "" ) {
								            return '<td id="actions-'+oObj.aData["id"]+'" class="actions "><a target="_blank" class="button button_stats" title="Stats" id="statlink-'+oObj.aData["id"]+'" href="/'+oObj.aData["keyword"]+'+">Stats</a><a onclick="toggle_share(\''+oObj.aData["id"]+'\');return false;" class="button button_share" title="Share" id="share-button-'+oObj.aData["id"]+'" href="">Share</a><a onclick="edit_link_display(\''+oObj.aData["id"]+'\');return false;" class="button button_edit" title="Edit" id="edit-button-'+oObj.aData["id"]+'" href="/admin/admin-ajax.php?id='+oObj.aData["id"]+'&amp;action=edit&amp;keyword='+oObj.aData["keyword"]+'&amp;nonce='+oObj.aData["nonce_edit"]+'">Edit</a><a onclick="remove_link(\''+oObj.aData["id"]+'\');return false;" class="button button_delete" title="Delete" id="delete-button-'+oObj.aData["id"]+'" href="/admin/admin-ajax.php?id='+oObj.aData["id"]+'&amp;action=delete&amp;keyword='+oObj.aData["keyword"]+'&amp;nonce='+oObj.aData["nonce_delete"]+'">Delete</a><input type="hidden" value="'+oObj.aData["keyword"]+'" id="keyword_'+oObj.aData["id"]+'"></td>';
								            }
								        else {
								            return oObj.aData[5];
								            }
								        },
								        "aTargets": [ 5 ]
								    },
								      { "fnRender": function ( oObj ) {
								        if ( oObj.aData[1] != "" ) {
								            return '<td id="url-'+oObj.aData["id"]+'" class="url "><a target="_blank" title="'+oObj.aData[1]+'" href="'+oObj.aData[1]+'">'+oObj.aData["short_title"]+'</a><br><small id="longurl-'+oObj.aData["id"]+'"><a target="_blank" href="'+oObj.aData[1]+'">'+oObj.aData["short_url"]+'</a></small></td>';
								            }
								        else {
								            return oObj.aData[1];
								            }
								        },
								        "aTargets": [ 1 ]
								    }
								 ],
                     "aoColumns": [
				        { "bSearchable": true, "bSortable": true,"sClass": "keyword  sorting_1", "bVisible": true },
				        { "bSearchable": true, "bSortable": true,"sClass": "url", "bVisible": true },
				        { "bSearchable": true, "bSortable": true,"sClass": "timestamp", "bVisible": true },
				        { "bSearchable": true, "bSortable": true,"sClass": "ip", "bVisible": true },
				        { "bSearchable": true, "bSortable": true,"sClass": "clicks", "bVisible": true },
				        { "bSearchable": false, "bSortable": false,"sClass": "actions", "bVisible": true, "sDefaultContent": 'Actions' }
				        ],
				     "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
			                
			                $(nRow).attr("id",'id-' + aData["id"]);
    						return nRow;
			            }

				});


			} );
		</script>
		<!-- added for manage users -->
		<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
				var oTable= $('#users_main_table').dataTable({
					aaSorting : [[0, 'desc']],					
					"bStateSave": true,
					stateSave: true,
					"bProcessing": true,
					"bServerSide": true,
					//"sPaginationType": "iFullNumbersShowPages",
					//"sPaginationType": "two_button",
					"sPaginationType": "full_numbers",
                    "sAjaxSource": '../ajax/server_users.php',
                    "aoColumnDefs": [

								    { "fnRender": function ( oObj ) {
								        if ( oObj.aData[6] != "" ) {
								            return '<td id="actions-'+oObj.aData["id"]+'" class="actions "><a onclick="edit_user_display(\''+oObj.aData["user_id"]+'\');return false;" class="button button_edit" title="Edit" id="edit-button-'+oObj.aData["user_id"]+'" action=edit&amp;nonce='+oObj.aData["nonce_edit"]+'">Edit</a><a onclick="remove_user_link(\''+oObj.aData["user_id"]+'\');return false;" class="button button_delete" title="Delete" id="delete-button-'+oObj.aData["user_id"]+'" href="/admin/admin-ajax.php?id='+oObj.aData["user_id"]+'&amp;action=delete_user&amp;keyword='+oObj.aData["keyword"]+'&amp;nonce='+oObj.aData["nonce_delete"]+'">Delete</a><input type="hidden" value="'+oObj.aData["user_id"]+'" id="userid'+oObj.aData["user_id"]+'"></td>';
								            }
								        else {
								            return oObj.aData[5];
								            }
								        },
								        "aTargets": [ 6 ]
								    }
								 ],
								 "aoColumns": [
						{ "bSearchable": true, "aTargets": 0,"bSortable": true,"sClass": "user_id", "bVisible": true },
				        { "bSearchable": true, "bSortable": true,"sClass": "first_name", "bVisible": true },
				        { "bSearchable": true, "bSortable": true,"sClass": "last_name", "bVisible": true },
				        { "bSearchable": true, "bSortable": true,"sClass": "user_email", "bVisible": true },
				        { "bSearchable": true, "bSortable": true,"sClass": "user_role", "bVisible": true },
				        { "bSearchable": true, "bSortable": true,"sClass": "user_status", "bVisible": true },
				        { "bSearchable": false, "bSortable": false,"sClass": "actions", "bVisible": true, "sDefaultContent": 'Actions' }
				        ],
				        "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
			                
			                $(nRow).attr("id",'id-' + aData["user_id"]);
    						return nRow;
			            }
				});

			} );
		</script>
		<?php }?>
		<script type="text/javascript">
			$(document).ready(function() {
			// run drop down menus
			$('.dropDown').click(function(){
				$(this).parent().children('.ddMenu').toggle();
				return false;
			});
			
			// close drop downs
			$(document).click(function(ev){
				if (!$(ev.target).hasClass("dropDown")) {
					$('.ddMenu').hide();
					$('#topBar a').removeClass('selected');
				}
			});
			});
		</script>

		




	<?php yourls_do_action( 'html_head', $context ); ?>
	<!--[if lte IE 8]>
 <link rel="stylesheet" type="text/css" href="<?php yourls_site_url(); ?>/css/ie8.css" />
<![endif]-->
</head>

<?php if( yourls_is_admin() ) {?>

<body class="<?php echo $context; ?> <?php echo $bodyclass; ?>">
 
<div class="header">
	<a href="<?php yourls_site_url(); ?>" class="h-logo" title="lyc.so"><img alt="lyc.so" title="lyc.so" src="/images/headerDog_2x.png"></a> 
	
<?php yourls_html_menu() ?>
<?php if($context=='login'){?>
	<span id="admin_menu_search_link"><a target="_blank" title="Lycos.com" href="http://search.lycos.com">Search</a></span>
	<span id="admin_menu_mail_link"><a target="_blank" title="mail.lycos.com" href="http://mail.lycos.com">Mail</a></span>
	<span id="admin_menu_tripod_link"><a target="_blank" title="Tripod" href="http://tripod.lycos.com">Tripod</a></span>
	<span id="admin_menu_gamesville_link"><a target="_blank" title="Gamesville.com" href="http://gamesville.com">Gamesville</a></span>
<?php }?>
<div class="moremenu">
        	<a class="dropDown" title="More Lycos Sites" href="#">More <span class="sprite"></span></a>
        	<ul style="display: none;" class="hide ddMenu lyGrey boxShadow1">
                <li class="mobileShow"><a target="_blank" href="http://www.tripod.lycos.com" title="Tripod">Tripod</a></li>
                <li class="mobileShow"><a target="_blank" href="http://www.gamesville.com/" title="Gamesville.com">Gamesville</a></li>
				<li><a target="_blank" href="http://domains.lycos.com/" title="Lycos Domains">Lycos Domains</a></li>
				<li><a target="_blank" href="http://news.lycos.com" title="Lycos News">Lycos News</a></li>
				<li><a target="_blank" href="http://shopping.lycos.com" title="Lycos Shopping">Lycos Shopping</a></li>					
				<li><a target="_blank" href="http://weather.lycos.com" title="Lycos Weather">Lycos Weather</a></li>
				<li><a target="_blank" href="http://www.whowhere.com/" title="WhoWhere?">WhoWhere?</a></li>
				<li><a target="_blank" href="http://chat.lycos.co.uk" title="Lycos Chat">Lycos Chat</a></li>
				<li><a target="_blank" href="http://info.lycos.com/about/products" title="View All" id="viewAll">View All</a></li>
            </ul>
</div>
	<span class="menu">
<?php 
 if( defined( 'YOURLS_USER' ) ) {
		$logout_link = yourls_apply_filter( 'logout_link', sprintf( yourls__('<span class="left margin12_T">Hello <strong>%s</strong>'), $_SESSION['name'] ) . '</span><a href="?action=logout" title="' . yourls_esc_attr__( 'Sign out' ) . '" class="logout"><img src="/images/logout-icon.png">' . '</a>
			
        	<div class="moremenu settingmenu">
        	<a  class="dropDown" title="' . yourls_esc_attr__( 'Settings' ) . '"><img src="/images/setting.png"> </a>
        	<ul style="display: none;" class="hide ddMenu lyGrey boxShadow1">
                <li><a href="editprofile.php" title="Edit Profile">Edit Profile</a></li>                
				<li><a href="changepwd.php" title="Change Password" id="changepwd">Change Password</a></li>
            </ul>
	</div>' );
	} else {
		$logout_link = yourls_apply_filter( 'logout_link', '' );
	}
	echo "$logout_link";

	$logout_link ?></span>
</div>

<?php }elseif(!yourls_is_admin() && $context!='infos'){?>
	<div class="header">
	<a href="<?php yourls_site_url(); ?>" class="logo" title="lyc.so"><img alt="lyc.so" title="lyc.so" src="/images/headerDog_2x.png"></a> 
	<?php 
		if(isset($_SESSION['role']) && $_SESSION['role']=='User'){?>
			<span id="admin_menu_user_link"><a href="<?php yourls_site_url(); ?>/user.php" title="User Interface">User Interface</a></span>
			<span id="admin_menu_user_link"><a href="<?php yourls_site_url(); ?>/signature.php" title="API Key">API Key</a></span>
			<span id="admin_menu_user_link"><a href="<?php yourls_site_url(); ?>/stats.php" title="Stats">Stats</a></span>

	<?php }	?>
	<?php 
		if(isset($_SESSION['role']) && $_SESSION['role']=='admin'){?>
			<span id="admin_menu_admin_link"><a href="<?php yourls_site_url(); ?>/user.php" title="Admin Interface">Admin Interface</a></span>
			<span id="admin_menu_plugins_link"><a href="<?php yourls_site_url(); ?>/plugins.php" title="Manage Plugins">Manage Plugins</a></span>
			<span id="admin_menu_users_link"><a href="<?php yourls_site_url(); ?>/manage_users.php" title="Manage Users">Manage Users</a></span>
			<span id="admin_menu_user_link"><a href="<?php yourls_site_url(); ?>/signature.php" title="API Key">API Key</a></span>
			<span id="admin_menu_user_link"><a href="<?php yourls_site_url(); ?>/stats.php" title="Stats">Stats</a></span>
	<?php }	?>
	<span id="admin_menu_search_link"><a target="_blank" title="Lycos.com" href="http://search.lycos.com">Search</a></span>
	<span id="admin_menu_mail_link"><a target="_blank" title="mail.lycos.com" href="http://mail.lycos.com">Mail</a></span>
	<span id="admin_menu_tripod_link"><a target="_blank" title="Tripod" href="http://tripod.lycos.com">Tripod</a></span>
	<span id="admin_menu_gamesville_link"><a target="_blank" title="Gamesville.com" href="http://gamesville.com">Gamesville</a></span>
	<div class="moremenu">
        <a href="#" title="More Lycos Sites" class="dropDown">More <span class="sprite"></span></a>
        	<ul style="display: none;" class="hide ddMenu lyGrey boxShadow1">
                <li class="mobileShow"><a target="_blank" href="http://www.tripod.lycos.com" title="Tripod">Tripod</a></li>
                <li class="mobileShow"><a target="_blank" href="http://www.gamesville.com/" title="Gamesville.com">Gamesville</a></li>
				<li><a target="_blank" href="http://domains.lycos.com/" title="Lycos Domains">Lycos Domains</a></li>
				<li><a target="_blank" href="http://news.lycos.com" title="Lycos News">Lycos News</a></li>
				<li><a target="_blank" href="http://shopping.lycos.com" title="Lycos Shopping">Lycos Shopping</a></li>					
				<li><a target="_blank" href="http://weather.lycos.com" title="Lycos Weather">Lycos Weather</a></li>
				<li><a target="_blank" href="http://www.whowhere.com/" title="WhoWhere?">WhoWhere?</a></li>
				<li><a target="_blank" href="http://chat.lycos.co.uk" title="Lycos Chat">Lycos Chat</a></li>
				<li><a target="_blank" href="http://info.lycos.com/about/products" title="View All" id="viewAll">View All</a></li>
            </ul>
	</div>
<?php //yourls_html_menu() 
//echo '<pre>';print_r($_SESSION);?>
	<span class="menu">
		<?php
		if(isset($_SESSION['username']) && $_SESSION['username']!=''){?>
			<span class="left margin12_T">Hello <strong><?php echo $_SESSION['name'];?></strong></span>		  
			 <div class="moremenu settingmenu">		  
	        	<a class="dropDown" title="Settings"><img src="/images/setting.png"> </a>
	        	<ul style="display: none;" class="hide ddMenu lyGrey boxShadow1">
	               	<li><a href="editprofile.php" title="Edit Profile">Edit Profile</a></li>                
					<li><a href="changepwd.php" title="Change Password" id="changepwd">Change Password</a></li>
	           	</ul>
			 </div>
			 <a href="<?php yourls_site_url(); ?>/user.php?action=logout" title="Sign Out"><img src="/images/logout-icon.png"></a>
		  
		 
		<?php }else{
		?>
		<a href="register.php" title="Sign Up">Sign Up</a>
		<a href="user.php" title="Sign In">Sign In</a>
		<?php }?>
<?php 
 // if( defined( 'YOURLS_USER' ) ) {
	// 	$logout_link = yourls_apply_filter( 'logout_link', sprintf( yourls__('Hello <strong>%s</strong>'), YOURLS_USER ) . ' <a href="?action=logout" title="' . yourls_esc_attr__( 'Sign out' ) . '"><img align="absmiddle" src="/images/logout-icon.png">' . '</a>' );
	// } else {
	// 	$logout_link = yourls_apply_filter( 'logout_link', '' );
	// }
	//echo "$logout_link";

	//$logout_link ?></span>
</div>
<body class="home">
	<?php }else { ?>
<body class="<?php echo $context; ?> <?php echo $bodyclass; ?>">
<?php }?>
<?php if(!yourls_is_admin() && $context=='infos'){?>
<div class="header">
	<a href="<?php yourls_site_url(); ?>" class="logo"><img src="images/headerDog_2x.png" alt="lyc.so" title="lyc.so"/></a> 
	<span id="admin_menu_search_link"><a target="_blank" title="Lycos.com" href="http://search.lycos.com">Search</a></span>
	<span id="admin_menu_mail_link"><a target="_blank" title="mail.lycos.com" href="http://mail.lycos.com">Mail</a></span>
	<span id="admin_menu_tripod_link"><a target="_blank" title="Tripod" href="http://tripod.lycos.com">Tripod</a></span>
	<span id="admin_menu_gamesville_link"><a target="_blank" title="Gamesville.com" href="http://gamesville.com">Gamesville</a></span>
	<div class="moremenu">
        <a href="#" title="More Lycos Sites" class="dropDown">More <span class="sprite"></span></a>
        	<ul style="display: none;" class="hide ddMenu lyGrey boxShadow1">
                <li class="mobileShow"><a target="_blank" href="http://www.tripod.lycos.com" title="Tripod">Tripod</a></li>
                <li class="mobileShow"><a target="_blank" href="http://www.gamesville.com/" title="Gamesville.com">Gamesville</a></li>
				<li><a target="_blank" href="http://domains.lycos.com/" title="Lycos Domains">Lycos Domains</a></li>
				<li><a target="_blank" href="http://news.lycos.com" title="Lycos News">Lycos News</a></li>
				<li><a target="_blank" href="http://shopping.lycos.com" title="Lycos Shopping">Lycos Shopping</a></li>					
				<li><a target="_blank" href="http://weather.lycos.com" title="Lycos Weather">Lycos Weather</a></li>
				<li><a target="_blank" href="http://www.whowhere.com/" title="WhoWhere?">WhoWhere?</a></li>
				<li><a target="_blank" href="http://chat.lycos.co.uk" title="Lycos Chat">Lycos Chat</a></li>
				<li><a target="_blank" href="http://info.lycos.com/about/products" title="View All" id="viewAll">View All</a></li>
            </ul>
	</div>

	<!-- <span class="menu"><a href="#"><img src="images/headerMenuIcon.png" /></a></span> -->
	<span class="menu">
		<?php
		if(isset($_SESSION['username']) && $_SESSION['username']!=''){?>
			<span class="left margin12_T">Hello <strong><?php echo $_SESSION['name'];?></strong></span>		  
			 <div class="moremenu settingmenu">		  
	        	<a class="dropDown" title="Settings"><img src="/images/setting.png"> </a>
	        	<ul style="display: none;" class="hide ddMenu lyGrey boxShadow1">
	               	<li><a href="editprofile.php" title="Edit Profile">Edit Profile</a></li>               
					<li><a href="changepwd.php" title="Change Password" id="changepwd">Change Password</a></li>
	           	</ul>
			 </div>
			 <a href="<?php yourls_site_url(); ?>/user.php?action=logout" title="Sign Out"><img src="/images/logout-icon.png"></a>
		  
		 
		<?php }else{
		?>
		<a href="register.php" title="Sign Up">Sign Uup</a>
		<a href="user.php" title="Sign In">Sign In</a>
		<?php }?>
<?php 
 // if( defined( 'YOURLS_USER' ) ) {
	// 	$logout_link = yourls_apply_filter( 'logout_link', sprintf( yourls__('Hello <strong>%s</strong>'), YOURLS_USER ) . ' <a href="?action=logout" title="' . yourls_esc_attr__( 'Signout' ) . '"><img align="absmiddle" src="/images/logout-icon.png">' . '</a>' );
	// } else {
	// 	$logout_link = yourls_apply_filter( 'logout_link', '' );
	// }
	//echo "$logout_link";

	//$logout_link ?></span>
</div>
<?php }?>
<!--header end-->
<div id="wrap">
	<?php
}

/**
 * Display HTML footer (including closing body & html tags)
 *
 */
function yourls_html_footer() {
	$year = date('Y');
	//global $ydb;
	
	//$num_queries = sprintf( yourls_n( '1 query', '%s queries', $ydb->num_queries ), $ydb->num_queries );
	?>
	</div> 	
	<div class="footer">
		<!-- <a href="#">About</a> | <a href="#">Help</a> | <a href="#">Contact</a> -->
		<span class="margin20_L">Lycos URL Shortener developed by lycos.com</span> <span class="margin20_L">Copyright © <?php echo $year;?> lycos.com</span>
	</div>
<!--footer end-->
<!-- OwnerIQ Analytics tag -->
	<script type="text/javascript">
	var _oiqq = _oiqq || [];
	_oiqq.push(['oiq_doTag']);

	(function() {
	var oiq = document.createElement('script'); oiq.type = 'text/javascript'; oiq.async = true;
	oiq.src = document.location.protocol + '//px.owneriq.net/stas/s/lycoss.js';
	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(oiq, s);
	})();
	</script>
<!-- End OwnerIQ tag -->
    
</body>
</html>
<?php }

/**
 * Display "Add new URL" box
 *
 * @param string $url URL to prefill the input with
 * @param string $keyword Keyword to prefill the input with
 */
function yourls_html_addnew( $url = '', $keyword = '' ) {
	$url = $url ? $url : 'http://';
	?>
	<div id="new_url">
		<div>
			<form id="new_url_form" action="" method="get">
				<div><strong><?php yourls_e( 'Enter the URL' ); ?></strong> : <input type="text" id="add-url" name="url" value="<?php echo $url; ?>" class="text" size="80" />
				<?php yourls_e( 'Optional '); ?>: <strong><?php yourls_e('Custom short URL'); ?> : </strong><input type="text" id="add-keyword" name="keyword" value="<?php echo $keyword; ?>" class="text" size="8" />
				<?php yourls_nonce_field( 'add_url', 'nonce-add' ); ?>
				<input type="button" id="add-button" name="add-button" value="<?php yourls_e( 'Shorten the URL' ); ?>" class="button" onclick="add_link();" /></div>
			</form>
			<div id="feedback" style="display:none"></div>
		</div>
		<?php yourls_do_action( 'html_addnew' ); ?>
	</div>
	<?php 
}

/**
 * Display main table's footer
 *
 * The $param array is defined in /admin/index.php, check the yourls_html_tfooter() call
 *
 * @param array $params Array of all required parameters
 * @return string Result
 */
function yourls_html_tfooter( $params = array() ) {
	extract( $params ); // extract $search_text, $page, $search_in ...
	?>
	<tfoot>
		<tr>
			<th colspan="6">

<div id="pagination">
				<span class="navigation">
				<?php if( $total_pages > 1 ) { ?>
					<span class="nav_total"><?php echo sprintf( yourls_n( '1 page', '%s pages', $total_pages ), $total_pages ); ?></span>
					<?php
					$base_page = yourls_admin_url( 'index.php' );
					// Pagination offsets: min( max ( zomg! ) );
					$p_start = max(  min( $total_pages - 4, $page - 2 ), 1 );
					$p_end = min( max( 5, $page + 2 ), $total_pages );
					if( $p_start >= 2 ) {
						$link = yourls_add_query_arg( array_merge( $params, array( 'page' => 1 ) ), $base_page );
						echo '<span class="nav_link nav_first"><a href="' . $link . '" title="' . yourls_esc_attr__('Go to First Page') . '">' . yourls__( '&laquo; First' ) . '</a></span>';
						echo '<span class="nav_link nav_prev"></span>';
					}
					for( $i = $p_start ; $i <= $p_end; $i++ ) {
						if( $i == $page ) {
							echo "<span class='nav_link nav_current'>$i</span>";
						} else {
							$link = yourls_add_query_arg( array_merge( $params, array( 'page' => $i ) ), $base_page );
							echo '<span class="nav_link nav_goto"><a href="' . $link . '" title="' . sprintf( yourls_esc_attr( 'Page %s' ), $i ) .'">'.$i.'</a></span>';
						}
					}
					if( ( $p_end ) < $total_pages ) {
						$link = yourls_add_query_arg( array_merge( $params, array( 'page' => $total_pages ) ), $base_page );
						echo '<span class="nav_link nav_next"></span>';
						echo '<span class="nav_link nav_last"><a href="' . $link . '" title="' . yourls_esc_attr__('Go to First Page') . '">' . yourls__( 'Last &raquo;' ) . '</a></span>';
					}
					?>
				<?php } ?>
				</span>
			</div>


			<div id="filter_form">
				<form action="" method="get">
					<div id="filter_options">
						<?php
						
						// First search control: text to search
						$_input = '<input type="text" name="search" class="text" size="12" value="' . yourls_esc_attr( $search_text ) . '" />';
						$_options = array(
							'keyword' => yourls__( 'Short URL' ),
							'url'     => yourls__( 'URL' ),
							'title'   => yourls__( 'Title' ),
							'ip'      => yourls__( 'IP' ),
						);							
						$_select = yourls_html_select( 'search_in', $_options, $search_in );
						/* //translators: "Search for <input field with text to search> in <select dropdown with URL, title...>" */
						yourls_se( 'Search for %1$s in %2$s', $_input , $_select );
						echo "&ndash;\n";
						
						// Second search control: order by
						$_options = array(
							'keyword'      => yourls__( 'Short URL' ),
							'url'          => yourls__( 'URL' ),
							'timestamp'    => yourls__( 'Date' ),
							'ip'           => yourls__( 'IP' ),
							'clicks'       => yourls__( 'Clicks' ),
						);
						$_select = yourls_html_select( 'sort_by', $_options, $sort_by );
						$sort_order = isset( $sort_order ) ? $sort_order : 'desc' ;
						$_options = array(
							'asc'  => yourls__( 'Ascending' ),
							'desc' => yourls__( 'Descending' ),
						);
						$_select2 = yourls_html_select( 'sort_order', $_options, $sort_order );
						/* //translators: "Order by <criteria dropdown (date, clicks...)> in <order dropdown (Descending or Ascending)>" */
						yourls_se( 'Order by %1$s %2$s', $_select , $_select2 );
						echo "&ndash;\n";
						
						// Third search control: Show XX rows
						/* //translators: "Show <text field> rows" */
						yourls_se( 'Show %s rows',  '<input type="text" name="perpage" class="text" size="2" value="' . $perpage . '" />' );
						echo "<br/>\n";

						// Fourth search control: Show links with more than XX clicks
						$_options = array(
							'more' => yourls__( 'more' ),
							'less' => yourls__( 'less' ),
						);
						$_select = yourls_html_select( 'click_filter', $_options, $click_filter );
						$_input  = '<input type="text" name="click_limit" class="text" size="4" value="' . $click_limit . '" /> ';
						/* //translators: "Show links with <more/less> than <text field> clicks" */
						yourls_se( 'Show links with %1$s than %2$s clicks', $_select, $_input );
						echo "<br/>\n";

						// Fifth search control: Show links created before/after/between ...
						$_options = array(
							'before'  => yourls__('before'),
							'after'   => yourls__('after'),
							'between' => yourls__('between'),
						);
						$_select = yourls_html_select( 'date_filter', $_options, $date_filter );
						$_input  = '<input type="text" name="date_first" id="date_first" class="text" size="12" value="' . $date_first . '" />';
						$_and    = '<span id="date_and"' . ( $date_filter === 'between' ? ' style="display:inline"' : '' ) . '> &amp; </span>';
						$_input2 = '<input type="text" name="date_second" id="date_second" class="text" size="12" value="' . $date_second . '"' . ( $date_filter === 'between' ? ' style="display:inline"' : '' ) . '/>';
						/* //translators: "Show links created <before/after/between> <date input> <"and" if applicable> <date input if applicable>" */
						yourls_se( 'Show links created %1$s %2$s %3$s %4$s', $_select, $_input, $_and, $_input2 );
						?>

						<div id="filter_buttons">
							<input type="submit" id="submit-sort" value="<?php yourls_e('Search'); ?>" class="button primary" />
							&nbsp;
							<input type="button" id="submit-clear-filter" value="<?php yourls_e('Clear'); ?>" class="button" onclick="window.parent.location.href = 'index.php'" />
						</div>
				
					</div>
				</form>
			</div>
			
			<?php
			// Remove empty keys from the $params array so it doesn't clutter the pagination links
			$params = array_filter( $params, 'yourls_return_if_not_empty_string' ); // remove empty keys

			if( isset( $search_text ) ) {
				$params['search'] = $search_text;
				unset( $params['search_text'] );
			}
			?>

			</th>
		</tr>
		<?php yourls_do_action( 'html_tfooter' ); ?>
	</tfoot>
	<?php
}

/**
 * Return a select box
 *
 * @since 1.6
 *
 * @param string $name HTML 'name' (also use as the HTML 'id')
 * @param array $options array of 'value' => 'Text displayed'
 * @param string $selected optional 'value' from the $options array that will be highlighted
 * @param boolean $display false (default) to return, true to echo
 * @return string HTML content of the select element
 */
function yourls_html_select( $name, $options, $selected = '', $display = false ) {
	$html = "<select name='$name' id='$name' size='1'>\n";
	foreach( $options as $value => $text ) {
		$html .= "<option value='$value' ";
		$html .= $selected == $value ? ' selected="selected"' : '';
		$html .= ">$text</option>\n";
	}
	$html .= "</select>\n";
	$html  = yourls_apply_filters( 'html_select', $html, $name, $options, $selected, $display );
	if( $display )
		echo $html;
	return $html;
}

/**
 * Display the Quick Share box
 *
 */
function yourls_share_box( $longurl, $shorturl, $title = '', $text='', $shortlink_title = '', $share_title = '', $hidden = false ) {
	if ( $shortlink_title == '' )
		$shortlink_title = '<h2>' . yourls__( 'Your short link' ) . '</h2>';
	if ( $share_title == '' )
		$share_title = '<h2>' . yourls__( 'Quick Share' ) . '</h2>';
	
	// Allow plugins to short-circuit the whole function
	$pre = yourls_apply_filter( 'shunt_share_box', false );
	if ( false !== $pre )
		return $pre;
		
	$text   = ( $text ? '"'.$text.'" ' : '' );
	$title  = ( $title ? "$title " : '' );
	$share  = yourls_esc_textarea( $title.$text.$shorturl );
	$count  = 140 - strlen( $share );
	$hidden = ( $hidden ? 'style="display:none;"' : '' );
	
	// Allow plugins to filter all data
	$data = compact( 'longurl', 'shorturl', 'title', 'text', 'shortlink_title', 'share_title', 'share', 'count', 'hidden' );
	$data = yourls_apply_filter( 'share_box_data', $data );
	extract( $data );
	
	$_share = rawurlencode( $share );
	$_url   = rawurlencode( $shorturl );
	?>
	
	<div id="shareboxes" <?php echo $hidden; ?>>

		<?php yourls_do_action( 'shareboxes_before', $longurl, $shorturl, $title, $text ); ?>

		<div id="copybox" class="share">
		<?php echo $shortlink_title; ?>
			<p><input id="copylink" class="text width90"  value="<?php echo yourls_esc_url( $shorturl ); ?>" /></p>
			<p><small><?php yourls_e( 'Long link' ); ?>: <a target="_blank" id="origlink" href="<?php echo yourls_esc_url( $longurl ); ?>"><?php echo yourls_esc_url( $longurl ); ?></a></small>
			<?php if( yourls_do_log_redirect() ) { ?>
			<br/><small><?php yourls_e( 'Stats' ); ?>: <a id="statlink" target="_blank" href="<?php echo yourls_esc_url( $shorturl ); ?>+"><?php echo yourls_esc_url( $shorturl ); ?>+</a></small>
			<input type="hidden" id="titlelink" value="<?php echo yourls_esc_attr( $title ); ?>" />
			<?php } ?>
			</p>
		</div>
		<!-- <div style="display: table-cell; width: 15px;"></div> -->
		<?php yourls_do_action( 'shareboxes_middle', $longurl, $shorturl, $title, $text ); ?>

		<div id="sharebox" class="share">
			<?php echo $share_title; ?>
			<div id="tweet">
				<!-- <span id="charcount" class="hide-if-no-js"><?php //echo $count; ?></span> -->
				<textarea id="tweet_body"><?php echo $share; ?></textarea>
			</div>
			<p id="share_links"><?php yourls_e( 'Share with' ); ?> 
				<a id="share_tw" href="http://twitter.com/home?status=<?php echo $_share; ?>" title="<?php yourls_e( 'Tweet this!' ); ?>" onclick="share('tw');return false">Twitter</a>
				<a id="share_fb" href="http://www.facebook.com/share.php?u=<?php echo $_url; ?>" title="<?php yourls_e( 'Share on Facebook' ); ?>" onclick="share('fb');return false;">Facebook</a>
				<!--<a id="share_ff" href="http://friendfeed.com/share/bookmarklet/frame#title=<?php echo $_share; ?>" title="<?php yourls_e( 'Share on Friendfeed' ); ?>" onclick="share('ff');return false;">FriendFeed</a>-->
				<?php
				yourls_do_action( 'share_links', $longurl, $shorturl, $title, $text );
				// Note: on the main admin page, there are no parameters passed to the sharebox when it's drawn.
				?>
			</p>
		</div>
		
		<?php yourls_do_action( 'shareboxes_after', $longurl, $shorturl, $title, $text ); ?>
	
	</div>
	
	<?php
}

/**
 * Die die die
 *
 */
function yourls_die( $message = '', $title = '', $header_code = 200 ) {
	yourls_status_header( $header_code );
	
	if( !yourls_did_action( 'html_head' ) ) {
		yourls_html_head();
		yourls_html_logo();
	}
	echo yourls_apply_filter( 'die_title', "<h2>$title</h2>" );
	echo yourls_apply_filter( 'die_message', "<p>$message</p>" );
	yourls_do_action( 'yourls_die' );
	if( !yourls_did_action( 'html_head' ) ) {
		yourls_html_footer();
	}
	die();
}

/**
 * Return an "Edit" row for the main table
 *
 * @param string $keyword Keyword to edit
 * @return string HTML of the edit row
 */
function yourls_table_edit_row( $keyword ) {
	$keyword = yourls_sanitize_string( $keyword );
	$id = yourls_string2htmlid( $keyword ); // used as HTML #id
	$url = yourls_get_keyword_longurl( $keyword );
	
	$title = htmlspecialchars( yourls_get_keyword_title( $keyword ) );
	$safe_url = yourls_esc_attr( $url );
	$safe_title = yourls_esc_attr( $title );
	$www = yourls_link();
	
	$nonce = yourls_create_nonce( 'edit-save_'.$id );
	
	if( $url ) {
		$return = <<<RETURN
<tr id="edit-$id" class="edit-row"><td colspan="5" class="edit-row"><div class="row"><div class="width10 margin7_T">%s : </div><input type="text" id="edit-url-$id" name="edit-url-$id" value="$safe_url" class="text" size="70" /></div><div class="row"><div class="width10 margin7_T">%s : </div> <div class="width90">$www<input type="text" id="edit-keyword-$id" name="edit-keyword-$id" value="$keyword" class="text" size="10" /></div></div><div class="row"><div class="width10 margin7_T">%s : </div> <input type="text" id="edit-title-$id" name="edit-title-$id" value="$safe_title" class="text" size="60" /></div></td><td colspan="1" align="center" class="btnstd"><input type="button" id="edit-submit-$id" name="edit-submit-$id" value="%s" title="%s" class="button" onclick="edit_link_save('$id');" />&nbsp;<input type="button" id="edit-close-$id" name="edit-close-$id" value="%s" title="%s" class="button" onclick="edit_link_hide('$id');" /><input type="hidden" id="old_keyword_$id" value="$keyword"/><input type="hidden" id="nonce_$id" value="$nonce"/></td></tr>
RETURN;
		$return = sprintf( urldecode( $return ), yourls__( 'Long URL' ), yourls__( 'Short URL' ), yourls__( 'Title' ), yourls__( 'Save' ), yourls__( 'Save new values' ), yourls__( 'Cancel' ), yourls__( 'Cancel editing' ) );
	} else {
		$return = '<tr class="edit-row notfound"><td colspan="6" class="edit-row notfound">' . yourls__( 'Error, URL not found' ) . '</td></tr>';
	}
	
	$return = yourls_apply_filter( 'table_edit_row', $return, $keyword, $url, $title );

	return $return;
}

/**
 * Return an "Add" row for the main table
 *
 * @return string HTML of the edit row
 */
function yourls_table_add_row( $keyword, $url, $title = '', $ip, $clicks, $timestamp ) {
	$keyword  = yourls_sanitize_string( $keyword );
	$id       = yourls_string2htmlid( $keyword ); // used as HTML #id
	$shorturl = yourls_link( $keyword );

	$statlink = yourls_statlink( $keyword );
		
	$delete_link = yourls_nonce_url( 'delete-link_'.$id,
		yourls_add_query_arg( array( 'id' => $id, 'action' => 'delete', 'keyword' => $keyword ), yourls_admin_url( 'admin-ajax.php' ) ) 
	);
	
	$edit_link = yourls_nonce_url( 'edit-link_'.$id,
		yourls_add_query_arg( array( 'id' => $id, 'action' => 'edit', 'keyword' => $keyword ), yourls_admin_url( 'admin-ajax.php' ) ) 
	);
	
	// Action link buttons: the array
	$actions = array(
		'stats' => array(
			'href'    => $statlink,
			'id'      => "statlink-$id",
			'title'   => yourls_esc_attr__( 'Stats' ),
			'anchor'  => yourls__( 'Stats' ),

		),
		'share' => array(
			'href'    => '',
			'id'      => "share-button-$id",
			'title'   => yourls_esc_attr__( 'Share' ),
			'anchor'  => yourls__( 'Share' ),
			'onclick' => "toggle_share('$id');return false;",
		),
		'edit' => array(
			'href'    => $edit_link,
			'id'      => "edit-button-$id",
			'title'   => yourls_esc_attr__( 'Edit' ),
			'anchor'  => yourls__( 'Edit' ),
			'onclick' => "edit_link_display('$id');return false;",
		),
		'delete' => array(
			'href'    => $delete_link,
			'id'      => "delete-button-$id",
			'title'   => yourls_esc_attr__( 'Delete' ),
			'anchor'  => yourls__( 'Delete' ),
			'onclick' => "remove_link('$id');return false;",
		)
	);
	$actions = yourls_apply_filter( 'table_add_row_action_array', $actions );
	
	// Action link buttons: the HTML
	$action_links = '';
	foreach( $actions as $key => $action ) {
		$onclick = isset( $action['onclick'] ) ? 'onclick="' . $action['onclick'] . '"' : '' ;
		if($action['title']=='Stats'){
			$action_links .= sprintf( '<a target="_blank" href="%s" id="%s" title="%s" class="%s" %s>%s</a>',
			$action['href'], $action['id'], $action['title'], 'button button_'.$key, $onclick, $action['anchor']
		);
		}else{
			$action_links .= sprintf( '<a href="%s" id="%s" title="%s" class="%s" %s>%s</a>',
			$action['href'], $action['id'], $action['title'], 'button button_'.$key, $onclick, $action['anchor']
		);
		}
	}
	$action_links = yourls_apply_filter( 'action_links', $action_links, $keyword, $url, $ip, $clicks, $timestamp );

	if( ! $title )
		$title = $url;

	$protocol_warning = '';
	if( ! in_array( yourls_get_protocol( $url ) , array( 'http://', 'https://' ) ) )
		$protocol_warning = yourls_apply_filters( 'add_row_protocol_warning', '<span class="warning" title="' . yourls__( 'Not a common link' ) . '">&#9733;</span>' );

	// Row cells: the array
	$cells = array(
		'keyword' => array(
			'template'      => '<a href="%shorturl%">%keyword_html%</a>',
			'shorturl'      => yourls_esc_url( $shorturl ),
			'keyword_html'  => yourls_esc_html( $keyword ),
		),
		'url' => array(
			'template'      => '<a href="%long_url%" title="%title_attr%">%title_html%</a><br/><small>%warning%<a href="%long_url%">%long_url_html%</a></small>',
			'long_url'      => yourls_esc_url( $url ),
			'title_attr'    => yourls_esc_attr( $title ),
			'title_html'    => yourls_esc_html( yourls_trim_long_string( $title ) ),
			'long_url_html' => yourls_esc_html( yourls_trim_long_string( $url ) ),
			'warning'       => $protocol_warning,
		),
		'timestamp' => array(
			'template' => '%date%',
			'date'     => date( 'M d, Y H:i', $timestamp +( YOURLS_HOURS_OFFSET * 3600 ) ),
		),
		'ip' => array(
			'template' => '%ip%',
			'ip'       => $ip,
		),
		'clicks' => array(
			'template' => '%clicks%',
			'clicks'   => yourls_number_format_i18n( $clicks, 0, '', '' ),
		),
		'actions' => array(
			'template' => '%actions% <input type="hidden" id="keyword_%id%" value="%keyword%"/>',
			'actions'  => $action_links,
			'id'       => $id,
			'keyword'  => $keyword,
		),
	);
	$cells = yourls_apply_filter( 'table_add_row_cell_array', $cells, $keyword, $url, $title, $ip, $clicks, $timestamp );
	
	// Row cells: the HTML. Replace every %stuff% in 'template' with 'stuff' value.
	$row = "<tr id=\"id-$id\">";
	foreach( $cells as $cell_id => $elements ) {
		$callback = new yourls_table_add_row_callback( $elements );
		$row .= sprintf( '<td class="%s" id="%s">', $cell_id, $cell_id . '-' . $id );
		$row .= preg_replace_callback( '/%([^%]+)?%/', array( $callback, 'callback' ), $elements['template'] );
		// For the record, in PHP 5.3+ we don't need to introduce a class in order to pass additional parameters
		// to the callback function. Instead, we would have used the 'use' keyword :
		// $row .= preg_replace_callback( '/%([^%]+)?%/', function( $match ) use ( $elements ) { return $elements[ $match[1] ]; }, $elements['template'] );
		
		$row .= '</td>';
	}
	$row .= "</tr>";
	$row  = yourls_apply_filter( 'table_add_row', $row, $keyword, $url, $title, $ip, $clicks, $timestamp );
	
	return $row;
}

/**
 * Callback class for yourls_table_add_row
 *
 * See comment about PHP 5.3+ in yourls_table_add_row()
 *
 * @since 1.7
 */
class yourls_table_add_row_callback {
    private $elements;
	
    function __construct($elements) {
		$this->elements = $elements;
	}
	
    function callback( $matches ) {
		return $this->elements[ $matches[1] ];
    }
}


/**
 * Echo the main table head
 *
 */
function yourls_table_head() {
	$start = '<table id="dashboard_main_table" class="tblSorter" cellpadding="0" cellspacing="1"><input type="hidden" name="show_row" id="show_row" value="" /><input type="hidden" name="show_share" id="show_share" value="" /><input type="hidden" name="tr_class" id="tr_class" value="" /><thead><tr>'."\n";
	echo yourls_apply_filter( 'table_head_start', $start );
	
	$cells = yourls_apply_filter( 'table_head_cells', array(
		'shorturl' => yourls__( 'Short URL' ),
		'longurl'  => yourls__( 'Original URL' ),
		'date'     => yourls__( 'Date' ),
		'ip'       => yourls__( 'IP' ),
		'clicks'   => yourls__( 'Clicks' ),
		'actions'  => yourls__( 'Actions' )
	) );
	foreach( $cells as $k => $v ) {
		echo "<th id='dashboard_main_table_head_$k'>$v</th>\n";
	}
	
	$end = "</tr></thead>\n";
	echo yourls_apply_filter( 'table_head_end', $end );
}

/**
 * Echo the tbody start tag
 *
 */
function yourls_table_tbody_start() {
	echo yourls_apply_filter( 'table_tbody_start', '<tbody >' );
}

/**
 * Echo the tbody end tag
 *
 */
function yourls_table_tbody_end() {
	echo yourls_apply_filter( 'table_tbody_end', '</tbody>' );
}

/**
 * Echo the table start tag
 *
 */
function yourls_table_end() {
	echo yourls_apply_filter( 'table_end', '</table>' );
}

/**
 * Echo HTML tag for a link
 *
 */
function yourls_html_link( $href, $title = '', $element = '' ) {
	if( !$title )
		$title = $href;
	if( $element )
		$element = sprintf( 'id="%s"', yourls_esc_attr( $element ) );
	$link = sprintf( '<a href="%s" %s>%s</a>', yourls_esc_url( $href ), $element, yourls_esc_html( $title ) );
	echo yourls_apply_filter( 'html_link', $link );
}

/**
 * Display the login screen. Nothing past this point.
 *
 */
function yourls_login_screen( $error_msg = '' ) {
	yourls_html_head( 'login' );
	
	$action = ( isset( $_GET['action'] ) && $_GET['action'] == 'logout' ? '?' : '' );

	//yourls_html_logo();
	?>
	<style type="text/css">
	body, html {height: 100%;}
	.home {background:url(../images/bg.jpg) repeat-x 0 bottom; background-attachment:fixed;}
	#wrap {height:auto; padding:60px 0 150px 0; max-width: 100%;}
	</style>
	<div class="contentarea homecontent">
	<div class="homeinner">
	<p class="center"><a href="<?php yourls_site_url(); ?>" title="lyc.so"><img src="images/lycsoLogo.png" alt="lyc.so" title="lyc.so" /></a></p>
	<div id="login">
		<div class="logintitle">Please Sign In</div>
	
		  <?php if (isset($_REQUEST['status']) && $_REQUEST['status']== 1 ){
          echo "<div class='success'>Activated Successfully. Please Sign In.</div>";
          }?>

          <?php if (isset($_REQUEST['status']) && $_REQUEST['status']== 0 ){
          //echo "<div class='errormessage'>Activation Failed/. Please try again.</div>";
          echo "<div class='errormessage'>Activation Link Expired.</div>";
          }?>
		<form method="post" name="loginpage" action="<?php echo $action; ?>" onsubmit="return loginValidateForm();"> <?php // reset any QUERY parameters ?>
			<?php
				//if( !empty( $error_msg ) ) {
				if( !empty( $error_msg ) && $error_msg!='Please Sign In') {
					echo '<p class="error">'.$error_msg.'</p>';
				}
			?>
			<p>
				<label for="username"><?php yourls_e( 'Email Address' ); ?>:</label>
				<input type="text" id="username" name="username" size="30" class="text" />
			</p>
			<p>
				<label for="password"><?php yourls_e( 'Password' ); ?>:</label>
				<input type="password" id="password" name="password" size="30" class="text" />
			</p>
			<p>
				<label>&nbsp;</label>
				<input type="submit" id="submit" name="submit" value="<?php yourls_e( 'Sign In' ); ?>" />
			</p>
			<p>
				<label>&nbsp;</label>
				<a href="forgotpassword.php" name="forgotpassword" value="forgotpassword">Forgot Password</a>

			</p>
			<p>
				
				
				 <strong>Please <a href="register.php">Sign Up</a> if you dont have an account</strong>
			</p>
		</form>
		<script type="text/javascript">$('#username').focus();</script>
	</div>
</div>
</div>


	<?php
	yourls_html_footer();
	?>
	<!-- new code -->
	<div class="add">			
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
	</div>

<!-- end -->
<?php
	die();
}

/**
 * Display the admin menu
 *id="admin_menu_logout_link" class="topbar"
 */
function yourls_html_menu() {

	// Build menu links
	if( defined( 'YOURLS_USER' ) ) {
		$logout_link = yourls_apply_filter( 'logout_link', sprintf( yourls__('Hello <strong class="hellouser">%s</strong>'), $firstname ) . '<a class="logout" title="Sign Out" href="?action=logout"' . yourls_esc_attr__( 'Sign out' ) . '">' . yourls__( 'Logout' ) . '</a>' );
	} else {
		$logout_link = yourls_apply_filter( 'logout_link', '' );
	}
	$help_link   = yourls_apply_filter( 'help_link',   '<a href="' . yourls_site_url( false ) .'/readme.html">' . yourls__( 'Help' ) . '</a>' );
	
	$admin_links    = array();
	$admin_sublinks = array();
	
	if(yourls_check_user()) {
		$admin_links['user'] = array(
			'url'    => yourls__( 'user.php' ),
			'title'  => yourls__( 'User Interface' ),
			'anchor' => yourls__( 'User Interface' ),
		);
		$admin_links['signature'] = array(
			'url'    => 'signature.php',
			'title'  => yourls__( 'API Key' ),
			'anchor' => yourls__( 'API Key' ),
			
		);
		$admin_links['stats'] = array(
			'url'    => 'stats.php',
			'title'  => yourls__( 'Stats' ),
			'anchor' => yourls__( 'Stats' ),
			
		);
		$admin_links['search'] = array(
			'url'    => 'http://search.lycos.com',
			'title'  => yourls__( 'Lycos.com' ),
			'anchor' => yourls__( 'Search' ),
			
		);
		$admin_links['mail'] = array(
			'url'    => 'http://mail.lycos.com',
			'title'  => yourls__( 'mail.lycos.com' ),
			'anchor' => yourls__( 'Mail' ),
			
		);
		$admin_links['tripod'] = array(
			'url'    => 'http://tripod.lycos.com',
			'title'  => yourls__( 'Tripod' ),
			'anchor' => yourls__( 'Tripod' ),
			
		);
		$admin_links['gamesville'] = array(
			'url'    => 'http://gamesville.com',
			'title'  => yourls__( 'Gamesville.com' ),
			'anchor' => yourls__( 'Gamesville' ),
			
		);

	}else{
		$admin_links['admin'] = array(
			'url'    => yourls__( 'user.php' ),
			'title'  => yourls__( 'Admin Interface' ),
			'anchor' => yourls__( 'Admin Interface' ),
		);
		// $admin_links['tools'] = array(
		// 	'url'    => yourls_admin_url( 'tools.php' ),
		// 	'anchor' => yourls__( 'Tools' )
		// );		
		$admin_links['plugins'] = array(
			'url'    => yourls__( 'plugins.php' ),
			'title'  => yourls__( 'Manage Plugins' ),
			'anchor' => yourls__( 'Manage Plugins' ),
			
		);
		$admin_links['users'] = array(
			'url'    => yourls__( 'manage_users.php' ),
			'title'  => yourls__( 'Manage Users' ),
			'anchor' => yourls__( 'Manage Users' ),
			
		);
		$admin_links['signature'] = array(
			'url'    => 'signature.php',
			'title'  => yourls__( 'API Key' ),
			'anchor' => yourls__( 'API Key' ),
			
		);
		$admin_links['stats'] = array(
			'url'    => 'stats.php',
			'title'  => yourls__( 'Stats' ),
			'anchor' => yourls__( 'Stats' ),
			
		);
		$admin_links['search'] = array(
			'url'    => 'http://search.lycos.com',
			'title'  => yourls__( 'Lycos.com' ),
			'anchor' => yourls__( 'Search' ),
			
		);
		$admin_links['mail'] = array(
			'url'    => 'http://mail.lycos.com',
			'title'  => yourls__( 'mail.lycos.com' ),
			'anchor' => yourls__( 'Mail' ),
			
		);
		$admin_links['tripod'] = array(
			'url'    => 'http://tripod.lycos.com',
			'title'  => yourls__( 'Tripod' ),
			'anchor' => yourls__( 'Tripod' ),
			
		);
		$admin_links['gamesville'] = array(
			'url'    => 'http://gamesville.com',
			'title'  => yourls__( 'Gamesville.com' ),
			'anchor' => yourls__( 'Gamesville' ),
			
		);
		
		
		// $admin_links['more'] = array(
		// 	'url'    => 'http://domains.lycos.com',			
		// 	'title'  => yourls__( 'More Lycos Sites' ),
		// 	'anchor' => yourls__( 'More' ),
			
		// );
		$admin_sublinks['plugins'] = yourls_list_plugin_admin_pages();
		// $admin_sublinks['more'] = array(
		// 	'url'	=> 'http://domains.lycos.com',
		// 	'title'  => yourls__( 'Lycos Domains' ),
		// 	'anchor' => yourls__( 'Lycos Domains' ),
			
		// );
	}
	// if( yourls_check_admin() ) {
	// 	$admin_links['tools'] = array(
	// 		'url'    => yourls_admin_url( 'tools.php' ),
	// 		'anchor' => yourls__( 'Tools' )
	// 	);		
	// 	$admin_links['plugins'] = array(
	// 		'url'    => yourls_admin_url( 'plugins.php' ),
	// 		'title'  => yourls__( 'Manage Plugins' ),
	// 		'anchor' => yourls__( 'Manage Plugins' ),
			
	// 	);
	// 	$admin_links['users'] = array(
	// 		'url'    => yourls_admin_url( 'manage_users.php' ),
	// 		'title'  => yourls__( 'Manage Users' ),
	// 		'anchor' => yourls__( 'Manage Users' ),
			
	// 	);
	// 	$admin_links['search'] = array(
	// 		'url'    => 'http://search.lycos.com',
	// 		'title'  => yourls__( 'Lycos.com' ),
	// 		'anchor' => yourls__( 'Search' ),
			
	// 	);
	// 	$admin_links['mail'] = array(
	// 		'url'    => 'http://mail.lycos.com',
	// 		'title'  => yourls__( 'mail.lycos.com' ),
	// 		'anchor' => yourls__( 'Mail' ),
			
	// 	);
	// 	$admin_links['tripod'] = array(
	// 		'url'    => 'http://tripod.lycos.com',
	// 		'title'  => yourls__( 'Tripod' ),
	// 		'anchor' => yourls__( 'Tripod' ),
			
	// 	);
	// 	$admin_links['gamesville'] = array(
	// 		'url'    => 'http://gamesville.com',
	// 		'title'  => yourls__( 'Gamesville.com' ),
	// 		'anchor' => yourls__( 'Gamesville' ),
			
	// 	);
	// 	// $admin_links['more'] = array(
	// 	// 	'url'    => 'http://domains.lycos.com',			
	// 	// 	'title'  => yourls__( 'More Lycos Sites' ),
	// 	// 	'anchor' => yourls__( 'More' ),
			
	// 	// );
	// 	$admin_sublinks['plugins'] = yourls_list_plugin_admin_pages();
	// 	// $admin_sublinks['more'] = array(
	// 	// 	'url'	=> 'http://domains.lycos.com',
	// 	// 	'title'  => yourls__( 'Lycos Domains' ),
	// 	// 	'anchor' => yourls__( 'Lycos Domains' ),
			
	// 	// );
	// }
	
	$admin_links    = yourls_apply_filter( 'admin_links',    $admin_links );
	$admin_sublinks = yourls_apply_filter( 'admin_sublinks', $admin_sublinks );
	
	// Now output menu
	//echo '<ul id="admin_menu">'."\n";
	if ( yourls_is_private() && !empty( $logout_link ) )
		//echo '<li id="admin_menu_logout_link" class="topbar">' . $logout_link .'</li>';

	foreach( (array)$admin_links as $link => $ar ) {//echo $ar['img'];
		if( isset( $ar['url'] ) ) {
			$anchor = isset( $ar['anchor'] ) ? $ar['anchor'] : $link;
			$title  = isset( $ar['title'] ) ? 'title="' . $ar['title'] . '"' : '';
			if($ar['title']!='Admin Interface' && $ar['title']!='Manage Plugins' && $ar['title']!='Manage Users' && $ar['title']!='User Interface' && $ar['title']!='API Key' && $ar['title']!='Stats'){
			printf( '<span id="admin_menu_%s_link"><a target="_blank" href="%s" %s>'.$ar['img'].'%s</a></span>', $link, $ar['url'], $title, $anchor );
			}else{
			printf( '<span id="admin_menu_%s_link"><a href="%s" %s>'.$ar['img'].'%s</a></span>', $link, $ar['url'], $title, $anchor );
			}
		}
		// Output submenu if any. TODO: clean up, too many code duplicated here
		if( isset( $admin_sublinks[$link] ) ) {
			
			foreach( $admin_sublinks[$link] as $link => $ar ) {
				if( isset( $ar['url'] ) ) {
					$anchor = isset( $ar['anchor'] ) ? $ar['anchor'] : $link;
					$title  = isset( $ar['title'] ) ? 'title="' . $ar['title'] . '"' : '';
					printf( '<a target="_blank" href="%s" %s>%s</a>', $link, $link, $ar['url'], $title, $anchor );
				}
			}
			
		}
	}
	
	if ( isset( $help_link ) )
		//echo '<li id="admin_menu_help_link">' . $help_link .'</li>';
		
	yourls_do_action( 'admin_menu' );


	yourls_do_action( 'admin_notices' );
	yourls_do_action( 'admin_notice' ); // because I never remember if it's 'notices' or 'notice'
	/*
	To display a notice:
	$message = "<div>OMG, dude, I mean!</div>" );
	yourls_add_action( 'admin_notices', create_function( '', "echo '$message';" ) );
	*/
}

/**
 * Wrapper function to display admin notices
 *
 */
function yourls_add_notice( $message, $style = 'notice' ) {
	// Escape single quotes in $message to avoid breaking the anonymous function
	$message = yourls_notice_box( strtr( $message, array( "'" => "\'" ) ), $style );
	yourls_add_action( 'admin_notices', create_function( '', "echo '$message';" ) );
}

/**
 * Return a formatted notice
 *
 */
function yourls_notice_box( $message, $style = 'notice' ) {
	return <<<HTML
	<div class="$style">
	<p>$message</p>
	</div>
HTML;
}

/**
 * Display a page
 *
 */
function yourls_page( $page ) {
	$include = YOURLS_ABSPATH . "/pages/$page.php";
	if( !file_exists( $include ) ) {
		yourls_die( "Page '$page' not found", 'Not found', 404 );
	}
	yourls_do_action( 'pre_page', $page );
	include_once( $include );
	yourls_do_action( 'post_page', $page );
	die();	
}

/**
 * Display the language attributes for the HTML tag.
 *
 * Builds up a set of html attributes containing the text direction and language
 * information for the page. Stolen from WP.
 *
 * @since 1.6
 */
function yourls_html_language_attributes() {
	$attributes = array();
	$output = '';
	
	$attributes[] = ( yourls_is_rtl() ? 'dir="rtl"' : 'dir="ltr"' );
	
	$doctype = yourls_apply_filters( 'html_language_attributes_doctype', 'html' );
	// Experimental: get HTML lang from locale. Should work. Convert fr_FR -> fr-FR
	if ( $lang = str_replace( '_', '-', yourls_get_locale() ) ) {
		if( $doctype == 'xhtml' ) {
			$attributes[] = "xml:lang=\"$lang\"";
		} else {
			$attributes[] = "lang=\"$lang\"";
		}
	}

	$output = implode( ' ', $attributes );
	$output = yourls_apply_filters( 'html_language_attributes', $output );
	echo $output;
}

/**
 * Output translated strings used by the Javascript calendar
 *
 * @since 1.6
 */
function yourls_l10n_calendar_strings() {
	echo "\n<script>\n";
	echo "var l10n_cal_month = " . json_encode( array_values( yourls_l10n_months() ) ) . ";\n";
	echo "var l10n_cal_days = " . json_encode( array_values( yourls_l10n_weekday_initial() ) ) . ";\n";
	echo "var l10n_cal_today = \"" . yourls_esc_js( yourls__( 'Today' ) ) . "\";\n";
	echo "var l10n_cal_close = \"" . yourls_esc_js( yourls__( 'Close' ) ) . "\";\n";
	echo "</script>\n";
	
	// Dummy returns, to initialize l10n strings used in the calendar
	yourls__( 'Today' );
	yourls__( 'Close' );
}


/**
 * Display a notice if there is a newer version of YOURLS available
 *
 * @since 1.7
 */
function yourls_new_core_version_notice() {

	yourls_debug_log( 'Check for new version: ' . ( yourls_maybe_check_core_version() ? 'yes' : 'no' ) );
	
	$checks = yourls_get_option( 'core_version_checks' );
	
	if( isset( $checks->last_result->latest ) AND version_compare( $checks->last_result->latest, YOURLS_VERSION, '>' ) ) {
		$msg = yourls_s( '<a href="%s">YOURLS version %s</a> is available. Please update!', 'http://yourls.org/download', $checks->last_result->latest );
		yourls_add_notice( $msg );
	}
}

/**
 * Send a filerable content type header
 *
 * @since 1.7
 * @param string $type content type ('text/html', 'application/json', ...)
 * @return bool whether header was sent
 */
function yourls_content_type_header( $type ) {
	if( !headers_sent() ) {
		$charset = yourls_apply_filters( 'content_type_header_charset', 'utf-8' );
		header( "Content-Type: $type; charset=$charset" );
		return true;
	}
	return false;
}

/**
 * Get search text from query string variables search_protocol, search_slashes and search
 *
 * Some servers don't like query strings containing "(ht|f)tp(s)://". A javascript bit
 * explodes the search text into protocol, slashes and the rest (see JS function
 * split_search_text_before_search()) and this function glues pieces back together
 * See issue https://github.com/YOURLS/YOURLS/issues/1576
 *
 * @since 1.7
 * @return string Search string
 */
function yourls_get_search_text() {
	$search = '';
	if( isset( $_GET['search_protocol'] ) )
		$search .= $_GET['search_protocol'];
	if( isset( $_GET['search_slashes'] ) )
		$search .= $_GET['search_slashes'];
	if( isset( $_GET['search'] ) )
		$search .= $_GET['search'];
	
	return htmlspecialchars( trim( $search ) );
}
