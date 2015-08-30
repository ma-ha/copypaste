<?php
/*
 RESTful Copy'n Paste Backend
  
 License: "The MIT License (MIT)" 
 Copyright (c) 2015 ma-ha

 Functionality:
  - create pages (POST)
  - add copy text to pages (POST with params page_id, copytext) 
  - load page (GET with page_id)
  - delete copytext (DELETE with data_id)
  
 You can protect your page with an additional "secret" parameter. */

include_once 'db_connection.php';

if ( $_SERVER['REQUEST_METHOD'] === 'GET' ) { // read mode

	$secret = null;
	if ( isset( $_GET['secret'] ) ) {
		$secret = mysql_real_escape_string( $_GET['secret'] );
	}
	
	if ( isset( $_GET["page_id"] ) ) {
		if ( secretOK( $_GET["page_id"], $secret ) ) {
			
			$page_id = mysql_real_escape_string( $_GET["page_id"] );
			getPageByID( $page_id );
		
		} else {
			httpSendError( 401, 'Secret reuired for this page!' );
		}
		
	} else {// bad request
		httpSendError( 400, 'GET param page_id required!' );
	}

} else if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) { // write mode
	
	if ( isset( $_POST["page_id"] ) ) {
		if ( isset( $_POST["copytext"] ) ) {
			if ( secretOK( $_POST["page_id"], $_POST["secret"] ) ) {

				addCopyText();
				
			} else { // not authorized
				httpSendError( 401, 'Secret reuired for this page!' );
			}
			
		} else  {
			// bad request
			httpSendError( 400, 'POST param copytext required!' );
		}	
		
	} else  {
		createNewPage();
	}
	
} else if ( $_SERVER['REQUEST_METHOD'] === 'DELETE' ) { // write mode

	$secret = null;
	if ( isset( $_POST['secret'] ) ) {
		$secret = mysql_real_escape_string( $_POST['secret'] );
	}
	
	if ( isset( $_GET["page_id"] ) && isset( $_GET["data_id"] ) ) {
		
		if ( secretOK( $_GET["page_id"], $secret ) ) {
			
			deleteCopyText();
		
		} else { // not authorized
			httpSendError( 401, 'Secret reuired for this page!' );	
		}	
		
	} else {
		httpSendError( 400, 'POST param page_id and data_id required!' );
	}
}

// ===========================================================================
function createNewPage() {
	mylog( 'createNewPage()' );
	$page_id = genRandString( 5 );
	$secret = null; 
	
	$sql = "INSERT INTO copypage SET page_id = '$page_id'";  
	sqllog( $sql );
	
	if ( isset( $_POST["secret"] ) ) {
		$secret = mysql_real_escape_string( $_GET["secret"] );
		$sql .= ", secret = '$secret'";
	}
	
	mysql_query( $sql );
	getPageByID( $page_id );
}

// ===========================================================================
function addCopyText() {
	mylog( 'addCopyText()' );
	$page_id  = mysql_real_escape_string( $_POST['page_id'] );
	$copytext = mysql_real_escape_string( $_POST['copytext'] );
	
	$sql = "INSERT INTO copytext SET page_id = '$page_id', copytext = '$copytext'";
	sqllog( $sql );
	mysql_query( $sql );

	getPageByID( $page_id );
}


// ===========================================================================
function getPageByID( $page_id ) {
	mylog( 'getPageByID()' );
	// load copy paste data
	$copydata =  array();
	$copydata['page_id'] = $page_id; 
	// build SQL
	$secret = null;
	$sql = "SELECT * FROM copydata WHERE page_id = '$page_id' ";
	if ( isset( $_GET["secret"] ) ) {
		$secret = mysql_real_escape_string( $_GET["secret"] ); 
		$sql .= " AND secret = '$secret' " ; 
	}
	$sql .= " ORDER BY create_date";
	sqllog( $sql );
	
	// load data
	$rslt = mysql_query( $sql );
	while ( $rec = mysql_fetch_array( $rslt ) ) {
		$copydata[] = array(
			'data_id' => $rec[ 'data_id' ],
			'create_date' => $rec[ 'create_date' ],
			'copytext' => $rec[ 'copytext' ]
		);
		
	}
	header('Content-type: application/json');
	echo json_encode( $copydata , JSON_PRETTY_PRINT );
}


// ===========================================================================
function deleteCopyText() {
	mylog( 'deleteCopyText()' );
	$page_id = mysql_real_escape_string( $_POST['page_id'] );
	$data_id = mysql_real_escape_string( $_POST['data_id'] );
	
	$sql = "DELETE FROM copydata WHERE data_id = $data_id";
	sqllog( $sql );
	mysql_query( $sql );

	getPageByID( $page_id );
}


// ===========================================================================
function secretOK( $page_id, $secret ) {
	mylog( 'secretOK()' );
	$page_id = mysql_real_escape_string( $page_id );
	$sql = "SELECT * FROM copypage WHERE page_id = '$page_id' ";
	sqllog( $sql );
	$rslt = mysql_query( $sql );
	if ( $rec = mysql_fetch_array( $rslt ) ) {
		if ( $rec[ 'secret' ] != null ) {
			if ( $rec[ 'secret' ] ==  $secret ) {
				log( 'secretOK = true (1)' );
				return true; 
			} else {
				log( 'secretOK = false' );
				return false;
			}
		} else {
			log( 'secretOK = true (2)' );
			return true;
		}
	}
}

// ===========================================================================
/** return general error */
function httpSendError( $httpStatus, $errorText ) {
	mylog( 'httpSendError( $httpStatus )' );
	http_response_code( $httpStatus );
	header('Content-type: application/text');
	echo $errorText;
}


// ===========================================================================
/** helper to create a random key */
function genRandString( $length ) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$string = '';
	for ( $i = 0; $i < $length; $i++ ) {
		$string .= $characters[ mt_rand( 0, strlen( $characters ) ) ];
	}
	return $string;
}

// ===========================================================================
function mylog( $txt ) {
	error_log( $txt );
}

function sqllog( $txt ) {
	error_log( $txt );
}

?>