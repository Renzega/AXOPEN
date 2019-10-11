<?php 

session_start();

require_once('app/database.php');
require_once('app/models/user.class.php');
require_once('app/models/notification.class.php');
require_once('app/models/essay.class.php');
$Database = new Database();
$User = new User();
$Notification = new Notification();
$Essay = new Essay();

if(isset($_SESSION['id'])){
	if(!($User->ExistsUserWithId($_SESSION['id']))){
		session_unset();
		session_destroy();
	} else {
		if($_SESSION['email'] == $User->GetUser($_SESSION['id'], "email")){
			if(!($_SESSION['password'] == $User->GetUser($_SESSION['id'], "password"))){
				session_unset();
				session_destroy();
			} 
		} else {
			session_unset();
			session_destroy();
		}
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

	<aside class="navmenu">
		<div class="post-titles">
			<div class="tag-title">
				<div class="container">
					<p class="tags" id="post-titles">
						<a data-filter=".pt-notifications" href="#"><i class="fa fa-bell"></i> Notifications</a>
					</p>
				</div>
			</div>
			<button type="button" class="remove-navbar"><i class="fa fa-times"></i></button>
			<ul id="menu-notifications" class="post-title-list clearfix">
				<?php

				if(isset($_SESSION['id'])){
					$Notification->ShowNotifs($_SESSION['id']);
				}

				?>
			</ul>
		</div>
	</aside>

	<div class="canvas">
		<div class="canvas-overlay"></div>
		<header>
			<nav class="navbar navbar-fixed-top nav-down navbar-laread">
				<div class="container">
					<div class="navbar-header">
						<a class="navbar-brand" href="medium-image-v1-2.html"><img height="64" src="core/assets/img/ltae_logo_small.png" alt=""></a>
					</div>
					<?php

					if(isset($_SESSION['id'])){
						if($Notification->ExistsNotifsNotRead($_SESSION['id'])){
							echo '<div class="get-post-titles"><button id="read-notifs" type="button" class="navbar-toggle push-navbar" data-navbar-type="default"><span id="read-bars" style="color: #F5DA81;"><i class="fa fa-bell"></i></span></button></div>';
						} else {
							echo '<div class="get-post-titles"><button id="read-notifs" type="button" class="navbar-toggle push-navbar" data-navbar-type="default"><i class="fa fa-bell"></i></button></div>';
						}
					}

					?>
					
					<?php

					if(!(isset($_SESSION['id']))){
						echo '<a href="#" data-toggle="modal" data-target="#login-form" class="modal-form"><i class="fa fa-user"></i></a>';
					} else {
						echo '<div class="get-post-titles"><a href="index.php?p=profil&profil_id='.htmlentities($User->GetUser($_SESSION['id'], "id")).'"><i class="fa fa-user"></i></a></div>';
					}

					?>
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

		<div class="container">
			<div class="row">
				<div class="col-md-8">

					<div class="post-fluid post-medium-vertical">

						<div class="container-fluid post-default">
							<div class="container-medium">
								<div class="row post-items">
									<div class="post-item-banner">
										<img src="core/assets/img/img-16.png" alt="" />
									</div>
									<div class="col-md-12">
										<div class="post-item">
											<div class="post-item-paragraph">
												<div>
													<a href="#" class="quick-read qr-only-phone"><i class="fa fa-eye"></i></a>
													<a href="#" class="mute-text"><span class="label-grey">SOCIOLOGIE</span></a>
												</div>
												<h3><a href="#">Introduction à la théorie du marché social</a></h3>
												<p>Éléments introductifs de la théorie <a href="#" class="more">[...]</a></p>
												<h6>TAGS : <span class="label-golden">Actualités</span> <span class="label-golden">Débat</span></h6>
											</div>
											<div class="post-item-info clearfix">
												<div class="pull-left">
													<span>Vendredi 11 janvier 2019</span>   •   Par <a href="#">Samuel Clauzon</a>   •   <span class="stars"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i></span>
												</div>
												<div class="pull-right post-item-social">
													<a href="#" class="quick-read qr-not-phone"><i class="fa fa-eye"></i></a>
													<a href="#" tabindex="0" role="button" data-toggle="popover" data-trigger="focus" data-placement="top" data-content="<a href='#'><i class='fa fa-facebook'></i></a><a href='#'><i class='fa fa-twitter'></i></a>" class="pis-share"><i class="fa fa-share-alt"></i></a>
													<a href="#" class="post-like"><i class="fa fa-heart"></i><span>28</span></a>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<a href="gallery-v3.html" class="more-down"><i class="fa fa-long-arrow-down"></i></a><br />
					</div>
				</div>

				<aside class="col-md-4">

					<div class="laread-right">

						<form class="laread-form search-form">
							<div class="input"><input type="text" class="form-control" placeholder="Rechercher un essai"></div>
							<button type="submit" class="btn btn-link"><i class="fa fa-search"></i></button>
						</form>

						<ul class="laread-list">
							<li class="title">RUBRIQUES</li>
							<?php 

							$Essay->ShowAsideEssayCategories();

							?>
						</ul>

						<ul class="laread-list">
							<li class="title">DERNIERS ESSAIS</li>
							<li><a href="#">The Nature of My Inspiration</a><i class="date">28 June</i></li>
							<li><a href="#">Sam Feldt - Show Me Love</a><i class="date">27 June</i></li>
							<li><a href="#">Do You Love Coffee?</a><i class="date">25 June</i></li>
							<li><a href="#">The Game Before The Game</a><i class="date">23 June</i></li>
							<li><a href="#">Long Live The Kings</a><i class="date">22 June</i></li>
						</ul>

						<ul class="laread-list">
							<li class="title">ESSAIS LES PLUS POPULAIRES</li>
							<li><a href="#">The Nature of My Inspiration</a><i class="date">28 June</i></li>
							<li><a href="#">Sam Feldt - Show Me Love</a><i class="date">27 June</i></li>
							<li><a href="#">Do You Love Coffee?</a><i class="date">25 June</i></li>
							<li><a href="#">The Game Before The Game</a><i class="date">23 June</i></li>
							<li><a href="#">Long Live The Kings</a><i class="date">22 June</i></li>
						</ul>

						<ul class="laread-list">
							<li class="title">TAGS</li>
							
								<?php

								if($Essay->CountEssayTags() > 0){
									echo '<li class="bar-tags">';
									$Essay->ShowAsideEssayTags();
									echo '</li>';
								} else {
									echo 'Aucun tag n\'est disponible pour le moment.';
								}

								?>
						</ul>

						<ul class="laread-list barbg-grey">
							<li class="title">NEWSLETTER</li>
							<li class="newsletter-bar">
								<p>Abonnez-vous à notre newsletter et restez informés de nos activités !</p>
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
									<input type="text" class="form-control" placeholder="john@doe.com">
									<span class="input-group-btn">
										<button class="btn" type="button"><i class="fa fa-check"></i></button>
									</span>
								</div>
							</li>
						</ul>

						<div class="laread-list quotes-basic">
							<i class="fa fa-quote-left"></i> CITATION DU JOUR <i class="fa fa-quote-right"></i>
							<p>“La vérité n'est ni docile, ni obéissante; elle est un maître solennel, sévère et souvent insaisissable.”</p>
							<span class="whosay">- Donald Walker </span>
						</div>

						<ul class="laread-list">
							<li class="title">DONS</li>
							<li>
								<div class="latabs-box without-outline">
										<ul class="latabs clearfix" role="tablist">
											<li role="presentation" class="active">
												<a href="#about-2" id="about-2-tab" role="tab" data-toggle="tab" aria-controls="about-2" aria-expanded="true">Statistiques</a>
											</li>
											<li role="presentation">
												<a href="#features-2" role="tab" id="features-2-tab" data-toggle="tab" aria-controls="features-2">Faire un don</a>
											</li>
										</ul>
										<div class="tab-content">
											<div role="tabpanel" class="tab-pane fade in active" id="about-2" aria-labelledby="about-2-tab">
												<table class="table table-striped">
													<thead>
														<tr>
														    <th>Place</th>
														    <th>Nom</th>
														    <th>Consultations</th>
														</tr>
													</thead>
													<tbody>
														<tr>
														    <th scope="row"><strong>1</strong></th>
														    <td>Économie</td>
														    <td>456</td>
														</tr>
														<tr>
														    <th scope="row"><strong>2</strong></th>
														    <td>Sociologie</td>
														    <td>351</td>
														</tr>
														<tr>
														    <th scope="row"><strong>3</strong></th>
														    <td>Droit</td>
														    <td>212</td>
														</tr>
														<tr>
														    <th scope="row">4</th>
														    <td>Art</td>
														    <td>24</td>
														</tr>
														<tr>
														    <th scope="row">5</th>
														    <td>Musique</td>
														    <td>13</td>
														</tr>
													</tbody>
												</table>
												<i class="fa fa-star"></i> <strong>Meilleur(e) donateur/trice :</strong> Samuel C. avec 100,00€<br />
												<i class="fa fa-star"></i> <strong>Dernier/ère donateur/trice :</strong> John D. avec 10,00€<br />
											</div>
											<div role="tabpanel" class="tab-pane fade" id="features-2" aria-labelledby="features-2-tab">
												<div class="laalert alert-warning alert-dismissible fade in" role="alert">
													<strong>Oups !</strong> Veuillez vous connecter pour procéder à une donation.
												</div>
											</div>
										</div>
								</div>
							</li>
						</ul>

						<ul class="laread-list social-bar">
							<li class="title">SUIVEZ-NOUS</li>
							<li class="social-icons">
								<a href="#"><i class="fa fa-facebook"></i></a>
								<a href="#"><i class="fa fa-twitter"></i></a>
								<a href="#"><i class="fa fa-google-plus"></i></a>
								<a href="#"><i class="fa fa-dribbble"></i></a>
							</li>
						</ul>

					</div>

				</aside>
			</div>
		</div>

		<footer class="container-fluid footer">
			<div class="container text-center">
				<div class="footer-logo"><img src="core/assets/img/ltae_logo_small.png" alt=""></div>
				<p class="laread-motto">N'ayez plus peur d'exprimer vos opinions en toute créativité !</p>
				<div class="laread-social">
					<a href="#" class="fa fa-twitter"></a>
					<a href="#" class="fa fa-facebook"></a>
					<a href="#" class="fa fa-pinterest"></a>
				</div>
			</div>
		</footer>
	</div>

	<div id="quick-read" class="qr-white-theme">
		<div class="quick-read-head">
			<div class="container">
				<a href="#" class="qr-logo"></a>
				<div class="qr-tops">
					<a href="#" class="qr-search-close"><i class="fa fa-times"></i></a>
					<a href="#" class="qr-search"><i class="fa fa-search"></i></a>
					<a href="#" class="qr-change"><i class="fa fa-adjust"></i></a>
					<a href="#" class="qr-close"><i class="fa fa-times"></i></a>
				</div>
				<form class="qr-search-form">
					<input type="text" placeholder="Search LaRead">
				</form>
			</div>
		</div>
		<div class="quick-dialog">
			<div class="quick-body">
				<div class="container">
					<div class="col-md-8 col-md-offset-2">
						<div class="qr-content post-item-paragraph">

							<article>
								<h2>A Nice Street Cafe in London</h2>

								<p>Consectetur adipiscing elit. Vivamus nec mauris pulvinar leo dignissim sollicitudin eleifend eget velit. Nunc sed dolor enim, vitae sodales diam. Mauris fermentum fringilla lorem, in rutrum massa sodales et. Praesent mollis sodales est, eget fringilla libero sagittis eget. Nunc gravida varius risus ac luctus. Mauris ornare eros sed libero euismod ornare. Nulla id sem a mauris egestas pulvinar vitae non dui. Cras odio tortor, feugiat nec sagittis sed, laoreet ut mauris. In hac habitasse platea dictumst.</p>

								<p>What if instead your website used machine learning to build itself, and then rebuilt as necessary, based on data it was gathering about how it was being used? That's what The Grid is aiming to do. After you add content such as pictures, text, the stuff everyone enjoys interacting with your obligation to design...</p>

								<h4>The Truth about Teens and Privacy</h4>

								<p>Social media has introduced a new dimension to the well-worn fights over private space and personal expression. Teens do not want their parents to view their online profiles or look over their shoulder when they’re chatting with friends. Parents are no longer simply worried about what their children wear out of the house but what they photograph themselves wearing in their bedroom to post online. Interactions that were previously invisible to adults suddenly have traces, prompting parents to fret over.</p>

								<h4>Here are some of the ways you may be already being hacked:</h4>

								<ul class="in-list">
									<li>Everyone makes mistakes</li>
									<li>You can control only your behavior</li>
									<li>Good habits create discipline</li>
									<li>Remember the <u>big picture</u></li>
									<li>Everyone learns differently</li>
									<li>Focus on the Benefits, Not the Difficulties</li>
									<li>Traditions are bonding opportunities</li>
								</ul>

								<p>This is not a comprehensive list. Rather, it is a snapshot in time of real-life events that are happening right now. In the future, we will likely read this list and laugh at all the things I failed to envision.</p>
								<p class="with-img">
									<a href="core/assets/img/banner-85-1.jpg" data-fluidbox-qr><img src="core/assets/img/banner-85.jpg" alt=""></a>
									<span class="img-caption">Walk through the Forest</span>
								</p>
								<p>Elit try-hard consectetur, dolore voluptate minim distillery. Bespoke Cosby sweater pug street art et keytar. Nihil fish whatever trust fund, dreamcatcher in fingerstache squid seitan accusamus. Organic Wes Anderson High Life setruhe authentic iPhone, aute art party hashtag fixie church-key art veniam Tumblr polaroid. DIY polaroid vinyl, sustainable hella scenester accusamus fanny pack. Ut Neutra enim pariatur cornhole actually Banksy, tote bag fugiat ad accusamus. Incididunt fixie normcore fingerstache. Freegan proident literally brunch before they sold out.
								</p>

								<p>Readymade fugiat narwhal, typewriter VHS aute stumptown hoodie irure put a bird on it. Fashion axe raw denim brunch put a bird on it voluptate Truffaut. Bitters PBR&amp;B nulla Odd Future swag leggings. Banh mi Wes Anderson butcher letterpress skateboard quis. Chambray hella retro viral Cosby sweater photo booth. Schlitz elit Cosby sweater, Blue Bottle non chambray chia. Single-origin coffee pickled.</p>

								<h5>Blockquote</h5>

								<p>Do officia aliqua, pop-up ut et occupy sriracha. YOLO meggings PBR sartorial mollit, Schlitz assumenda vero kitsch plaid post-ironic PBR&amp;B keffiyeh. Cosby sweater wolf YOLO Austin bespoke, American Apparel crucifix paleo flexitarian. Aliquip bitters food truck, incididunt tofu accusamus magna nesciunt typewriter drinking vinegar Shoreditch try-hard you probably haven’t heard of them labore. </p>

								<blockquote>
									<p><i>“The Muppets Take Manhattan”</i><br />
									This movie was a disappointment. The Muppets do not take Manhattan at all. They merely visit it.<br />
									<span>— No stars.</span></p>
								</blockquote>

								<p>Do officia aliqua, pop-up ut et occupy sriracha. YOLO meggings PBR sartorial mollit, Schlitz assumenda vero kitsch plaid post-ironic PBR&amp;B keffiyeh. Cosby sweater wolf YOLO Austin bespoke, American Apparel crucifix paleo flexitarian. Aliquip bitters food truck, incididunt tofu accusamus.</p>
							</article>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="quick-read-bottom">
			<p class="qr-info">By <a href="#">Daniele Zedda</a>   •   18 February</p>
			<div class="qr-nav">
				<a href="#" class="qr-prev">← PREV POST</a>
				<a href="#" class="qr-share" tabindex="0" role="button" data-toggle="popover" data-placement="top" data-trigger="focus" data-content="<a href='#'><i class='fa fa-facebook'></i></a><a href='#'><i class='fa fa-twitter'></i></a>"><i class="fa fa-share-alt"></i></a>
				<a href="#" class="qr-comment"><i class="fa fa-comment"></i></a>
				<a href="#" class="qr-like"><i class="fa fa-heart"></i> 34</a>
				<a href="#" class="qr-next">NEXT POST →</a>
			</div>
		</div>
		<div class="quick-read-bottom qr-bottom-2 hide">
			<div class="qr-nav">
				<a href="#" class="qr-prev">← PREV POST</a>
				<p class="qr-info">By <a href="#">Daniele Zedda</a>   •   18 February</p>
				<a href="#" class="qr-next">NEXT POST →</a>
				<a href="#" class="qr-like"><i class="fa fa-heart"></i> 34</a>
				<div class="qr-sharebox">
					<span>Share on</span>
					<a href='#'><i class='fa fa-facebook'></i></a>
					<a href='#'><i class='fa fa-twitter'></i></a>
				</div>
			</div>
		</div>
	</div>

	<!-- Login Modal -->
	<div class="modal leread-modal fade" id="login-form" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content" id="login-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title"><i class="fa fa-unlock-alt"></i>Connexion à votre compte</h4>
				</div>
				<div class="modal-body">
					<form>
						<div class="form-group">
							<input id="login-email" name="login-email" type="email" class="form-control" placeholder="Adresse email">
						</div>
						<div class="form-group">
							<input id="login-password" name="login-password" type="password" class="form-control" placeholder="Mot de passe">
						</div>
						<div class="linkbox">
							<a href="index.php?p=nouveau_mdp">Mot de passe oublié ?</a>
							<div id="login-error"><span class="form-warning">Aucune erreur</span></div>
						</div>
						<div class="linkbox">
							<label><span><a href="index.php?p=inscription" id="register-btn">Pas encore inscrit ?</a></span></label>
							<button id="login-btn" type="button" class="btn btn-golden btn-signin">Connexion</button>
						</div>
					</form>
				</div>
			</div>
		</div>
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

			var error = document.getElementById('login-error');

			var error_activated = false;

			error.style.display = "none";

			$('#login-btn').click(function(e){

				e.preventDefault();

				if($('#login-email').val() != ''){

					error_activated = false;
					error.style.display = "none";

					if($('#login-password').val() != ''){

						error_activated = false;
						error.style.display = "none";

						$.post(

							'app/processing/index/login.php',
							{
								email : $('#login-email').val(),
								password : $('#login-password').val(),
							},
							function(data){
								if(data == 'Success'){
									document.location.href = "index.php?p=accueil";
								} else {
									error_activated = true;
									error.innerHTML = "<span class='form-warning'><i class='fa fa-exclamation'></i> Nom d'utilisateur ou mot de passe incorrect !</span>";
									error.style.display = "block";	
								}
							}

						);

					} else {

						error_activated = true;
						error.innerHTML = "<span class='form-warning'><i class='fa fa-exclamation'></i> Veuillez saisir un mot de passe !</span>";
						error.style.display = "block";	
					}
				} else {

					error_activated = true;
					error.innerHTML = "<span class='form-warning'><i class='fa fa-exclamation'></i> Veuillez saisir une adresse email !</span>";
					error.style.display = "block";
				}
			});

			$('#read-notifs').click(function(e){

				$.post(

					'app/processing/index/read_notifs.php',
					function(data){
						if(data == 'Success'){
							document.getElementById('read-bars').style.color = "#FFFFFF";
						}
					}

				);

			});

			$(document).on("click", ".delete-notification", function(e){

				var notif_id = this.id;

				$.ajax({

					type: 'POST',
					url: 'app/processing/index/delete-notif.php',
					data:
					{
						id : notif_id,
					},
					success: function(data){
						$('#notification-'+notif_id).remove();
						resetNotifs();
					},
					error: function(data){
						document.location.href = "index.php?p=accueil";
					},

				});

			});

			function resetNotifs(){

				$.ajax({
					type: 'GET',
					url: 'app/processing/index/reset-notifs.php',
					success: function(data){
						$('.post-title-list').html(data);
					},
				});	

			}

		});

	</script>
</body>
</html>
