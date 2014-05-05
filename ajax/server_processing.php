<?php

	/* REMOVE THIS LINE (it just includes my SQL connection user/pass) */
	define( 'YOURLS_ADMIN', true );
	define( 'YOURLS_AJAX', true );
	require_once( dirname( dirname( __FILE__ ) ) .'/includes/load-yourls.php' );
	yourls_maybe_require_auth();

	include(__DIR__."/../user/config.php");
	
	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * Easy set variables
	 */
	
	/* Array of database columns which should be read and sent back to DataTables. Use a space where
	 * you want to insert a non-database field (for example a counter or static image)
	 */
	$aColumns = array( 'keyword', 'url', 'timestamp', 'ip', 'clicks','title');
	$aColumns_alias = array( 'id'=>'keyword', "short_url"=>"url", "short_title"=>"title");
	
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "keyword";
	
	/* DB table to use */
	$sTable = "yourls_url";
	
	/* Database connection information */
	$gaSql['user']       = YOURLS_DB_USER;
	$gaSql['password']   = YOURLS_DB_PASS;
	$gaSql['db']         = YOURLS_DB_NAME;
	$gaSql['server']     = YOURLS_DB_HOST;
	
	
	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * If you just want to use the basic configuration for DataTables with PHP server-side, there is
	 * no need to edit below this line
	 */
	
	/* 
	 * Local functions
	 */
	function fatal_error ( $sErrorMessage = '' )
	{
		header( $_SERVER['SERVER_PROTOCOL'] .' 500 Internal Server Error' );
		die( $sErrorMessage );
	}

	
	/* 
	 * MySQL connection
	 */
	if ( ! $gaSql['link'] = mysql_pconnect( $gaSql['server'], $gaSql['user'], $gaSql['password']  ) )
	{
		fatal_error( 'Could not open connection to server' );
	}

	if ( ! mysql_select_db( $gaSql['db'], $gaSql['link'] ) )
	{
		fatal_error( 'Could not select database ' );
	}

	/* 
	 * Paging
	 */
	$sLimit = "";
	if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
	{
		$sLimit = "LIMIT ".intval( $_GET['iDisplayStart'] ).", ".
			intval( $_GET['iDisplayLength'] );
	}
	
	
	/*
	 * Ordering
	 */
	$sOrder = "";
	if ( isset( $_GET['iSortCol_0'] ) )
	{
		$sOrder = "ORDER BY  ";
		for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
		{
			if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
			{
				$sOrder .= "`".$aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."` ".
					($_GET['sSortDir_'.$i]==='asc' ? 'asc' : 'desc') .", ";
			}
		}
		
		$sOrder = substr_replace( $sOrder, "", -2 );
		if ( $sOrder == "ORDER BY" )
		{
			$sOrder = "";
		}
	}
	
	
	/* 
	 * Filtering
	 * NOTE this does not match the built-in DataTables filtering which does it
	 * word by word on any field. It's possible to do here, but concerned about efficiency
	 * on very large tables, and MySQL's regex functionality is very limited
	 */
	$sWhere = "";
	if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
	{
		$sWhere = "WHERE (";
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" )
			{
				$sWhere .= "`".$aColumns[$i]."` LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
			}
		}
		$sWhere = substr_replace( $sWhere, "", -3 );
		$sWhere .= ')';
	}
	
	/* Individual column filtering */
	for ( $i=0 ; $i<count($aColumns) ; $i++ )
	{
		if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
		{
			if ( $sWhere == "" )
			{
				$sWhere = "WHERE ";
			}
			else
			{
				$sWhere .= " AND ";
			}
			$sWhere .= "`".$aColumns[$i]."` LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
		}
	}

	//For user
	if(isset($_SESSION['username']) && $_SESSION['username'] != ""){
		$obj_user = new user();
		$user_data = $obj_user->getUserDetails();
		if($user_data['role'] == "User") {
			$user_id = $user_data['user_id'];
			if($sWhere != "")
				$sWhere .= " AND user_id='".$user_id."'";
			else
				$sWhere = "WHERE user_id='".$user_id."'";
		}
	}
	
	/*
	 * SQL queries
	 * Get data to display
	 */
	$sQuery = "
		SELECT SQL_CALC_FOUND_ROWS `".str_replace(" , ", " ", implode("`, `", $aColumns))."`
		FROM   $sTable
		$sWhere
		$sOrder
		$sLimit
		";
	$rResult = mysql_query( $sQuery, $gaSql['link'] ) or fatal_error( 'MySQL Error: ' . mysql_errno() );
	
	/* Data set length after filtering */
	$sQuery = "
		SELECT FOUND_ROWS()
	";
	$rResultFilterTotal = mysql_query( $sQuery, $gaSql['link'] ) or fatal_error( 'MySQL Error: ' . mysql_errno() );
	$aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
	$iFilteredTotal = $aResultFilterTotal[0];
	
	/* Total data set length */
	$sQuery = "
		SELECT COUNT(`".$sIndexColumn."`)
		FROM   $sTable
	";
	$rResultTotal = mysql_query( $sQuery, $gaSql['link'] ) or fatal_error( 'MySQL Error: ' . mysql_errno() );
	$aResultTotal = mysql_fetch_array($rResultTotal);
	$iTotal = $aResultTotal[0];
	
	
	/*
	 * Output
	 */
	$output = array(
		"sEcho" => intval(@$_GET['sEcho']),
		"iTotalRecords" => $iTotal,
		"iTotalDisplayRecords" => $iFilteredTotal,
		"aaData" => array(),
	);
	
	while ( $aRow = mysql_fetch_array( $rResult ) )
	{
		$row = array();
		$row_alias = array();
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			if ( $aColumns[$i] == "version" )
			{
				/* Special output formatting for 'version' column */
				$row[] = ($aRow[ $aColumns[$i] ]=="0") ? '-' : $aRow[ $aColumns[$i] ];
			}
			else if ( $aColumns[$i] != ' ' )
			{
				/* General output */
				//if($i==5)
				// $row[] = $aRow[ $aColumns[$i] ];
				addAliasColumn($aRow[ $aColumns[$i] ], $aColumns[$i], $row);
			}

		}
		
		$output['aaData'][] = $row;
	}


	function addAliasColumn($str, $alias_column, &$row){
		global $aColumns_alias;
		

		

		if($alias_column == "title" && in_array($alias_column, array_values($aColumns_alias)) ){
			$row["short_title"] = yourls_trim_long_string($str);
		}else if($alias_column == "url" && in_array($alias_column, array_values($aColumns_alias)) ){
			$row["short_url"] = yourls_trim_long_string($str);
		}else if($alias_column== "keyword" && in_array($alias_column, array_values($aColumns_alias)) ){

			$str = yourls_sanitize_string( $str );
			$id = yourls_string2htmlid($str);
			$nonce_edit = yourls_create_nonce("edit-link_".$id, false);
			$nonce_delete = yourls_create_nonce("delete-link_".$id, false);
			$row["keyword"] = $str;
			$row["id"] = $id;
			$row["nonce_edit"] = $nonce_edit;
			$row["nonce_delete"] = $nonce_delete;
			
		}else{
			if($alias_column=="timestamp"){
				$str = date( 'M d, Y H:i', strtotime($str) +( YOURLS_HOURS_OFFSET * 3600 ) );
			}
			if($alias_column=="clicks"){
				yourls_number_format_i18n( $str, 0, '', '' );
			}
		}
		$row[] = $str;
	}
	
	echo json_encode( $output );
?>