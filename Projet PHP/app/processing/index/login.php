<?php 

session_start();

require_once('../../database.php');
require_once('../../models/user.class.php');
$Database = new Database();
$User = new User();

if(!(isset($_SESSION['id']))){

	if(isset($_POST['email']) && isset($_POST['password'])){

		$email = htmlentities(trim($_POST['email']));
		$password = htmlentities(trim($_POST['password']));

		if(strlen($email) > 0){

			if(strlen($password) > 0){

				if($User->ExistsUserWithEmail($email)){

					$user_id = $User->GetUserIdFromEmail($email);

					if(sha1($password) == $User->GetUser($user_id, "password")){

						session_start();
						$_SESSION['id'] = $user_id;
						$_SESSION['email'] = $email;
						$_SESSION['password'] = sha1($password);
						echo 'Success';

					} else {
						echo 'Error';
					}

				} else {

					echo 'Error';

				}

			} else {

				echo 'Error';

			}

		} else {

			echo 'Error';

		}

	} else {

		echo 'Error';

	}

} else {

	echo 'Error';

}

?>