<?php

session_start();

require_once('app/database.php');
require_once('app/models/user.class.php');
require_once('app/models/pronostics.class.php');

$Database = new Database();
$User = new User();
$Pronostic = new Pronostics();

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

			$isValidSession = true;

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
									<li>
										<a href="vip.php?p=accueil">
											<i class="fa fa-star" aria-hidden="true"></i>
											<span>Pronostics</span>
										</a>
									</li>
									<li class="nav-active">
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
						<h2>Pronostics journaliers</h2>
					</header>

					<section class="panel">
						<div class="panel-body">
							<div class="invoice">
								<header class="clearfix">
									<div class="row">
										<div class="col-sm-6 mt-md">
											<h2 class="h2 mt-none mb-sm text-dark text-bold">Gestion de mes bankrolls</h2>
										</div>
									</div>
								</header>
								<div class="col-12">
									<section class="panel">
										<header class="panel-heading">
											<div class="panel-actions">
												<button type="button" class="btn btn-info" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Nouvelle bankroll"><i class="fa fa-plus"></i> </button>
											</div>
							
											<h2 class="panel-title">Sélectionnez une bankroll</h2>
										</header>
										<div class="panel-body">
											<div class="table-responsive">
												<table class="table mb-none">
													<thead>
														<tr>
															<th>Nom</th>
															<th>Date de création</th>
															<th>Bankroll de départ</th>
															<th>Bankroll actuelle</th>
															<th>Actions</th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td>Bankroll Betclic</td>
															<td>09/04/2019</td>
															<td>100,00€</td>
															<td style="color: red;">-10,00€</td>
															<td class="actions-hover">
																<a href=""><i class="fa fa-arrow-right"></i></a>
																<a href="" class="delete-row"><i class="fa fa-trash-o"></i></a>
															</td>
														</tr>
													</tbody>
												</table>
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

		<!-- Vendor -->
		<script src="core/assets/vendor/jquery/jquery.js"></script>
		<script src="core/assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
		<script src="core/assets/vendor/bootstrap/js/bootstrap.js"></script>
		<script src="core/assets/vendor/nanoscroller/nanoscroller.js"></script>
		<script src="core/assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
		<script src="core/assets/vendor/magnific-popup/magnific-popup.js"></script>
		<script src="core/assets/vendor/jquery-placeholder/jquery.placeholder.js"></script>
		
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

	</body>
</html>