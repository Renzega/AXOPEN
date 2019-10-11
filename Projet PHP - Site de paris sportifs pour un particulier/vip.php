<?php 

$pages = array('contact', 'connexion', 'inscription', 'nouveau_mdp', 'reinitialisation_mdp', 'accueil', 'abonnement', 'abonner_1', 'abonner_2', 'abonner_3', 'gestion_bankrolls');

if(isset($_GET['p']) && in_array($_GET['p'], $pages)) {
	require('app/controllers/vip/'.$_GET['p'].'.php');
} else {
	header('Location: index.php?p=accueil');
}

?>