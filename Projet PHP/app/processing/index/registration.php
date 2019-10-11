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

		if(strlen($username) > 5 && strlen($username) < 17){

			if(preg_match('/^[a-zA-Z0-9]+$/', $username)){

				if(preg_match('/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/', $email)){

					if(filter_var($email, FILTER_VALIDATE_EMAIL)){

						if(strlen($secret_question) > 9 && strlen($secret_question) < 256){

							if(strlen($secret_question_answer) > 4 && strlen($secret_question_answer) < 256){

								if(!($User->ExistsUserWithUsername(strtolower($username)))){

									if(!($User->ExistsUserWithEmail(strtolower($email)))){

										$User->CreateUser($username, $email, $secret_question, $secret_question_answer);
										echo 'Success';

									} else {

										echo 'Error';

									}

								} else {

									echo 'Error';

								}

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

	} else {

		echo 'Error';

	}

} else {

	echo 'Error';

}

?>