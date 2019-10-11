<?php 

session_start();

require_once('../../../vendor/autoload.php');
require_once('../../database.php');
require_once('../../models/user.class.php');
require_once('../../models/codes_promo.class.php');
$ids = require_once('../../models/PAYPAL/paypal.config.php');

$Database = new Database();
$User = new User();
$Code_Promo = new Code_Promo();

if(isset($_SESSION['id']))
{
	if($User->GetUser($_SESSION['id'], 'email') == $_SESSION['email'])
	{

		if($User->GetUser($_SESSION['id'], 'password') == $_SESSION['password'])
		{

			$apiContext = new \PayPal\Rest\ApiContext(
				new \PayPal\Auth\OAuthTokenCredential(
					$ids['id'],
					$ids['secret']
				)
			);

			$apiContext->setConfig(
		        array(
		            'mode' => 'live',
		            'log.LogEnabled' => false,
		            'log.LogLevel' => 'INFO', // PLEASE USE `INFO` LEVEL FOR LOGGING IN LIVE ENVIRONMENTS
		            'cache.enabled' => false,
		            'cache.FileName' => '/PaypalCache'
		        )
		    );

		    if(isset($_GET['code_promo']))
		    {

		    	$code_promo_value = htmlentities(trim($_GET['code_promo']));

		    	$new_price = 4.99;

		    	$is_valid_promo_code = false;

		    	if($Code_Promo->ExistsPromoSecretCode($code_promo_value))
		    	{
		    		if($Code_Promo->GetPromoCode($Code_Promo->GetPromoCodeIdFromSecretCode($code_promo_value), 'subscription_type') == 1)
		    		{

		    			$reduction = $Code_Promo->GetPromoCode($Code_Promo->GetPromoCodeIdFromSecretCode($code_promo_value), 'promo_value');
		    			$new_price = $new_price - ($reduction*$new_price)/100;
		    			$is_valid_promo_code = true;

		    		} else {

		    			$new_price = 4.99;

		    		}
		    	}

		    	if($new_price != 0)
		    	{

					$list = new \PayPal\Api\ItemList();
					$item = (new \PayPal\Api\Item())
						->setName('Abonnement Rock Money 1 Mois STARTER')
						->setPrice($new_price)
						->setCurrency('EUR')
						->setQuantity(1);
					$list->addItem($item);

					$details = (new \PayPal\Api\Details())
						->setSubTotal($new_price);

					$amount = (new PayPal\Api\Amount())
						->setTotal($new_price)
						->setCurrency('EUR')
						->setDetails($details);

					$transaction = (new PayPal\Api\Transaction())
						->setItemList($list)
						->setDescription('Abonnement Rock Money 1 Mois STARTER')
						->setAmount($amount)
						->setCustom($_SESSION['id'].'|1|'.$code_promo_value);

					$payment = new \PayPal\Api\Payment();

					$payment->setTransactions([$transaction]);

					$payment->setIntent('sale');

					$redirectUrls = new \PayPal\Api\RedirectUrls();
					$redirectUrls->setReturnUrl('http://rock-money.fr/vip.php?p=abonner_1');
					$redirectUrls->setCancelUrl('http://rock-money.fr/vip.php?p=abonnement');
					$payment->setRedirectUrls($redirectUrls);

					$payment->setPayer((new \PayPal\Api\Payer())->setPaymentMethod('paypal'));

					try
					{
						
						$payment->create($apiContext); 
						echo $payment->getApprovalLink();

					} catch (\PayPal\Exception\PayPalConnectionException $e)
					{

						echo $e->getData();

					}

				} else {

					if($is_valid_promo_code == true)
					{

						if($Code_Promo->GetPromoCode($Code_Promo->GetPromoCodeIdFromSecretCode($code_promo_value), 'for_all') != 1)
						{

							$User->SetUser($_SESSION['id'], 'subscription_type', '1');
							$User->SetUser($_SESSION['id'], 'subscription_date_end', date('d/m/Y', strtotime("+1 months")));
							echo 'vip.php?p=accueil';

						} else {

							$User->SetUser($_SESSION['id'], 'subscription_type', '1');
							$User->SetUser($_SESSION['id'], 'subscription_date_end', date('d/m/Y', strtotime("+1 months")));
							echo 'vip.php?p=accueil';

						}

					} 

				}

			} else {

				$list = new \PayPal\Api\ItemList();
				$item = (new \PayPal\Api\Item())
					->setName('Abonnement Rock Money 1 Mois STARTER')
					->setPrice(4.99)
					->setCurrency('EUR')
					->setQuantity(1);
				$list->addItem($item);

				$details = (new \PayPal\Api\Details())
					->setSubTotal(4.99);

				$amount = (new PayPal\Api\Amount())
					->setTotal(4.99)
					->setCurrency('EUR')
					->setDetails($details);

				$transaction = (new PayPal\Api\Transaction())
					->setItemList($list)
					->setDescription('Abonnement Rock Money 1 Mois STARTER')
					->setAmount($amount)
					->setCustom($_SESSION['id'].'|1|');

				$payment = new \PayPal\Api\Payment();

				$payment->setTransactions([$transaction]);

				$payment->setIntent('sale');

				$redirectUrls = new \PayPal\Api\RedirectUrls();
				$redirectUrls->setReturnUrl('http://rock-money.fr/vip.php?p=abonner_1');
				$redirectUrls->setCancelUrl('http://rock-money.fr/vip.php?p=abonnement');
				$payment->setRedirectUrls($redirectUrls);

				$payment->setPayer((new \PayPal\Api\Payer())->setPaymentMethod('paypal'));

				try
				{
					
					$payment->create($apiContext);
					echo $payment->getApprovalLink();

				} catch (\PayPal\Exception\PayPalConnectionException $e)
				{

					echo $e->getData();

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

?>