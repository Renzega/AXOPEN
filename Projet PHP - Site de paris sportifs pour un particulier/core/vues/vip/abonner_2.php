<?php

session_start();

require_once('vendor/autoload.php');
require_once('app/database.php');
require_once('app/models/codes_promo.class.php');
require_once('app/models/invoices.class.php');
require_once('app/models/paypal_checkout.class.php');
require_once('app/models/user.class.php');

$ids = require_once('app/models/PAYPAL/paypal.config.php');

$Database = new Database();
$User = new User();
$Code_Promo = new Code_Promo();
$Invoice = new Invoices();
$Paypal_Checkout = new Paypal_Checkout();

$isValidSession = false;

if(isset($_SESSION['id']))
{
	if($User->GetUser($_SESSION['id'], 'email') == $_SESSION['email'])
	{

		if(!($User->GetUser($_SESSION['id'], 'password') == $_SESSION['password']))
		{

			session_destroy();
			session_unset();
			$isValidSession = false;
			header("Location: vip.php?p=connexion");

		} else {

			if($User->GetUser($_SESSION['id'], 'subscription_type') > 0)
			{

				if($User->GetUser($_SESSION['id'], 'subscription_date_end') == 'N')
				{

					$User->SetUser($_SESSION['id'], 'subscription_type', '0');
					$isValidSession = true;
					
				} else {

					$subscription_date_end = $User->GetUser($_SESSION['id'], 'subscription_date_end');
					$current_date = date("d/m/Y");

					if($current_date > $subscription_date_end)
					{

						$User->SetUser($_SESSION['id'], 'subscription_type', '0');
						$User->SetUser($_SESSION['id'], 'subscription_date_end', 'N');
						$isValidSession = true;

					} else {

						header("Location: vip.php?p=accueil");

					}

				}

			} else {

				if($User->GetUser($_SESSION['id'], 'subscription_date_end') == 'N')
				{

					$isValidSession = true;

				} else {

					$User->SetUser($_SESSION['id'], 'subscription_type', '0');
					$User->SetUser($_SESSION['id'], 'subscription_date_end', 'N');
					$isValidSession = true;

				}

			}

		}

	} else {

		session_destroy();
		session_unset();
		$isValidSession = false;
		header("Location: vip.php?p=connexion");

	}

} else {

	$isValidSession = false;
	header("Location: vip.php?p=connexion");

}

if($isValidSession == true)
{
	if(isset($_GET['paymentId']) && isset($_GET['token']) && isset($_GET['PayerID']))
	{

		try
		{

			if(!($Paypal_Checkout->ExistsPaypalCheckout($_GET['paymentId'])))
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

				$payment = \PayPal\Api\Payment::get($_GET['paymentId'], $apiContext);

				$execution = (new \PayPal\Api\PaymentExecution())
					->setPayerId($_GET['PayerID'])
					->setTransactions($payment->getTransactions());
				$payment->execute($execution, $apiContext);

				$custom_infos = explode(',', $payment->getTransactions()[0]->getCustom(0));

				$real_custom_infos = explode('|', $custom_infos[0]);

				if($real_custom_infos[1] == '2')
				{

					if(strlen($real_custom_infos[2]) > 0)
					{
						if($Code_Promo->ExistsPromoSecretCode($real_custom_infos[2]))
						{

							$amount = round((14.99 - (($Code_Promo->GetPromoCode($Code_Promo->GetPromoCodeIdFromSecretCode($real_custom_infos[2]), 'promo_value')*14.99)/100)), 2, PHP_ROUND_HALF_UP);

							if($Code_Promo->GetPromoCode($Code_Promo->GetPromoCodeIdFromSecretCode($real_custom_infos[2]), 'for_all') != 1)
							{

								$Code_Promo->DeletePromoCode($Code_Promo->GetPromoCodeIdFromSecretCode($real_custom_infos[2]));
								$User->SetUser($real_custom_infos[0], 'subscription_type', $real_custom_infos[1]);
								$User->SetUser($real_custom_infos[0], 'subscription_date_end', date('d/m/Y', strtotime("+1 months")));

							} else {

								$User->SetUser($real_custom_infos[0], 'subscription_type', $real_custom_infos[1]);
								$User->SetUser($real_custom_infos[0], 'subscription_date_end', date('d/m/Y', strtotime("+1 months")));

							}

							$Invoice->CreateInvoice($User->GetUser($_SESSION['id'], 'email'), 'Abonnement 1 mois FLASH', $amount, $_GET['paymentId']);
							$Paypal_Checkout->CreatePaypalCheckout($_GET['paymentId'], $_GET['token'], $_GET['PayerID']);

						} else {

							$Invoice->CreateInvoice($User->GetUser($_SESSION['id'], 'email'), 'Abonnement 1 mois FLASH', 14.99, $_GET['paymentId']);
							$Paypal_Checkout->CreatePaypalCheckout($_GET('paymentId'), $_GET['token'], $_GET['PayerID']);
							die('Une erreur est survenue: le code promo utilisé est invalide. Nous ne pouvons procéder à votre abonnement et vous pouvez exiger un remboursement des frais engendrés lors de la transaction en nous contactant.');

						}

					} else {

							$User->SetUser($real_custom_infos[0], 'subscription_type', $real_custom_infos[1]);
							$User->SetUser($real_custom_infos[0], 'subscription_date_end', date('d/m/Y', strtotime("+1 months")));
							$Invoice->CreateInvoice($User->GetUser($_SESSION['id'], 'email'), 'Abonnement 1 mois DIAMOND', 14.99, $_GET['paymentId']);
							$Paypal_Checkout->CreatePaypalCheckout($_GET['paymentId'], $_GET['token'], $_GET['PayerID']);

					}

				} else {

					header("Location: vip.php?p=abonnement");

				}

			} else {

				if(!($Paypal_Checkout->GetPaypalCheckout($Paypal_Checkout->GetPaypalCheckoutIdFromPaymentId($_GET['paymentId']), 'token') == $_GET['token']))
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

					$payment = \PayPal\Api\Payment::get($_GET['paymentId'], $apiContext);

					$execution = (new \PayPal\Api\PaymentExecution())
						->setPayerId($_GET['PayerID'])
						->setTransactions($payment->getTransactions());
					$payment->execute($execution, $apiContext);

					$custom_infos = explode(',', $payment->getTransactions()[0]->getCustom(0));

					$real_custom_infos = explode('|', $custom_infos[0]);

					if($real_custom_infos[1] == '2')
					{

						if(strlen($real_custom_infos[2]) > 0)
						{
							if($Code_Promo->ExistsPromoSecretCode($real_custom_infos[2]))
							{

								$amount = round((14.99 - (($Code_Promo->GetPromoCode($Code_Promo->GetPromoCodeIdFromSecretCode($real_custom_infos[2]), 'promo_value')*14.99)/100)), 2, PHP_ROUND_HALF_UP);

								if($Code_Promo->GetPromoCode($Code_Promo->GetPromoCodeIdFromSecretCode($real_custom_infos[2]), 'for_all') != 1)
								{

									$Code_Promo->DeletePromoCode($Code_Promo->GetPromoCodeIdFromSecretCode($real_custom_infos[2]));
									$User->SetUser($real_custom_infos[0], 'subscription_type', $real_custom_infos[1]);
									$User->SetUser($real_custom_infos[0], 'subscription_date_end', date('d/m/Y', strtotime("+1 months")));

								} else {

									$User->SetUser($real_custom_infos[0], 'subscription_type', $real_custom_infos[1]);
									$User->SetUser($real_custom_infos[0], 'subscription_date_end', date('d/m/Y', strtotime("+1 months")));

								}

								$Invoice->CreateInvoice($User->GetUser($_SESSION['id'], 'email'), 'Abonnement 1 mois FLASH', $amount, $_GET['paymentId']);
								$Paypal_Checkout->CreatePaypalCheckout($_GET['paymentId'], $_GET['token'], $_GET['PayerID']);

							} else {

								$Invoice->CreateInvoice($User->GetUser($_SESSION['id'], 'email'), 'Abonnement 1 mois FLASH', 14.99, $_GET['paymentId']);
								$Paypal_Checkout->CreatePaypalCheckout($_GET['paymentId'], $_GET['token'], $_GET['PayerID']);
								die('Une erreur est survenue: le code promo utilisé est invalide. Nous ne pouvons procéder à votre abonnement et vous pouvez exiger un remboursement des frais engendrés lors de la transaction en nous contactant.');

							}

						} else {

							$User->SetUser($real_custom_infos[0], 'subscription_type', $real_custom_infos[1]);
							$User->SetUser($real_custom_infos[0], 'subscription_date_end', date('d/m/Y', strtotime("+1 months")));
							$Invoice->CreateInvoice($User->GetUser($_SESSION['id'], 'email'), 'Abonnement 1 mois DIAMOND', 14.99, $_GET['paymentId']);
							$Paypal_Checkout->CreatePaypalCheckout($_GET['paymentId'], $_GET['token'], $_GET['PayerID']);

						}

					} else {

						header("Location: vip.php?p=abonnement");

					}

				}
			}
		} catch(\PayPal\Exception\PayPalConnectionException $e)
		{
			header("Location: vip.php?p=abonnement");
		}

	} else {

			header("Location: vip.php?p=abonnement");

	}

} else {

	header("Location: vip.php?p=abonnement");

}

?>
<!doctype html>
<html class="fixed">
	<head>

		<!-- Basic -->
		<meta charset="UTF-8">

		<title>Rock Money : Expert en paris sportifs, finance et poker</title>
		<meta name="keywords" content="HTML5 Admin Template" />
		<meta name="description" content="Porto Admin - Responsive HTML5 Template">
		<meta name="author" content="okler.net">

		<!-- Mobile Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

		<!-- Web Fonts  -->
		<link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">

		<!-- Vendor CSS -->
		<link rel="stylesheet" href="core/assets/vendor/bootstrap/css/bootstrap.css" />
		<link rel="stylesheet" href="core/assets/vendor/font-awesome/css/font-awesome.css" />
		<link rel="stylesheet" href="core/assets/vendor/magnific-popup/magnific-popup.css" />
		<link rel="stylesheet" href="core/assets/vendor/bootstrap-datepicker/css/datepicker3.css" />

		<!-- Theme CSS -->
		<link rel="stylesheet" href="core/assets/stylesheets/theme.css" />

		<!-- Skin CSS -->
		<link rel="stylesheet" href="core/assets/stylesheets/skins/default.css" />

		<!-- Theme Custom CSS -->
		<link rel="stylesheet" href="core/assets/stylesheets/theme-custom.css">

		<!-- Head Libs -->
		<script src="core/assets/vendor/modernizr/modernizr.js"></script>

		<link rel="icon" type="image/png" href="core/images/favicon.png">

	</head>
	<body>
		<section class="body">

			<!-- start: header -->
			<header class="header">
				<div class="logo-container">
					<a href="../" class="logo">
						<img src="core/images/logo-rest.png" height="35" alt="Porto Admin" />
					</a>
					<div class="visible-xs toggle-sidebar-left" data-toggle-class="sidebar-left-opened" data-target="html" data-fire-event="sidebar-left-opened">
						<i class="fa fa-bars" aria-label="Toggle sidebar"></i>
					</div>
				</div>
			
				<!-- start: search & user box -->
				<div class="header-right">
			
					<span class="separator"></span>
			
					<ul class="notifications">
						<li>
							<a href="#" class="dropdown-toggle notification-icon" data-toggle="dropdown">
								<i class="fa fa-bell"></i>
								<span class="badge" style="background: grey;">0</span>
							</a>
			
							<div class="dropdown-menu notification-menu">
								<div class="notification-title">
									<span class="pull-right label label-default">0</span>
									Notifications
								</div>
			
								<div class="content">
									<ul>
										<li>
											<a href="#" class="clearfix">
												<div class="image">
													<i class="fa fa-thumbs-down bg-danger"></i>
												</div>
												<span class="title">Pari perdu !</span>
												<span class="message">13/02/19 - #1</span>
											</a>
										</li>
										<li>
											<a href="#" class="clearfix">
												<div class="image">
													<i class="fa fa-thumbs-up bg-success"></i>
												</div>
												<span class="title">Pari gagné !</span>
												<span class="message">13/02/19 - #2</span>
											</a>
										</li>
									</ul>
								</div>
							</div>
						</li>
					</ul>
			
					<span class="separator"></span>
			
					<div id="userbox" class="userbox">
						<a href="#" data-toggle="dropdown">
							<figure class="profile-picture">
								<img src="core/assets/images/!happy-face.png" alt="Joseph Doe" class="img-circle" data-lock-picture="assets/images/!logged-user.jpg" />
							</figure>
							<div class="profile-info" data-lock-name="John Doe" data-lock-email="johndoe@okler.com">
								<span class="name">
									<?php

									if($isValidSession == true)
									{
										echo htmlentities($User->GetMemberName($User->GetUser($User->GetUserIdFromEmail($_SESSION['email']), 'email')));
									}

									?>
								</span>
								<span class="role">
									<?php

									if($isValidSession == true)
									{
										echo $User->GetMemberRank($User->GetUser($User->GetUserIdFromEmail($_SESSION['email']), 'member_rank'));
									}

									?>
								</span>
							</div>
			
							<i class="fa custom-caret"></i>
						</a>
			
						<div class="dropdown-menu">
							<ul class="list-unstyled">
								<li class="divider"></li>
								<li>
									<a role="menuitem" tabindex="-1" href="vip.php?p=compte"><i class="fa fa-user"></i> Mon compte</a>
								</li>
								<li>
									<a role="menuitem" tabindex="-1" href="#" id="logout-button"><i class="fa fa-power-off"></i> Se déconnecter</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<!-- end: search & user box -->
			</header>
			<!-- end: header -->

			<div class="inner-wrapper">
				<!-- start: sidebar -->
				<aside id="sidebar-left" class="sidebar-left">
				
					<div class="sidebar-header">
						<div class="sidebar-title">
							<span style="color: white;"><i class="fa fa-star"></i> Espace VIP</span>
						</div>
						<div class="sidebar-toggle hidden-xs" data-toggle-class="sidebar-left-collapsed" data-target="html" data-fire-event="sidebar-left-toggle">
							<i class="fa fa-bars" aria-label="Toggle sidebar"></i>
						</div>
					</div>
				
					<div class="nano">
						<div class="nano-content">
							<nav id="menu" class="nav-main" role="navigation">
								<ul class="nav nav-main">
									<li class="nav-active">
										<a href="vip.php?p=accueil">
											<i class="fa fa-star" aria-hidden="true"></i>
											<span>Pronostics</span>
										</a>
									</li>
									<li>
										<a href="vip.php?p=statistiques">
											<i class="fa fa-bars" aria-hidden="true"></i>
											<span>Statistiques</span>
										</a>
									</li>
									<li>
										<a href="vip.php?p=gestion_bankroll">
											<i class="fa fa-euro" aria-hidden="true"></i>
											<span>Gestion de bankroll</span>
										</a>
									</li>
									<li class="nav-parent">
										<a>
											<i class="fa fa-user" aria-hidden="true"></i>
											<span>Compte</span>
										</a>
										<ul class="nav nav-children">
											<li>
												<a href="pages-signup.html">
													 Paramètres du compte
												</a>
											</li>
											<li>
												<a href="pages-signin.html">
													 Factures
												</a>
											</li>
											<li>
												<a href="pages-recover-password.html">
													 Gestion d'abonnement
												</a>
											</li>
										</ul>
									</li>
								</ul>
							</nav>
						</div>
				
					</div>
				
				</aside>
				<!-- end: sidebar -->

				<section role="main" class="content-body">
					<header class="page-header">
						<h2>Abonnement</h2>
					</header>

					<section class="panel">
						<div class="panel-body">
							<div class="invoice">
								<header class="clearfix">
									<div class="row">
										<div class="col-12">
											<center><h2 class="h2 mt-none mb-sm text-dark text-bold">Abonnement réussi !</h2></center>
										</div>
									</div>
								</header>
								<div class="row">
									<div class="col-12">
										<div class="alert alert-success">
											<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
											<strong>Félicitations !</strong> Vous êtes dorénavant abonné au pack Rock Money FLASH !<br />
											Nous espérons que votre expérience sera irréprochable.
										</div>
									</div>

								</div>
							</div>
						</div>
					</section>

					<!-- end: page -->
				</section>
			</div>
		</section>
	</body>

		<!-- Vendor -->
		<script src="core/assets/vendor/jquery/jquery.js"></script>
		<script src="core/assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
		<script src="core/assets/vendor/bootstrap/js/bootstrap.js"></script>
		<script src="core/assets/vendor/nanoscroller/nanoscroller.js"></script>
		<script src="core/assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
		<script src="core/assets/vendor/magnific-popup/magnific-popup.js"></script>
		<script src="core/assets/vendor/jquery-placeholder/jquery.placeholder.js"></script>

		<script src="core/assets/vendor/pnotify/pnotify.custom.js"></script>
		
		<!-- Theme Base, Components and Settings -->
		<script src="core/assets/javascripts/theme.js"></script>
		
		<!-- Theme Custom -->
		<script src="core/assets/javascripts/theme.custom.js"></script>
		
		<!-- Theme Initialization Files -->
		<script src="core/assets/javascripts/theme.init.js"></script>

		<script>
			$(document).ready(function()
			{

				$(document).on('click', '#logout-button', function()
				{
					
					$.post(

						'app/processing/vip/logout.php',
						function(data)
						{
							if(data == 'Success')
							{

								document.location.href = "index.php?p=accueil";

							} else {

								document.location.href = "index.php?p=accueil";

							}
						}

					);

				});

			});
		</script>
</html>