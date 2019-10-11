<?php 

session_start();

require_once('../../database.php');
require_once('../../models/user.class.php');
require_once('../../models/notification.class.php');
$Database = new Database();
$Notification = new Notification();

if(isset($_SESSION['id'])){
	if($Notification->ExistsNotifsNotRead($_SESSION['id'])){
		$Notification->ReadAllNotifs($_SESSION['id']);
		echo 'Success';
	} else {
		echo 'Error';
	}
} else {
	echo 'Error';
}

?>