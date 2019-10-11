<?php 

session_start();

require_once('../../database.php');
require_once('../../models/user.class.php');
require_once('../../models/notification.class.php');
$Database = new Database();
$Notification = new Notification();

if(isset($_SESSION['id'])){

	$id = htmlentities(trim($_POST['id']));

	if($Notification->ExistsNotifWithId($id)){

		if($Notification->GetNotif($id, "user_get") == $_SESSION['id']){

			$Notification->DeleteNotif($id, $_SESSION['id']);
			echo 'Success';

		} else {
			echo 'Error';
		}

	} else {
		echo 'Error';
	}
}

?>