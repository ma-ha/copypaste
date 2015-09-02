<?php 
error_log("LoadPage");
if ( isset( $_GET['page_id'] ) ) {
	echo "/mh/ws/copypaste/web/index.html?page_id=". $_GET['page_id'];
} else {
	echo "";
}
?>