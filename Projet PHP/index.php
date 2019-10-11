<?php 

$pages = array('accueil', 'classements', 'methode_essai', 'contact', 'inscription', 'nouveau_mdp', 'reinitialisation_mdp');

if(isset($_GET['p']) && in_array($_GET['p'], $pages)) {
	require('app/controllers/index/'.$_GET['p'].'.php');
} else {
	header('Location: index.php?p=accueil');
}

?>