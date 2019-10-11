<?php 

session_start();

require_once('../../database.php');
require_once('../../models/user.class.php');

$Database = new Database();
$User = new User();

if(!(isset($_SESSION['id'])))
{

	if(isset($_POST['email']) && isset($_POST['password']) && isset($_POST['password_repeat']) && isset($_POST['phone_number']))
	{

		$email = htmlentities(trim($_POST['email']));
		$password = htmlentities(trim($_POST['password']));
		$password_repeat = htmlentities(trim($_POST['password_repeat']));
		$phone_number = htmlentities(trim($_POST['phone_number']));

		if(strlen($email) > 0 && strlen($password) > 0 && strlen($password_repeat) && strlen($phone_number) > 0)
		{

			if(preg_match('/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/', $email))
			{

				if(filter_var($email, FILTER_VALIDATE_EMAIL))
				{

					if(!($User->ExistsUserWithEmail(strtolower($email))))
					{

						if(strlen($password) >= 8 && strlen($password) <= 20)
						{

							if($password == $password_repeat)
							{

								if(preg_match('/^(0)[1-9](\d{2}){4}$/', $phone_number))
								{

									if(!($User->ExistsUserWithPhoneNumber($phone_number)))
									{

										$User->CreateUser($email, $password, $phone_number);
										echo 'Success';

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