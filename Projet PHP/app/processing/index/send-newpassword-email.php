<?php 

session_start();

require_once('../../database.php');
require_once('../../models/user.class.php');
$Database = new Database();
$User = new User();

if(!(isset($_SESSION['id']))){

	if(isset($_POST['email'])){

		$email = htmlentities(trim($_POST['email']));

		if($User->ExistsUserWithEmail($email)){

			$newpassword_key = $User->GenerateUserPasswordKey();
			$User->SetUser($User->GetUserIdFromEmail($email), 'newpassword_key', $newpassword_key);
			$User->SendUserNewPasswordEmail($User->GetUser($User->GetUserIdFromEmail($email), 'username'), $email, $newpassword_key);
			echo 'Success';

		} else {

			echo 'Error';

		}

	}

} else {

	echo 'Error';

}

?>