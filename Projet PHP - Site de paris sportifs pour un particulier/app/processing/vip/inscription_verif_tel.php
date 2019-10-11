<?php 

session_start();

require_once('../../database.php');
require_once('../../models/user.class.php');

$Database = new Database();
$User = new User();

if(!(isset($_SESSION['id'])))
{

	if(isset($_POST['phone_number']))
	{

		$phone_number = htmlentities(trim($_POST['phone_number']));

		if($User->ExistsUserWithPhoneNumber($phone_number))
		{

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