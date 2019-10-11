<?php 

session_start();

require_once('../../database.php');
require_once('../../models/user.class.php');

$Database = new Database();
$User = new User();

if(!(isset($_SESSION['id'])))
{

	if(isset($_POST['email']) && isset($_POST['npk']) && isset($_POST['password']) && isset($_POST['password_repeat']))
	{

		$email = htmlentities(trim($_POST['email']));
		$npk = htmlentities(trim($_POST['npk']));
		$password = htmlentities(trim($_POST['password']));
		$password_repeat = htmlentities(trim($_POST['password_repeat']));

		if(strlen($password) >= 8 && strlen($password) <= 20)
		{ 

			if($password == $password_repeat)
			{

				if($User->ExistsUserWithEmail($email))
				{

					if(!($User->GetUser($User->GetUserIdFromEmail($email), 'newpassword_key') == 'NULL'))
					{

						if($User->GetUser($User->GetUserIdFromEmail($email), 'newpassword_key') == $npk)
						{

							$User->SetUser($User->GetUserIdFromEmail($email), 'password', sha1($password));
							$User->SetUser($User->GetUserIdFromEmail($email), 'newpassword_key', 'NULL');
							$User->SendUserNewPasswordEmailReq($email, $password);
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

} else {

	echo 'Error';

}

?>