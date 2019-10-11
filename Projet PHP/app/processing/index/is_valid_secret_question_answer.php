<?php 

session_start();

require_once('../../database.php');
require_once('../../models/user.class.php');
$Database = new Database();
$User = new User();

if(!(isset($_SESSION['id']))){

	if(isset($_POST['email']) && isset($_POST['secret_question_answer'])){

		$email = htmlentities(trim($_POST['email']));
		$secret_question_answer = htmlentities(trim($_POST['secret_question_answer']));

		if($User->ExistsUserWithEmail($email)){

			if($User->GetUser($User->GetUserIdFromEmail($email), 'secret_question_answer') == trim($secret_question_answer)){

				echo 'Success';

			} else {

				echo 'Error';

			}

		} else {

			echo 'Error';

		}

	} else {

		echo 'Error 2';

	}

} else {

	echo 'Error';

}

?>