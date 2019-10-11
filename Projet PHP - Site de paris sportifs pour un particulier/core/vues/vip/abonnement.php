<?php

session_start();

require_once('app/database.php');
require_once('app/models/user.class.php');

$Database = new Database();
$User = new User();

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

		<link rel="stylesheet" href="core/assets/vendor/pnotify/pnotify.custom.css" />

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
											<center><h2 class="h2 mt-none mb-sm text-dark text-bold">Vous n'êtes pas abonné. Pourquoi hésitez-vous encore ?</h2></center>
										</div>
									</div>
								</header>
								<center><h4>Choisissez parmi nos 3 formules ci-dessous :</h4></center>
								<div class="row">
									<div class="col-md-4">
										<section class="panel panel-featured panel-featured-primary">
											<header class="panel-heading">
												<h2 class="panel-title">Formule Starter</h2>
											</header>
											<div class="panel-body">
												<center>
													<h5>2 tickets simples par jour</h5>
													<hr class="separator" />
													<h5>Accès à l'espace VIP</h5>
													<hr class="separator" />
													<h5>Taux de réussite: 92%</h5>
													<hr class="separator" />
													<h4><strong>Prix : 4,99€ TTC</strong> /mois</h4>
													<hr class="separator" />
													<button type="button" class="mb-xs mt-xs mr-xs btn btn-primary modal-basic" href="#modal-first-subscription">Commander</button>
												</center>
											</div>
										</section>
									</div>
									<div class="col-md-4">
										<section class="panel panel-featured panel-featured-warning">
											<header class="panel-heading">
												<h2 class="panel-title">Formule Flash</h2>
											</header>
											<div class="panel-body">
												<center>
													<h5>Éléments de la formule Starter</h5>
													<hr class="separator" />
													<h5>1 ticket combiné par jour (cote min: 2)</h5>
													<hr class="separator" />
													<h5>Recevez les pronostics 2h en avance par rapport aux VIP Starter</h5>
													<hr class="separator" />
													<h5>-50% sur la formation Poker</h5>
													<hr class="separator" />
													<h5>-50% sur la formation Finance</h5>
													<hr class="separator" />
													<h5>Taux de réussite: 89%</h5>
													<hr class="separator" />
													<h4><strong>Prix : 14,99€ TTC</strong> /mois</h4>
													<hr class="separator" />
													<button type="button" class="mb-xs mt-xs mr-xs btn btn-warning modal-basic" href="#modal-second-subscription">Commander</button>
												</center>
											</div>
										</section>
									</div>
									<div class="col-md-4">
										<section class="panel panel-featured panel-featured-danger">
											<header class="panel-heading">
												<h2 class="panel-title">Formule Diamond</h2>
											</header>
											<div class="panel-body">
												<center>
													<h5>Éléments de la formule Flash</h5>
													<hr class="separator" />
													<h5>1 ticket combiné par jour (cote min: 2)</h5>
													<hr class="separator" />
													<h5>Recevez les pronostics 4h en avance par rapport aux VIP Starter</h5>
													<hr class="separator" />
													<h5>Recevez les pronostics directement par SMS</h5>
													<hr class="separator" />
													<h5>Formation Poker gratuite</h5>
													<hr class="separator" />
													<h5>Formation Finance gratuite</h5>
													<hr class="separator" />
													<h5>1 publicité SNAP offerte</h5>
													<hr class="separator" />
													<h5>Taux de réussite: 86%</h5>
													<hr class="separator" />
													<h4><strong>Prix : 24,99€ TTC</strong> /mois</h4>
													<hr class="separator" />
													<button type="button" class="mb-xs mt-xs mr-xs btn btn-danger modal-basic" href="#modal-third-subscription">Commander</button>
												</center>
											</div>
										</section>
									</div>

								</div>
							</div>
						</div>
					</section>

					<div id="modal-first-subscription" class="modal-block modal-header-color modal-block-primary mfp-hide">
						<section class="panel">
							<header class="panel-heading">
								<h2 class="panel-title">Confirmation d'abonnement</h2>
							</header>
							<div class="panel-body">
								<div class="modal-wrapper">
									<div class="modal-icon">
										<i class="fa fa-question-circle"></i>
									</div>
									<div class="modal-text">
										<div class="alert alert-danger code_promo_invalide">
											<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
											<strong>Erreur !</strong> Ce code promo est invalide ou n'est pas réservé à cette formule. N'oubliez pas de respecter chaque caractère, y compris les majuscules et les minuscules.
										</div>
										<div class="alert alert-info confirmation_abonnement_1">
											<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
											<strong>Veuillez patienter...</strong> Vous allez être redirigé(e) dans quelques instants vers la page de paiement PayPal pour effectuer votre transaction.
										</div>
										<h4>Abonnement pack STARTER</h4>
										<p>Vous serez redirigé(e), après vérification d'un code promo éventuel, vers la page de paiement PayPal pour effectuer votre transaction.</p>
										<br />
										<p>Code promo : (Laissez vide si pas de code promo)</p>
										<input type="text" class="form-control" id="code_promo">
									</div>
								</div>
							</div>
							<footer class="panel-footer">
								<div class="row">
									<div class="col-md-12 text-right">
										<button class="btn btn-primary subscription-confirm">Confirmer</button>
										<button class="btn btn-default subscription-dismiss">Annuler</button>
									</div>
								</div>
							</footer>
						</section>
					</div>

					<div id="modal-second-subscription" class="modal-block modal-header-color modal-block-warning mfp-hide">
						<section class="panel">
							<header class="panel-heading">
								<h2 class="panel-title">Confirmation d'abonnement</h2>
							</header>
							<div class="panel-body">
								<div class="modal-wrapper">
									<div class="modal-icon">
										<i class="fa fa-question-circle"></i>
									</div>
									<div class="modal-text">
										<div class="alert alert-danger code_promo_invalide_2">
											<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
											<strong>Erreur !</strong> Ce code promo est invalide ou n'est pas réservé à cette formule. N'oubliez pas de respecter chaque caractère, y compris les majuscules et les minuscules.
										</div>
										<div class="alert alert-info confirmation_abonnement_2">
											<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
											<strong>Veuillez patienter...</strong> Vous allez être redirigé(e) dans quelques instants vers la page de paiement PayPal pour effectuer votre transaction.
										</div>
										<h4>Abonnement pack FLASH</h4>
										<p>Vous serez redirigé(e), après vérification d'un code promo éventuel, vers la page de paiement PayPal pour effectuer votre transaction.</p>
										<br />
										<p>Code promo : (Laissez vide si pas de code promo)</p>
										<input type="text" class="form-control" id="code_promo_2">
									</div>
								</div>
							</div>
							<footer class="panel-footer">
								<div class="row">
									<div class="col-md-12 text-right">
										<button class="btn btn-primary subscription-confirm-2">Confirmer</button>
										<button class="btn btn-default subscription-dismiss">Annuler</button>
									</div>
								</div>
							</footer>
						</section>
					</div>

					<div id="modal-third-subscription" class="modal-block modal-header-color modal-block-danger mfp-hide">
						<section class="panel">
							<header class="panel-heading">
								<h2 class="panel-title">Confirmation d'abonnement</h2>
							</header>
							<div class="panel-body">
								<div class="modal-wrapper">
									<div class="modal-icon">
										<i class="fa fa-question-circle"></i>
									</div>
									<div class="modal-text">
										<div class="alert alert-danger code_promo_invalide_3">
											<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
											<strong>Erreur !</strong> Ce code promo est invalide ou n'est pas réservé à cette formule. N'oubliez pas de respecter chaque caractère, y compris les majuscules et les minuscules.
										</div>
										<div class="alert alert-info confirmation_abonnement_3">
											<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
											<strong>Veuillez patienter...</strong> Vous allez être redirigé(e) dans quelques instants vers la page de paiement PayPal pour effectuer votre transaction.
										</div>
										<h4>Abonnement pack FLASH</h4>
										<p>Vous serez redirigé(e), après vérification d'un code promo éventuel, vers la page de paiement PayPal pour effectuer votre transaction.</p>
										<br />
										<p>Code promo : (Laissez vide si pas de code promo)</p>
										<input type="text" class="form-control" id="code_promo_3">
									</div>
								</div>
							</div>
							<footer class="panel-footer">
								<div class="row">
									<div class="col-md-12 text-right">
										<button class="btn btn-primary subscription-confirm-3">Confirmer</button>
										<button class="btn btn-default subscription-dismiss">Annuler</button>
									</div>
								</div>
							</footer>
						</section>
					</div>

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

		<script src="core/assets/javascripts/ui-elements/examples.modals.js"></script>
		
		<!-- Theme Base, Components and Settings -->
		<script src="core/assets/javascripts/theme.js"></script>
		
		<!-- Theme Custom -->
		<script src="core/assets/javascripts/theme.custom.js"></script>
		
		<!-- Theme Initialization Files -->
		<script src="core/assets/javascripts/theme.init.js"></script>

		<script>
			$(document).ready(function()
			{

				$('.code_promo_invalide').hide();
				$('.confirmation_abonnement_1').hide();
				$('.code_promo_invalide_2').hide();
				$('.confirmation_abonnement_2').hide();
				$('.code_promo_invalide_3').hide();
				$('.confirmation_abonnement_3').hide();

				$(document).on('click', '#logout-button', function(e)
				{

					e.preventDefault();
					
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

				$(document).on('click', '.subscription-dismiss', function (e) {
					e.preventDefault();
					$.magnificPopup.close();
				});

				$(document).on('click', '.subscription-confirm', function (e) {
					
					e.preventDefault();

					var code_promo = $('#code_promo');

					if(code_promo.val().length > 0)
					{

						$.post(

							'app/processing/vip/is_valid_promo_code.php',
							{
								code_promo : code_promo.val(),
							},
							function(data)
							{
								if(data == 'Success')
								{

									$('.code_promo_invalide').hide();
									$('.confirmation_abonnement_1').show();

									$.get(

										'app/processing/vip/subscription_first_set.php',
										{
											code_promo : code_promo.val(),
										},
										function(data)
										{
											if(data != 'Error')
											{

												document.location.href = data;

											}
										},

									);

								} else {

									$('.code_promo_invalide').show();

								}
							}

						);

					} else {

						$('.code_promo_invalide').hide();
						$('.confirmation_abonnement_1').show();

						$.get(

							'app/processing/vip/subscription_first_set.php',
							function(data)
							{
								if(data != 'Error')
								{
												
									document.location.href = data;

								} else {

									document.location.href = 'vip.php?p=abonnement';

								}
							},

						);

					}

				});

				$(document).on('click', '.subscription-confirm-2', function (e) {
					
					e.preventDefault();

					var code_promo_2 = $('#code_promo_2');

					if(code_promo_2.val().length > 0)
					{

						$.post(

							'app/processing/vip/is_valid_promo_code_2.php',
							{
								code_promo_2 : code_promo_2.val(),
							},
							function(data)
							{
								if(data == 'Success')
								{

									$('.code_promo_invalide_2').hide();
									$('.confirmation_abonnement_2').show();

									$.get(

										'app/processing/vip/subscription_second_set.php',
										{
											code_promo_2 : code_promo_2.val(),
										},
										function(data)
										{
											if(data != 'Error')
											{

												document.location.href = data;

											}
										},

									);

								} else {

									$('.code_promo_invalide_2').show();

								}
							}

						);

					} else {

						$('.code_promo_invalide_2').hide();
						$('.confirmation_abonnement_2').show();

						$.get(

							'app/processing/vip/subscription_second_set.php',
							function(data)
							{
								if(data != 'Error')
								{
												
									document.location.href = data;

								} else {

									document.location.href = 'vip.php?p=abonnement';

								}
							},

						);

					}

				});

				$(document).on('click', '.subscription-confirm-3', function (e) {
					
					e.preventDefault();

					var code_promo_3 = $('#code_promo_3');

					if(code_promo_3.val().length > 0)
					{

						$.post(

							'app/processing/vip/is_valid_promo_code_3.php',
							{
								code_promo_3 : code_promo_3.val(),
							},
							function(data)
							{
								if(data == 'Success')
								{

									$('.code_promo_invalide_3').hide();
									$('.confirmation_abonnement_3').show();

									$.get(

										'app/processing/vip/subscription_third_set.php',
										{
											code_promo_3 : code_promo_3.val(),
										},
										function(data)
										{
											if(data != 'Error')
											{

												document.location.href = data;

											}
										},

									);

								} else {

									$('.code_promo_invalide_3').show();

								}
							}

						);

					} else {

						$('.code_promo_invalide_3').hide();
						$('.confirmation_abonnement_3').show();

						$.get(

							'app/processing/vip/subscription_third_set.php',
							function(data)
							{
								if(data != 'Error')
								{
												
									document.location.href = data;

								} else {

									document.location.href = 'vip.php?p=abonnement';

								}
							},

						);

					}

				});

			});
		</script>
</html>