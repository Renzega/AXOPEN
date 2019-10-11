<?php 

session_start();

require_once('../../database.php');
require_once('../../models/user.class.php');
$Database = new Database();
$User = new User();

if(!(isset($_SESSION['id']))){

	if(isset($_POST['email'])){

		$email = htmlentities(trim(strtolower($_POST['email'])));

		if($User->ExistsUserWithEmail($email)){

			echo 'Error';

		} else {

			echo 'Success';

		}

	} else {

		echo 'Error';
		
	}

} else {

	echo 'Error';

}

?>