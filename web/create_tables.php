<?php
include_once 'db_connection.php';
$conn = dbconn();

$copypageTbl = "copypage";
$copypageCol = 
	"page_id VARCHAR(255) NOT NULL,
	secret VARCHAR(255) DEFAULT NULL,
	create_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (page_id)";

$createTable = $conn->exec( "CREATE TABLE IF NOT EXISTS $copypageTbl ( $copypageCol )" );
if ( $createTable ) {
	echo "Table $copypageTbl - Created!<br /><br />";
} else { 
	echo "Table $copypageTbl not successfully created! <br /><br />";
}

$copydataTbl = "copydata";
$copydataCol = 
	"data_id BIGINT NOT NULL AUTO_INCREMENT,
	page_id VARCHAR(255) NOT NULL,
	create_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	copytext TEXT NOT NULL, 
	PRIMARY KEY (data_id, page_id)";

$createTable = $conn->exec( "CREATE TABLE IF NOT EXISTS $copydataTbl ( $copydataCol )" );
if ( $createTable ) {
	echo "Table $copydataTbl - Created!<br /><br />";
} else { 
	echo "Table $copydataTbl not successfully created! <br /><br />";
}
?>