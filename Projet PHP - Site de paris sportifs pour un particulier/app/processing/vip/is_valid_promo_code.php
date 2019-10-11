<?php 

session_start();

require_once('../../../vendor/autoload.php');
require_once('../../database.php');
require_once('../../models/codes_promo.class.php');
$ids = require_once('../../models/PAYPAL/paypal.config.php');

$Database = new Database();
$Code_Promo = new Code_Promo();

if(isset($_SESSION['id']))
{
	if(isset($_POST['code_promo']))
	{

		$code_promo_value = htmlentities(trim($_POST['code_promo']));

		if($Code_Promo->ExistsPromoSecretCode($code_promo_value))
		{
			if($Code_Promo->GetPromoCode($Code_Promo->GetPromoCodeIdFromSecretCode($code_promo_value), 'subscription_type') == 1)
		    {

		    	echo 'Success';

		    } else {

		    	echo 'Error 3';

		    }
		    	

		} else {

			echo 'Error 2';

		}

	} else {

		echo 'Error 1';

	}
}

?>