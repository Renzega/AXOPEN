<?php 

session_start();

require_once('../../database.php');
require_once('../../models/user.class.php');
$Database = new Database();
$User = new User();

if(!(isset($_SESSION['id']))){

	if(isset($_POST['username']) && isset($_POST['email']) && isset($_POST['secret_question']) && isset($_POST['secret_question_answer'])){

		$username = htmlentities(trim($_POST['username']));
		$email = htmlentities(trim($_POST['email']));
		$secret_question = htmlentities(trim($_POST['secret_question']));
		$secret_question_answer = htmlentities(trim($_POST['secret_question_answer']));

		if($User->ExistsUserWithEmail($email)){

			if($User->GetUser($User->GetUserIdFromEmail($email), 'username') == $username){

				if($User->ExistsUserRealPassword($User->GetUserIdFromEmail($email))){

					$User->SendUserRegistrationEmail($email, $username, $User->GetUserRealPassword($User->GetUserIdFromEmail($email)), $User->GetUser($User->GetUserIdFromEmail($email), 'secret_question'), $User->GetUser($User->GetUserIdFromEmail($email), 'secret_question_answer'));
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

	}

} else {

	echo 'Error';

}

?>