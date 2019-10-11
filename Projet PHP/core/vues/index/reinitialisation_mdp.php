<?php 

session_start();

require_once('app/database.php');
require_once('app/models/essay.class.php');
require_once('app/models/user.class.php');
$Database = new Database();
$Essay = new Essay();
$User = new User();

$isValidRequest = false;

if(isset($_SESSION['id'])){

	header('Location: index.php?p=accueil');

} else {

	if(isset($_GET['email']) && isset($_GET['npk'])){

		$email = htmlentities(trim($_GET['email']));
		$npk = htmlentities(trim($_GET['npk']));

		if($User->ExistsUserWithEmail($email)){

			if($User->GetUser($User->GetUserIdFromEmail($email), 'newpassword_key') == $npk){

				if($User->GetUser($User->GetUserIdFromEmail($email), 'newpassword_key') == 'N'){

					header('Location: index.php?p=accueil');

				} else {

					$isValidRequest = true;

				}

			} else {

				header('Location: index.php?p=accueil');

			}

		} else {

			header('Location: index.php?p=accueil');

		}

	} else {

		header('Location: index.php?p=accueil');

	}

}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="Samuel Clauzon">
<link rel="icon" href="core/assets/img/favicon.png">
<title>La Taverne Aux Essais</title>
<!-- Bootstrap core CSS -->
<link href="core/assets/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome CSS -->
<link href="core/assets/css/font-awesome.min.css" rel="stylesheet">
<!-- Jasny CSS -->
<link href="core/assets/css/jasny-bootstrap.min.css" rel="stylesheet">
<!-- Animate CSS -->
<link href="core/assets/css/animate.css" rel="stylesheet">
<!-- Code CSS -->
<link href="core/assets/css/tomorrow-night.css" rel="stylesheet" />
<!-- Gallery CSS -->
<link href="core/assets/css/bootstrap-gallery.css" rel="stylesheet">
<!-- ColorBox CSS -->
<link href="core/assets/css/colorbox.css" rel="stylesheet">
<!-- Custom font -->
<link href='http://fonts.googleapis.com/css?family=Raleway:400,200,100,300,500,600,700,800,900' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Roboto+Slab&amp;subset=latin,latin-ext' rel='stylesheet' type='text/css'>
<!-- Custom styles for this template -->
<link href="core/assets/css/style.css" rel="stylesheet">
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
</head>
<body>
	<div class="page-loader">
		<div class="loader-in">Loading...</div>
		<div class="loader-out">Loading...</div>
	</div>

	<div class="canvas">
		<div class="canvas-overlay"></div>
		<header>
			<nav class="navbar navbar-fixed-top nav-down navbar-laread">
				<div class="container">
					<div class="navbar-header">
						<a class="navbar-brand" href="medium-image-v1-2.html"><img height="64" src="core/assets/img/logo-light.png" alt=""></a>
					</div>
					<button type="button" class="navbar-toggle collapsed menu-collapse" data-toggle="collapse" data-target="#main-nav">
						<span class="sr-only">Toggle navigation</span>
						<i class="fa fa-plus"></i>
					</button>
					<div class="collapse navbar-collapse" id="main-nav">
						<ul class="nav navbar-nav">
							<li>
								<a href="#"><i class="fa fa-home"></i> ACCUEIL</a>
							</li>
							<li>
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-list"></i> RUBRIQUES</a>
								<?php

								if($Essay->CountEssayCategories() > 0){
									$Essay->ShowMenuEssayCategories();
								} else {

									echo '<ul class="dropdown-menu" role="menu">';
									echo '<li><a href="#">Aucune rubrique n\'est disponible pour le moment.</a></li>';
									echo '</ul>';

								}

								?>
							</li>
							<li>
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-star"></i> CLASSEMENTS</a>
								<ul class="dropdown-menu" role="menu">
									<li><a href="#">Par rubriques</a></li>
									<li><a href="#">Par essais</a></li>
									<li><a href="#">Par auteurs</a></li>
								</ul>
							</li>
							<li>
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-plus"></i> PUBLIER</a>
								<ul class="dropdown-menu" role="menu">
									<li><a href="#">Méthode de l'essai</a></li>
									<li><a href="#">Publier un essai</a></li>
								</ul>
							</li>
							<li>
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-question"></i> À PROPOS</a>
								<ul class="dropdown-menu" role="menu">
									<li><a href="#">Notre équipe</a></li>
									<li><a href="#">À propos du projet</a></li>
								</ul>
							</li>
							<li>
								<a href="#""><i class="fa fa-location-arrow"></i> CONTACT</a>
							</li>
						</ul>
					</div><!--/.nav-collapse -->
				</div>
			</nav>
		</header>

		<div class="container">
			<div class="head-text">
				<img src="core/assets/img/ltae_logo.png" alt="">
			</div>
		</div>

		<section class="post-fluid">
			<div class="container-fluid">
				<div class="row laread-contact">
					<div class="contact-info">
						<h2>Ne nous quittez pas !</h2>
						<p class="text-contact">Réinitialisez dès maintenant votre mot de passe !</p>
						<div class="contact-form-vertical">
							<div id="alert-registration" class="laalert alert-danger alert-dismissible fade in" role="alert">
								<strong>Erreur !</strong> Erreur
							</div>
							<div id="success-registration" class="laalert alert-success alert-dismissible fade in" role="alert">
								<strong>Félicitations !</strong> Un email de réinitialisation de mot de passe vient de vous être envoyé. Veuillez nous <a href="index.php?p=contact">contacter</a> si vous ne l'avez pas reçu.
							</div>
							<form>
								<label>Question secrète : <strong><?php if($isValidRequest == true){ echo $User->GetUser($User->GetUserIdFromEmail($email), 'secret_question'); } ?></strong></label>
								<input class="contact-input" type="text" placeholder="Réponse à la question secrète" id="secret_question_answer" />
								<input class="contact-input" type="password" placeholder="Nouveau mot de passe" id="new_password" />
								<input class="contact-input" type="password" placeholder="Répétez votre nouveau mot de passe" id="new_password_repeat" />
								<br />
								<button class="btn btn-golden btn-block btn-newpassword"><span>JE VEUX RÉINITIALISER MON MOT DE PASSE</span></button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</section>

		<footer class="container-fluid footer">
			<div class="container text-center">
				<div class="footer-logo"><img src="core/assets/img/logo-black.png" alt=""></div>
				<p class="laread-motto">N'ayez plus peur d'exprimer vos opinions en toute créativité !</p>
				<div class="laread-social">
					<a href="#" class="fa fa-twitter"></a>
					<a href="#" class="fa fa-facebook"></a>
					<a href="#" class="fa fa-pinterest"></a>
				</div>
			</div>
		</footer>
	</div>

	<!-- Bootstrap core JavaScript
	================================================== -->
	<!-- Placed at the end of the document so the pages load faster -->
	<script src="core/assets/js/jquery.min.js"></script>
	<script src="core/assets/js/bootstrap.min.js"></script>
	<script src="core/assets/js/jasny-bootstrap.min.js"></script>
	<script src="core/assets/js/prettify.js"></script>
	<script src="core/assets/js/lang-css.js"></script>
	<script src="core/assets/js/jquery.blueimp-gallery.min.js"></script>
	<script src="core/assets/js/imagesloaded.js"></script>
	<script src="core/assets/js/masonry.js"></script>
	<script src="core/assets/js/viewportchecker.js"></script>
	<script src="core/assets/js/jquery.dotdotdot.min.js"></script>
	<script src="core/assets/js/jquery.colorbox-min.js"></script>
	<script src="core/assets/js/jquery.nicescroll.min.js"></script>
	<script src="core/assets/js/isotope.pkgd.min.js"></script>
	<script src="core/assets/js/jquery.ellipsis.min.js"></script>
	<script src="core/assets/js/calendar.js"></script>
	<script src="core/assets/js/jquery.touchSwipe.min.js"></script>
	<script src="core/assets/js/script.js"></script>
	<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>

	<script type="text/javascript">
		
		$(document).ready(function(){

			var error = document.getElementById('alert-registration');
			var success_registration = document.getElementById('success-registration');

			error.style.display = 'none';
			success_registration.style.display = 'none';

			$('.btn-newpassword').on('click', function(e){

				e.preventDefault();

				var input_secret_question = $('#secret_question_answer');
				var input_newpassword = $('#new_password');
				var input_newpassword_repeat = $('#new_password_repeat');

				if(input_secret_question.val().length > 0 && input_newpassword.val().length > 0 && input_newpassword_repeat.val().length > 0){
					
					$.post(

						'app/processing/index/is_valid_secret_question_answer.php',
						{
							email : $_GET('email'),
							secret_question_answer : input_secret_question.val(),
						},
						function(data){

							if(data == 'Success'){

								error.style.display = 'none';

								var passwordRegex = /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{7,17}$/;
								var isValidPassword = input_newpassword.val().match(passwordRegex);

								if(isValidPassword == null){

									error.style.display = 'none';
									error.innerHTML = '<strong>Erreur !</strong> Votre mot de passe doit faire au minimum 8 caractères et au maximum 16 caractères. Il doit contenir au moins une lettre majuscule et une lettre minuscule et un chiffre. Il doit contenir au moins un caractère spécial.';
									error.style.display = 'block';	

								} else {

									if(input_newpassword.val() == input_newpassword_repeat.val()){

										$.post(

											'app/processing/index/reset-user-password.php',
											{
												email : $_GET('email'),
												secret_question_answer : input_secret_question.val(),
												new_password : input_newpassword.val(),
												new_password_repeat : input_newpassword_repeat.val(),
												npk : $_GET('npk'),

											},
											function(data){

												if(data == 'Success'){

													error.style.display = 'none';
													success_registration.style.display = 'none';
													success_registration.innerHTML = "<strong>Félicitations !</strong> Votre mot de passe a été réinitialisé avec succès. Un email contenant vos nouvelles informations de connexion vient de vous être envoyé à l'adresse " + $_GET('email') + ". Veuillez nous contacter en cas de problème.</center>";
													success_registration.style.display = 'block';

												} else {

													// Redirection

												}

											}

										);

									} else {

										error.style.display = 'none';
										error.innerHTML = '<strong>Erreur !</strong> Les deux mots de passe saisis ne sont pas identiques.';
										error.style.display = 'block';	

									}

								}

							} else {

								error.style.display = 'none';
								error.innerHTML = '<strong>Erreur !</strong> La réponse à la question secrète saisie ne correspond pas à celle que vous avez renseignée lors de votre inscription.';
								error.style.display = 'block';	

							}

						}

					);

				} else {

					error.style.display = 'none';
					error.innerHTML = '<strong>Erreur !</strong> Veuillez saisir tous les champs !';
					error.style.display = 'block';	

				}

			});

		});

		function $_GET(param) {

			var vars = {};
			window.location.href.replace( location.hash, '' ).replace( 
				/[?&]+([^=&]+)=?([^&]*)?/gi, // regexp
				function( m, key, value ) { // callback
					vars[key] = value !== undefined ? value : '';
				}
			);

			if ( param ) {
				return vars[param] ? vars[param] : null;	
			}
			return vars;

		}

	</script>
</body>
</html>
