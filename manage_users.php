<?php
session_start();
define( 'YOURLS_ADMIN', true );
require_once( dirname( __FILE__ ).'/includes/load-yourls.php' );
yourls_maybe_require_auth();

// Handle plugin administration pages
if( isset( $_GET['page'] ) && !empty( $_GET['page'] ) ) {
	yourls_plugin_admin_page( $_GET['page'] );
}

// Handle activation/deactivation of plugins
if( isset( $_GET['action'] ) ) {

	// Check nonce
	yourls_verify_nonce( 'manage_plugins', $_REQUEST['nonce'] );

	// Check plugin file is valid
	if( isset( $_GET['plugin'] ) && yourls_validate_plugin_file( YOURLS_PLUGINDIR.'/'.$_GET['plugin'].'/plugin.php') ) {
		
		global $ydb;
		// Activate / Deactive
		switch( $_GET['action'] ) {
			case 'activate':
				$result = yourls_activate_plugin( $_GET['plugin'].'/plugin.php' );
				if( $result === true )
					yourls_redirect( yourls_admin_url( 'plugins.php?success=activated' ), 302 );

				break;
		
			case 'deactivate':
				$result = yourls_deactivate_plugin( $_GET['plugin'].'/plugin.php' );
				if( $result === true )
					yourls_redirect( yourls_admin_url( 'plugins.php?success=deactivated' ), 302 );

				break;
				
			default:
				$result = yourls__( 'Unsupported action' );
				break;
		}
	} else {
		$result = yourls__( 'No plugin specified, or not a valid plugin' );
	}
	
	yourls_add_notice( $result );
}

// Handle message upon succesfull (de)activation
if( isset( $_GET['success'] ) && ( ( $_GET['success'] == 'activated' ) OR ( $_GET['success'] == 'deactivated' ) ) ) {
	if( $_GET['success'] == 'activated' ) {
		$message = yourls__( 'Plugin has been activated' );
	} elseif ( $_GET['success'] == 'deactivated' ) {
		$message = yourls__( 'Plugin has been deactivated' );
	}
	
}

yourls_html_head( 'index', yourls__( 'Manage Users' ) );

/* To Edit and Delete Users*/
function edit_user_data($userid) {
	$user_results = $ydb->get_results( "SELECT * FROM `yourls_users` WHERE user_id='".$userid."';" );
			if(!empty($user_results)) {
				$user_data = current($user_results);
				if( !empty($user_data) ) {
					$id = $user_data->user_id;
					$fname = $user_data->firstname;
					$lname = $user_data->lastname;
					$email = $user_data->email;
					$role = $user_data->role;
					$status = $user_data->status;
		$return = <<<RETURN
<tr id="edit-$id" class="edit-row"><td colspan="5" class="edit-row"><strong>%s</strong>:<input type="text" id="edit-url-$id" name="edit-fname-$id" value="$fname" class="text" size="70" /><br/><strong>%s</strong>:<input type="text" id="edit-lname-$id" name="edit-lname-$id" value="$lname" class="text" size="10" /><br/><strong>%s</strong>: <input type="text" id="edit-email-$id" name="edit-email-$id" value="$email" class="text" size="60" /><br/><strong>%s</strong>: <input type="text" id="edit-role-$id" name="edit-role-$id" value="$role" class="text" size="60" /><br/><strong>%s</strong>: <input type="text" id="edit-status-$id" name="edit-status-$id" value="$status" class="text" size="60" /></td><td colspan="1"><input type="button" id="edit-submit-$id" name="edit-submit-$id" value="%s" title="%s" class="button" onclick="edit_user_save('$id');" />&nbsp;<input type="button" id="edit-close-$id" name="edit-close-$id" value="%s" title="%s" class="button" onclick="edit_link_hide('$id');" /><input type="hidden" id="user_id_$id" value="$id"/></td></tr>
RETURN;
		$return = sprintf( urldecode( $return ), yourls__( 'First Name' ), yourls__( 'Last Name' ), yourls__( 'Email' ), yourls__( 'Role' ), yourls__( 'Status' ), yourls__( 'Save' ), yourls__( 'Cancel' ) );
	} else {
		$return = '<tr class="edit-row notfound"><td colspan="6" class="edit-row notfound">' . yourls__( 'Error, URL not found' ) . '</td></tr>';
	}
	//echo json_encode( array('html' => $return) );
	// $return = yourls_apply_filter( 'table_edit_row', $return, $keyword, $url, $title );

	return $return;
			}
}
if( isset( $_GET['user_action'] ) ) {
	switch ($_GET['user_action']) {
		case 'edit':
			$userid = $_GET['id'];
			$user_results = $ydb->get_results( "SELECT * FROM `yourls_users` WHERE user_id='".$userid."';" );
			if(!empty($user_results)) {
				$user_data = current($user_results);
				if( !empty($user_data) ) {
					$id = $user_data->user_id;
					$fname = $user_data->firstname;
					$lname = $user_data->lastname;
					$email = $user_data->email;
					$role = $user_data->role;
					$status = $user_data->status;
		$return = <<<RETURN
<tr id="edit-$id" class="edit-row"><td colspan="5" class="edit-row"><strong>%s</strong>:<input type="text" id="edit-url-$id" name="edit-fname-$id" value="$fname" class="text" size="70" /><br/><strong>%s</strong>:<input type="text" id="edit-lname-$id" name="edit-lname-$id" value="$lname" class="text" size="10" /><br/><strong>%s</strong>: <input type="text" id="edit-email-$id" name="edit-email-$id" value="$email" class="text" size="60" /><br/><strong>%s</strong>: <input type="text" id="edit-role-$id" name="edit-role-$id" value="$role" class="text" size="60" /><br/><strong>%s</strong>: <input type="text" id="edit-status-$id" name="edit-status-$id" value="$status" class="text" size="60" /></td><td colspan="1"><input type="button" id="edit-submit-$id" name="edit-submit-$id" value="%s" title="%s" class="button" onclick="edit_user_save('$id');" />&nbsp;<input type="button" id="edit-close-$id" name="edit-close-$id" value="%s" title="%s" class="button" onclick="edit_link_hide('$id');" /><input type="hidden" id="user_id_$id" value="$id"/></td></tr>
RETURN;
		$return = sprintf( urldecode( $return ), yourls__( 'First Name' ), yourls__( 'Last Name' ), yourls__( 'Email' ), yourls__( 'Role' ), yourls__( 'Status' ), yourls__( 'Save' ), yourls__( 'Cancel' ) );
	} else {
		$return = '<tr class="edit-row notfound"><td colspan="6" class="edit-row notfound">' . yourls__( 'Error, URL not found' ) . '</td></tr>';
	}
	echo json_encode( array('html' => $return) );
	// $return = yourls_apply_filter( 'table_edit_row', $return, $keyword, $url, $title );

	// return $return;
			}
			break;
	}

}

?>

	<h2 class="title"><?php yourls_e( 'Manage Users' .' '. '<a href="../add_user.php" style="color:white; float:right;">Add User</a>'); ?></h2></br>
	
		<?php 
			if (isset($_REQUEST['status']) && $_REQUEST['status']== 1 ){
	        	echo "<center><strong>Added Successfully.</strong></center>";
	        }
	    ?>

		<?php 
			if (isset($_REQUEST['status']) && $_REQUEST['status']== 0 ){
	        	echo "<center><strong>Please try again.</strong></center>";
	        }
	    ?>
	    <?php 
			if (isset($_REQUEST['status']) && $_REQUEST['status']== 2 ){
	        	echo "<center><strong>User Already Exists.</strong></center>";
	        }
	    ?>


	<?php
	// Main Query
	$where = yourls_apply_filter( 'admin_list_where', $where );
	$user_results = $ydb->get_results( "SELECT * FROM `yourls_users`;" );
	$plugins = (array)yourls_get_plugins();
	uasort( $plugins, 'yourls_plugins_sort_callback' );
	
	$count = count( $plugins );
	$plugins_count = sprintf( yourls_n( '%s plugin', '%s plugins', $count ), $count );
	$count_active = yourls_has_active_plugins();
	?>
	
	<!--<p id="plugin_summary">-->
		<?php /* //translators: "you have '3 plugins' installed and '1' activated" */ //yourls_se( 'You currently have <strong>%1$s</strong> installed, and <strong>%2$s</strong> activated', $plugins_count, $count_active ); ?>
	<!--</p>-->
<?php echo $message ;?>
	<input type="hidden" name="tr_class" id="tr_class" value="" />
	<table id="users_main_table" class="tblSorter" cellpadding="0" cellspacing="1">
	<input type="hidden" name="user_hid" id="user_hid" value="" />

	<thead>
		<tr>
			<th>ID</th>
			<th><?php yourls_e( 'First Name' ); ?></th>
			<th><?php yourls_e( 'Last Name' ); ?></th>
			<th><?php yourls_e( 'Email' ); ?></th>
			<th><?php yourls_e( 'Role' ); ?></th>
			<th><?php yourls_e( 'Status' ); ?></th>
			<th><?php yourls_e( 'Action' ); ?></th>
		</tr>
	</thead>
	</table>
	
	<script type="text/javascript">
	yourls_defaultsort = 0;
	yourls_defaultorder = 0;
	<?php if ($count_active) { ?>
	$('#plugin_summary').append('<span id="toggle_plugins">filter</span>');
	$('#toggle_plugins').css({'background':'transparent url("../images/filter.gif") top left no-repeat','display':'inline-block','text-indent':'-9999px','width':'16px','height':'16px','margin-left':'3px','cursor':'pointer'})
		.attr('title', '<?php echo yourls_esc_attr__( 'Toggle active/inactive plugins' ); ?>')
		.click(function(){
			$('#main_table tr.inactive').toggle();
		});
	<?php } ?>
	</script>
	<?php yourls_html_footer(); ?>
