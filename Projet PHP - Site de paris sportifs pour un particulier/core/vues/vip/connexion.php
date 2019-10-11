<?php

session_start();

require_once('app/database.php');
require_once('app/models/user.class.php');

$Database = new Database();
$User = new User();

if(isset($_SESSION['id']))
{
	if($User->GetUser($_SESSION['id'], 'email') == $_SESSION['email'])
	{

		if(!($User->GetUser($_SESSION['id'], 'password') == $_SESSION['password']))
		{

			session_destroy();
			session_unset();

		}

	} else {

		session_destroy();
		session_unset();

	}

	header("Location: vip.php?p=accueil");
}

?>

<!doctype html>
<html class="fixed">
	<head>

		<!-- Basic -->
		<meta charset="UTF-8">

		<meta name="keywords" content="HTML5 Admin Template" />
		<meta name="description" content="Nous sommes des experts en paris sportifs, poker et finance. Rejoignez-nous !">

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

		<title>Rock Money : Expert en paris sportifs, finance et poker</title>

	</head>
	<body>
		<!-- start: page -->
		<section class="body-sign">
			<div class="center-sign">
				<a href="/" class="logo pull-left">
					<img src="core/images/logo-rest.png" height="54" alt="Porto Admin" />
				</a>

				<div class="panel panel-sign">
					<div class="panel-title-sign mt-xl text-right">
						<h2 class="title text-uppercase text-bold m-none"><i class="fa fa-user mr-xs"></i> Connexion</h2>
					</div>
					<div class="panel-body">
						<form>
							<div class="form-group mb-lg">
								<label>Adresse email</label>
								<div class="input-group input-group-icon">
									<input id="email" name="email" type="email" class="form-control input-lg" />
									<span class="input-group-addon">
										<span class="icon icon-lg">
											<i class="fa fa-user"></i>
										</span>
									</span>
								</div>
							</div>

							<div class="form-group mb-lg">
								<div class="clearfix">
									<label class="pull-left">Mot de passe</label>
									<a href="vip.php?p=nouveau_mdp" class="pull-right">Mot de passe oublié ?</a>
								</div>
								<div class="input-group input-group-icon">
									<input id="password" name="pwd" type="password" class="form-control input-lg" />
									<span class="input-group-addon">
										<span class="icon icon-lg">
											<i class="fa fa-lock"></i>
										</span>
									</span>
								</div>
							</div>

							<div class="row">
								<div class="col-sm-8">
									<div class="checkbox-custom checkbox-default">
										<input id="RememberMe" name="rememberme" type="checkbox"/>
										<label for="RememberMe">Se souvenir de moi</label>
									</div>
								</div>
								<div class="col-sm-4 text-right">
									<button id="login-btn" type="submit" class="btn btn-primary">Connexion</button>
								</div>
							</div>

							<span class="mt-lg mb-lg line-thru text-center text-uppercase">
								<span>Ou</span>
							</span>

							<p class="text-center">Vous n'êtes pas encore inscrit ? <a href="vip.php?p=inscription">Inscrivez-vous !</a>

						</form>
					</div>
				</div>

				<p class="text-center text-muted mt-md mb-md">&copy; Copyright 2019 Rock Money - Tous droits réservés</p>
			</div>
		</section>
		<!-- end: page -->

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

		<script src="core/assets/javascripts/ui-elements/examples.notifications.js"></script>

		<script>
			$(document).on('click', '#login-btn', function(e) {

				e.preventDefault();

				var email = $('#email');
				var password = $('#password');

				if(email.val().length > 0 && password.val().length > 0)
				{

					var emailRegex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
					var isValidEmail = email.val().match(emailRegex);

					if(isValidEmail == null){

						var notice = new PNotify({
							title: 'Erreur',
							text: 'Veuillez saisir une adresse email valide.',
							type: 'error',
							addclass: 'stack-bar-top',
							stack: {"dir1": "down", "dir2": "right", "push": "top", "spacing1": 0, "spacing2": 0},
							width: "100%"
						});	

					} else {

						$.post(

							'app/processing/vip/connexion.php',
							{
								email : email.val(),
								password : password.val(),
							},
							function(data)
							{
								if(data == 'Success')
								{

									document.location.href = "vip.php?p=accueil";

								} else {

									var notice = new PNotify({
										title: 'Erreur',
										text: 'Adresse email ou mot de passe incorrect.',
										type: 'error',
										addclass: 'stack-bar-top',
										stack: {"dir1": "down", "dir2": "right", "push": "top", "spacing1": 0, "spacing2": 0},
										width: "100%"
									});

								}
							}

						);

					}

				} else {

					var notice = new PNotify({
						title: 'Erreur',
						text: 'Veuillez saisir tous les champs.',
						type: 'error',
						addclass: 'stack-bar-top',
						stack: {"dir1": "down", "dir2": "right", "push": "top", "spacing1": 0, "spacing2": 0},
						width: "100%"
					});

				}

			});

		</script>

	</body>
</html>