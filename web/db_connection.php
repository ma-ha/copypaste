<?php
define( 'DB_HOST', 'localhost' );
define( 'DB_USER', 'copypasteuser' );
define( 'DB_PASSWORD', 'secret' );
define( 'DB_NAME', 'copypaste' );

$con = mysql_connect( DB_HOST, DB_USER, DB_PASSWORD );

if ( ! $con ) {
	die('Could not connect: ' . mysql_error());
}

mysql_select_db( DB_NAME , $con );
?>