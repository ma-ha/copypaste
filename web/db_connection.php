<?php
//define( 'DATASOURCE', 'sqlsrv:server = tcp:sz7ko30b78.database.windows.net,1433; Database = copypaste' );
define( 'DATASOURCE', 'mysql:dbname=copypaste;host=127.0.0.1' );
define( 'DB_USER', 'copypasteuser' );
define( 'DB_PASSWORD', 'secret' );


function dbconn() {
	$conn = NULL;
	try {
		$conn = new PDO ( DATASOURCE, DB_USER, DB_PASSWORD );
		$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	} catch ( PDOException $e ) {
		print( "Error connecting to SQL Server." );
		die( print_r( $e ) );
	}
	error_log( "Connection ".DATASOURCE );
	return $conn;
}
?>