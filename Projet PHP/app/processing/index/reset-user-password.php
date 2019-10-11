<?php 

session_start();

require_once('../../database.php');
require_once('../../models/user.class.php');
$Database = new Database();
$User = new User();

if(!(isset($_SESSION['id']))){

	if(isset($_POST['email']) && isset($_POST['secret_question_answer']) && isset($_POST['new_password']) && isset($_POST['new_password_repeat']) && isset($_POST['npk'])){

		$email = htmlentities(trim($_POST['email']));
		$secret_question_answer = htmlentities(trim($_POST['secret_question_answer']));
		$new_password = htmlentities(trim($_POST['new_password']));
		$new_password_repeat = htmlentities(trim($_POST['new_password_repeat']));
		$npk = htmlentities(trim($_POST['npk']));

		if($User->ExistsUserWithEmail($email)){

			if($User->GetUser($User->GetUserIdFromEmail($email), 'secret_question_answer') == $secret_question_answer){

				if($User->GetUser($User->GetUserIdFromEmail($email), 'newpassword_key') == $npk){

					if(preg_match('/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{7,17}$/', $new_password)){

						if(strlen($new_password) > 7 && strlen($new_password) < 17){

							if(strlen($new_password) == strlen($new_password_repeat)){

								if($User->ExistsUserRealPassword($User->GetUserIdFromEmail($email))){

									$User->DeleteUserRealPassword($User->GetUserIdFromEmail($email));

								}

								$User->SetUser($User->GetUserIdFromEmail($email), 'password', sha1($new_password));
								$User->SetUser($User->GetUserIdFromEmail($email), 'newpassword_key', 'N');
								$User->SendUserNewPasswordEmailReq($User->GetUser($User->GetUserIdFromEmail($email), 'username'), $email, $new_password);
								echo 'Success';

							} else {

								echo 'Error';

							}

						}

					} else {

						echo 'Error';

					}

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