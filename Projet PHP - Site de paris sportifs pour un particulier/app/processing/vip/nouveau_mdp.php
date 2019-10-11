<?php 

session_start();

require_once('../../database.php');
require_once('../../models/user.class.php');

$Database = new Database();
$User = new User();

if(!(isset($_SESSION['id'])))
{

	if(isset($_POST['email']))
	{

		$email = htmlentities(trim($_POST['email']));

		if(strlen($email) > 0)
		{

			if(preg_match('/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/', $email))
			{

				if(filter_var($email, FILTER_VALIDATE_EMAIL))
				{

					if($User->ExistsUserWithEmail(strtolower($email)))
					{

						$newpasswordkey = $User->GenerateUserAccountKey();
						$User->SetUser($User->GetUserIdFromEmail($email), 'newpassword_key', $newpasswordkey);
						$User->SendUserNewPasswordEmail($email, $newpasswordkey);
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