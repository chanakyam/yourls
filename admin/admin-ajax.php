<?php
define( 'YOURLS_ADMIN', true );
define( 'YOURLS_AJAX', true );
require_once( dirname( dirname( __FILE__ ) ) .'/includes/load-yourls.php' );
yourls_maybe_require_auth();

//updating users
function edit_user_data($userid) {
	global $ydb;
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
/*$return = <<<RETURN
<tr id="edit-$id" class="edit-row"><td colspan="5" class="edit-row"><strong>First Name</strong>:<input type="text" id="edit-fname-$id" name="edit-fname-$id" value="$fname" class="text" size="70" /><br/><strong>Last Name</strong>:<input type="text" id="edit-lname-$id" name="edit-lname-$id" value="$lname" class="text" size="10" /><br/><strong>Email</strong>: <input type="text" id="edit-email-$id" name="edit-email-$id" value="$email" class="text" size="60" /><br/><strong>Role</strong>: <input type="text" id="edit-role-$id" name="edit-role-$id" value="$role" class="text" size="60" /><br/><strong>Status</strong>: <input type="text" id="edit-status-$id" name="edit-status-$id" value="$status" class="text" size="60" /></td><td colspan="1"><input type="button" id="edit-submit-$id" name="edit-submit-$id" value="Save" title="Save" class="button" onclick="edit_user_save('$id');" />&nbsp;<input type="button" id="edit-close-$id" name="edit-close-$id" value="Cancel" title="Cancel" class="button" onclick="edit_user_hide('$id');" /><input type="hidden" id="user_id_$id" value="$id"/></td></tr>
RETURN;*/
$return = <<<RETURN
<tr id="edit-$id" class="edit-row"><td colspan="7" class="edit-row"><div class="row"><div class="width10 margin5_T">First Name :</div> <input type="text" id="edit-fname-$id" name="edit-fname-$id" value="$fname" class="text width50" size="70" /></div><div class="row"><div class="width10 margin5_T">Last Name :</div> <input type="text" id="edit-lname-$id" name="edit-lname-$id" value="$lname" class="text width50" size="10" /></div><div class="row"><div class="width10 margin5_T">Email :</div> <input type="text" id="edit-email-$id" name="edit-email-$id" value="$email" class="text width50" size="60" /></div><div class="row"><div class="width10 margin5_T">Role :</div><select id="edit-role-$id" name="edit-role-$id" class="text width50"><option value="User">User</option><option value='Admin'>Admin</option><option value='Admin User'>Admin User</option><option value='User'>User</option></select></div><div class="row"><div class="width10 margin5_T">Status :</div><select id="edit-status-$id" name="edit-status-$id" class="text width50" size="0" ><option value="Active">Active</option><option value="Inactive">Inactive</option></select><input type="button" id="edit-submit-$id" name="edit-submit-$id" value="Save" title="Save" class="button margin10_L" onclick="edit_user_save('$id');" /> <input type="button" id="edit-close-$id" name="edit-close-$id" value="Cancel" title="Cancel" class="button margin10_L" onclick="edit_user_hide('$id');" /><input type="hidden" id="user_id_$id" value="$id"/></td></tr>
RETURN;
		$return = urldecode( $return );
		} else {
			$return = '<tr class="edit-row notfound"><td colspan="6" class="edit-row notfound">' . 'Error, URL not found' . '</td></tr>';
		}
		return $return;
	}
}

//adding users
function save_user_data( $id,$fname, $lname, $email,$role, $status) {
	$q1 = "SELECT user_id from yourls_users WHERE email = '".$email."' AND user_id != ".$id;
	$sql = mysql_query($q1);
	if (mysql_num_rows($sql) == 0){
		$query = "UPDATE yourls_users SET firstname='".$fname."', lastname='".$lname."', email='".$email."', role='".$role."', status='".$status."'  WHERE user_id=".$id;
		mysql_query($query);
	//if($query){
		$response['status'] = "success";
		$response['message'] = "Updated Succesfully ";
	}else{
		$response['status'] = "fail";
		$response['message'] = "Email Already Exists";
	}
	return $response;
	
}

//deleting users
function delete_user( $id) {	
	$delete_query = "DELETE FROM `yourls_users` WHERE user_id='".$id."'" ;
	$delete_result = mysql_query($delete_query);
	if ($delete_result){
		$response['status']	= "success";
		$response['message'] = "Deleted Succesfully ";
	}else{

		$response['status']	= "fail";
		$response['message'] = "unsuccessful";
	}
	return $response;
	
}

// This file will output a JSON string
yourls_content_type_header( 'application/json' );

if( !isset( $_REQUEST['action'] ) )
	die();

// Pick action
$action = $_REQUEST['action'];
switch( $action ) {

	case 'add':
		yourls_verify_nonce( 'add_url', $_REQUEST['nonce'], false, 'omg error' );
		$return = yourls_add_new_link( $_REQUEST['url'], $_REQUEST['keyword'] );
		echo json_encode($return);
		break;
		
	case 'edit_display':
		yourls_verify_nonce( 'edit-link_'.$_REQUEST['id'], $_REQUEST['nonce'], false, 'omg error' );
		$row = yourls_table_edit_row ( $_REQUEST['keyword'] );
		echo json_encode( array('html' => $row) );
		break;

	case 'edit_save':
		yourls_verify_nonce( 'edit-save_'.$_REQUEST['id'], $_REQUEST['nonce'], false, 'omg error' );
		$return = yourls_edit_link( $_REQUEST['url'], $_REQUEST['keyword'], $_REQUEST['newkeyword'], $_REQUEST['title'] );
		echo json_encode($return);
		break;
		
	case 'delete':
		yourls_verify_nonce( 'delete-link_'.$_REQUEST['id'], $_REQUEST['nonce'], false, 'omg error' );
		$query = yourls_delete_link_by_keyword( $_REQUEST['keyword'] );
		echo json_encode(array('success'=>$query));
		break;
		
	case 'logout':
		// unused for the moment
		yourls_logout();
		break;

	case 'edit_user':
		$return = edit_user_data( $_REQUEST['id']);
		echo json_encode( array('html' => $return) );
		break;

	case 'edit_user_save':
		$return = save_user_data( $_REQUEST['id'],$_REQUEST['fname'], $_REQUEST['lname'], $_REQUEST['email'], $_REQUEST['role'], $_REQUEST['status']);
		echo json_encode($return);
		break;

	case 'delete_user':
		$query = delete_user( $_REQUEST['id'] );
		echo json_encode($query);
		break;

	
	default:
		yourls_do_action( 'yourls_ajax_'.$action );

}

die();
