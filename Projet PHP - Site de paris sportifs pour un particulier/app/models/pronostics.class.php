<?php 

class Pronostics extends User 
{

	public function ShowUserPronostics($user_id)
	{

		try
		{
			if($this->ExistsUserWithId($user_id))
			{

				try 
				{

					$user_vip_rank = $this->GetUser($user_id, 'subscription_type');
					$exists_pronostics = false;

					if($user_vip_rank > 0)
					{

						switch($user_vip_rank)
						{
							case 1:
							{
								$this->ShowUserNVPronostics($user_id);
								$this->ShowUserStarterPronostics($user_id);
								break;
							}
							case 2:
							{
								$this->ShowUserNVPronostics($user_id);
								$this->ShowUserStarterPronostics($user_id);
								$this->ShowUserFlashPronostics($user_id);
								break;
							}
							case 3:
							{
								$this->ShowUserNVPronostics($user_id);
								$this->ShowUserStarterPronostics($user_id);
								$this->ShowUserFlashPronostics($user_id);
								$this->ShowUserDiamondPronostics($user_id);
								break;
							}
						}

						if(!($this->ExistsNVPronostics()) && !($this->ExistsStarterPronostics()) && !($this->ExistsFlashPronostics()) && !($this->ExistsDiamondPronostics()))
						{
							echo '<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><strong>Oups...</strong> Aucun pronostic n\'est actuellement disponible.</div>';
						}

					}

				}catch(Exception $error)
				{
					die("<center>Erreur lors de la requête SQL:<br>".$error->getMessage()."</center>");
				}
			}
		}
		catch(Exception $error)
		{
			die("<center>Erreur lors de la requête SQL:<br>".$error->getMessage()."</center>");
		}

	}

	public function ShowUserNVPronostics($user_id)
	{
		try
		{
			if($this->ExistsUserWithId($user_id))
			{

				$user_vip_rank = $this->GetUser($user_id, 'subscription_type');

				if($user_vip_rank >= 0)
				{

					if($this->ExistsNVPronostics())
					{

						$subscription_type_for_all = 0;

						$query = $this->db()->prepare("SELECT * FROM `pronostics_categories` WHERE subscription_type = :subscription_type_for_all ORDER BY id DESC");
						$query->execute(array(":subscription_type_for_all" => $subscription_type_for_all));
						while($row = $query->fetch())
						{
							echo '<div class="panel panel-accordion panel-accordion-basic">';
							echo 	'<div class="panel-heading">';
							echo 		'<h4 class="panel-title">';
							echo 			'<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionPrimary" href="#prono-'.$row['id'].'">'.htmlentities(utf8_encode($row['category_name'])).'</a>';
							echo 		'</h4>';
							echo 	'</div>';
							echo 	'<div id="prono-'.$row['id'].'" class="accordion-body collapse">';
							echo 		'<div class="panel-body">';
							if($row['category_note'] != 'NULL')
							{
								echo 		'<p><strong>Note :</strong> '.htmlentities(utf8_encode($row['category_note'])).'</p>';
							}
							echo 			'<div class="col-12">';
							echo 				'<div class="tabs tabs-basic">';
							echo 					'<ul class="nav nav-tabs">';

							$category_id = $row['id'];

							$pub_date = date("d/m/Y");

							$query_2 = $this->db()->prepare("SELECT * FROM `pronostics_classes` WHERE category_id = :category_id AND publication_date = :pub_date ORDER BY id DESC");
							$query_2->execute(array(":category_id" => $category_id, ":pub_date" => $pub_date));
							while($row_2 = $query_2->fetch())
							{

								if($row_2['pronostic_type'] == 1)
								{

									$pronostic_type = 'Simple';

								} else {

									$pronostic_type = 'Combiné';

								}

								echo '<li>';
								echo '<a href="#prono-detail-'.$row_2['id'].'" data-toggle="tab" aria-expanded="true"><i class="fa fa-star"></i> '.htmlentities(utf8_encode($row_2['pronostic_title'])).' ('.$pronostic_type.')</a>';
								echo '</li>';
							}

							echo '</ul>';
							echo '<div class="tab-content">';

							$query_3 = $this->db()->prepare("SELECT * FROM `pronostics_classes` WHERE category_id = :category_id AND publication_date = :pub_date ORDER BY id DESC");
							$query_3->execute(array(":category_id" => $category_id, ":pub_date" => $pub_date));
							while($row_3 = $query_3->fetch())
							{
								$number_pronostics = 0;
								$moy_probability = 0.0;
								$cote_totale = 1.0;
								echo '<div id="prono-detail-'.$row_3['id'].'" class="tab-pane">';
								$query_4 = $this->db()->prepare("SELECT * FROM `pronostics` WHERE class_id = :class_id ORDER BY id DESC");
								$query_4->execute(array(":class_id" => $row_3['id']));
								while($row_4 = $query_4->fetch())
								{
									echo '<p><strong>Sport :</strong> '.htmlentities(utf8_encode($row_4['sport'])).'</p>';
									echo '<p><strong>Rencontre :</strong> '.htmlentities(utf8_encode($row_4['meet'])).'</p>';
									echo '<p><strong>Pronostic :</strong> '.htmlentities(utf8_encode($row_4['pronostic'])).'('.htmlentities(utf8_encode($row_4['cote'])).')</p>';
									echo '<p><strong>Probabilité :</strong> '.htmlentities(utf8_encode($row_4['probability'])).'/10</p>';
									echo '<p><strong>Analyse :<br /></strong> '.htmlentities(utf8_encode($row_4['analysis'])).'</p>';
									$number_pronostics = $number_pronostics + 1;
									$moy_probability = ($moy_probability + $row_4['probability']);
									$cote_totale = ($cote_totale*$row_4['cote']);
									echo '<hr class="separator">';
								}
								echo '<hr class="separator">';
								echo '<center><p><strong>Cote totale</strong> : '.round($cote_totale, 2, PHP_ROUND_HALF_UP).'</p></center>';
								echo '<center><p><strong>Probabilité</strong> : '.round(($moy_probability/$number_pronostics), 2, PHP_ROUND_HALF_UP).'</p></center>';
								echo '</div>';
							}
							echo '</div>';
							echo '</div>';
							echo '</div>';
							echo '</div>';
							echo '</div>';
							echo '</div>';
							$query->closeCursor();
							$query_2->closeCursor();
							$query_3->closeCursor();
							$query_4->closeCursor();
						}

					}

				}
			}
		}
		catch(Exception $error)
		{
			die("<center>Erreur lors de la requête SQL:<br>".$error->getMessage()."</center>");
		}
	}

	public function ShowUserStarterPronostics($user_id)
	{
		try
		{
			if($this->ExistsUserWithId($user_id))
			{

				$user_vip_rank = $this->GetUser($user_id, 'subscription_type');

				if($user_vip_rank >= 1)
				{

					if($this->ExistsStarterPronostics())
					{

						$subscription_type_for_all = 1;

						$query = $this->db()->prepare("SELECT * FROM `pronostics_categories` WHERE subscription_type = :subscription_type_for_all ORDER BY id DESC");
						$query->execute(array(":subscription_type_for_all" => $subscription_type_for_all));
						while($row = $query->fetch())
						{
							echo '<div class="panel panel-accordion panel-accordion-success">';
							echo 	'<div class="panel-heading">';
							echo 		'<h4 class="panel-title">';
							echo 			'<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionPrimary" href="#prono-'.$row['id'].'">'.htmlentities(utf8_encode($row['category_name'])).'</a>';
							echo 		'</h4>';
							echo 	'</div>';
							echo 	'<div id="prono-'.$row['id'].'" class="accordion-body collapse">';
							echo 		'<div class="panel-body">';
							if($row['category_note'] != 'NULL')
							{
								echo 		'<p><strong>Note :</strong> '.htmlentities(utf8_encode($row['category_note'])).'</p>';
							}
							echo 			'<div class="col-12">';
							echo 				'<div class="tabs tabs-success">';
							echo 					'<ul class="nav nav-tabs">';

							$category_id = $row['id'];

							$pub_date = date("d/m/Y");

							$query_2 = $this->db()->prepare("SELECT * FROM `pronostics_classes` WHERE category_id = :category_id AND publication_date = :pub_date ORDER BY id DESC");
							$query_2->execute(array(":category_id" => $category_id, ":pub_date" => $pub_date));
							while($row_2 = $query_2->fetch())
							{

								if($row_2['pronostic_type'] == 1)
								{

									$pronostic_type = 'Simple';

								} else {

									$pronostic_type = 'Combiné';

								}

								echo '<li>';
								echo '<a href="#prono-detail-'.$row_2['id'].'" data-toggle="tab" aria-expanded="true"><i class="fa fa-star"></i> '.htmlentities(utf8_encode($row_2['pronostic_title'])).' ('.$pronostic_type.')</a>';
								echo '</li>';
							}

							echo '</ul>';
							echo '<div class="tab-content">';

							$query_3 = $this->db()->prepare("SELECT * FROM `pronostics_classes` WHERE category_id = :category_id AND publication_date = :pub_date ORDER BY id DESC");
							$query_3->execute(array(":category_id" => $category_id, ":pub_date" => $pub_date));
							while($row_3 = $query_3->fetch())
							{
								$number_pronostics = 0;
								$moy_probability = 0.0;
								$cote_totale = 1.0;
								echo '<div id="prono-detail-'.$row_3['id'].'" class="tab-pane">';
								$query_4 = $this->db()->prepare("SELECT * FROM `pronostics` WHERE class_id = :class_id ORDER BY id DESC");
								$query_4->execute(array(":class_id" => $row_3['id']));
								while($row_4 = $query_4->fetch())
								{
									echo '<p><strong>Sport :</strong> '.htmlentities(utf8_encode($row_4['sport'])).'</p>';
									echo '<p><strong>Rencontre :</strong> '.htmlentities(utf8_encode($row_4['meet'])).'</p>';
									echo '<p><strong>Pronostic :</strong> '.htmlentities(utf8_encode($row_4['pronostic'])).'('.htmlentities(utf8_encode($row_4['cote'])).')</p>';
									echo '<p><strong>Probabilité :</strong> '.htmlentities(utf8_encode($row_4['probability'])).'/10</p>';
									echo '<p><strong>Analyse :<br /></strong> '.htmlentities(utf8_encode($row_4['analysis'])).'</p>';
									$number_pronostics = $number_pronostics + 1;
									$moy_probability = ($moy_probability + $row_4['probability']);
									$cote_totale = ($cote_totale*$row_4['cote']);
									echo '<hr class="separator">';
								}
								echo '<hr class="separator">';
								echo '<center><p><strong>Cote totale</strong> : '.round($cote_totale, 2, PHP_ROUND_HALF_UP).'</p></center>';
								echo '<center><p><strong>Probabilité</strong> : '.round(($moy_probability/$number_pronostics), 2, PHP_ROUND_HALF_UP).'</p></center>';
								echo '</div>';
							}
							echo '</div>';
							echo '</div>';
							echo '</div>';
							echo '</div>';
							echo '</div>';
							echo '</div>';
							$query->closeCursor();
							$query_2->closeCursor();
							$query_3->closeCursor();
							$query_4->closeCursor();
						}

					}

				}
			}
		}
		catch(Exception $error)
		{
			die("<center>Erreur lors de la requête SQL:<br>".$error->getMessage()."</center>");
		}
	}

	public function ShowUserFlashPronostics($user_id)
	{
		try
		{
			if($this->ExistsUserWithId($user_id))
			{

				$user_vip_rank = $this->GetUser($user_id, 'subscription_type');

				if($user_vip_rank >= 2)
				{

					if($this->ExistsFlashPronostics())
					{

						$subscription_type_for_all = 2;

						$query = $this->db()->prepare("SELECT * FROM `pronostics_categories` WHERE subscription_type = :subscription_type_for_all ORDER BY id DESC");
						$query->execute(array(":subscription_type_for_all" => $subscription_type_for_all));
						while($row = $query->fetch())
						{
							echo '<div class="panel panel-accordion panel-accordion-warning">';
							echo 	'<div class="panel-heading">';
							echo 		'<h4 class="panel-title">';
							echo 			'<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionPrimary" href="#prono-'.$row['id'].'">'.htmlentities(utf8_encode($row['category_name'])).'</a>';
							echo 		'</h4>';
							echo 	'</div>';
							echo 	'<div id="prono-'.$row['id'].'" class="accordion-body collapse">';
							echo 		'<div class="panel-body">';
							if($row['category_note'] != 'NULL')
							{
								echo 		'<p><strong>Note :</strong> '.htmlentities(utf8_encode($row['category_note'])).'</p>';
							}
							echo 			'<div class="col-12">';
							echo 				'<div class="tabs tabs-warning">';
							echo 					'<ul class="nav nav-tabs">';

							$category_id = $row['id'];

							$pub_date = date("d/m/Y");

							$query_2 = $this->db()->prepare("SELECT * FROM `pronostics_classes` WHERE category_id = :category_id AND publication_date = :pub_date ORDER BY id DESC");
							$query_2->execute(array(":category_id" => $category_id, ":pub_date" => $pub_date));
							while($row_2 = $query_2->fetch())
							{

								if($row_2['pronostic_type'] == 1)
								{

									$pronostic_type = 'Simple';

								} else {

									$pronostic_type = 'Combiné';

								}

								echo '<li>';
								echo '<a href="#prono-detail-'.$row_2['id'].'" data-toggle="tab" aria-expanded="true"><i class="fa fa-star"></i> '.htmlentities(utf8_encode($row_2['pronostic_title'])).' ('.$pronostic_type.')</a>';
								echo '</li>';
							}

							echo '</ul>';
							echo '<div class="tab-content">';

							$query_3 = $this->db()->prepare("SELECT * FROM `pronostics_classes` WHERE category_id = :category_id AND publication_date = :pub_date ORDER BY id DESC");
							$query_3->execute(array(":category_id" => $category_id, ":pub_date" => $pub_date));
							while($row_3 = $query_3->fetch())
							{
								$number_pronostics = 0;
								$moy_probability = 0.0;
								$cote_totale = 1.0;
								echo '<div id="prono-detail-'.$row_3['id'].'" class="tab-pane">';
								$query_4 = $this->db()->prepare("SELECT * FROM `pronostics` WHERE class_id = :class_id ORDER BY id DESC");
								$query_4->execute(array(":class_id" => $row_3['id']));
								while($row_4 = $query_4->fetch())
								{
									echo '<p><strong>Sport :</strong> '.htmlentities(utf8_encode($row_4['sport'])).'</p>';
									echo '<p><strong>Rencontre :</strong> '.htmlentities(utf8_encode($row_4['meet'])).'</p>';
									echo '<p><strong>Pronostic :</strong> '.htmlentities(utf8_encode($row_4['pronostic'])).'('.htmlentities(utf8_encode($row_4['cote'])).')</p>';
									echo '<p><strong>Probabilité :</strong> '.htmlentities(utf8_encode($row_4['probability'])).'/10</p>';
									echo '<p><strong>Analyse :<br /></strong> '.htmlentities(utf8_encode($row_4['analysis'])).'</p>';
									$number_pronostics = $number_pronostics + 1;
									$moy_probability = ($moy_probability + $row_4['probability']);
									$cote_totale = ($cote_totale*$row_4['cote']);
									echo '<hr class="separator">';
								}
								echo '<hr class="separator">';
								echo '<center><p><strong>Cote totale</strong> : '.round($cote_totale, 2, PHP_ROUND_HALF_UP).'</p></center>';
								echo '<center><p><strong>Probabilité</strong> : '.round(($moy_probability/$number_pronostics), 2, PHP_ROUND_HALF_UP).'</p></center>';
								echo '</div>';
							}
							echo '</div>';
							echo '</div>';
							echo '</div>';
							echo '</div>';
							echo '</div>';
							echo '</div>';
							$query->closeCursor();
							$query_2->closeCursor();
							$query_3->closeCursor();
							$query_4->closeCursor();
						}

					}

				}
			}
		}
		catch(Exception $error)
		{
			die("<center>Erreur lors de la requête SQL:<br>".$error->getMessage()."</center>");
		}
	}

	public function ShowUserDiamondPronostics($user_id)
	{
		try
		{
			if($this->ExistsUserWithId($user_id))
			{

				$user_vip_rank = $this->GetUser($user_id, 'subscription_type');

				if($user_vip_rank >= 3)
				{

					if($this->ExistsDiamondPronostics())
					{

						$subscription_type_for_all = 3;

						$query = $this->db()->prepare("SELECT * FROM `pronostics_categories` WHERE subscription_type = :subscription_type_for_all ORDER BY id DESC");
						$query->execute(array(":subscription_type_for_all" => $subscription_type_for_all));
						while($row = $query->fetch())
						{
							echo '<div class="panel panel-accordion panel-accordion-danger">';
							echo 	'<div class="panel-heading">';
							echo 		'<h4 class="panel-title">';
							echo 			'<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionPrimary" href="#prono-'.$row['id'].'">'.htmlentities(utf8_encode($row['category_name'])).'</a>';
							echo 		'</h4>';
							echo 	'</div>';
							echo 	'<div id="prono-'.$row['id'].'" class="accordion-body collapse">';
							echo 		'<div class="panel-body">';
							if($row['category_note'] != 'NULL')
							{
								echo 		'<p><strong>Note :</strong> '.htmlentities(utf8_encode($row['category_note'])).'</p>';
							}
							echo 			'<div class="col-12">';
							echo 				'<div class="tabs tabs-danger">';
							echo 					'<ul class="nav nav-tabs">';

							$category_id = $row['id'];

							$pub_date = date("d/m/Y");

							$query_2 = $this->db()->prepare("SELECT * FROM `pronostics_classes` WHERE category_id = :category_id AND publication_date = :pub_date ORDER BY id DESC");
							$query_2->execute(array(":category_id" => $category_id, ":pub_date" => $pub_date));
							while($row_2 = $query_2->fetch())
							{

								if($row_2['pronostic_type'] == 1)
								{

									$pronostic_type = 'Simple';

								} else {

									$pronostic_type = 'Combiné';

								}

								echo '<li>';
								echo '<a href="#prono-detail-'.$row_2['id'].'" data-toggle="tab" aria-expanded="true"><i class="fa fa-star"></i> '.htmlentities(utf8_encode($row_2['pronostic_title'])).' ('.$pronostic_type.')</a>';
								echo '</li>';
							}

							echo '</ul>';
							echo '<div class="tab-content">';

							$query_3 = $this->db()->prepare("SELECT * FROM `pronostics_classes` WHERE category_id = :category_id AND publication_date = :pub_date ORDER BY id DESC");
							$query_3->execute(array(":category_id" => $category_id, ":pub_date" => $pub_date));
							while($row_3 = $query_3->fetch())
							{
								$number_pronostics = 0;
								$moy_probability = 0.0;
								$cote_totale = 1.0;
								echo '<div id="prono-detail-'.$row_3['id'].'" class="tab-pane">';
								$query_4 = $this->db()->prepare("SELECT * FROM `pronostics` WHERE class_id = :class_id ORDER BY id DESC");
								$query_4->execute(array(":class_id" => $row_3['id']));
								while($row_4 = $query_4->fetch())
								{
									echo '<p><strong>Sport :</strong> '.htmlentities(utf8_encode($row_4['sport'])).'</p>';
									echo '<p><strong>Rencontre :</strong> '.htmlentities(utf8_encode($row_4['meet'])).'</p>';
									echo '<p><strong>Pronostic :</strong> '.htmlentities(utf8_encode($row_4['pronostic'])).'('.htmlentities(utf8_encode($row_4['cote'])).')</p>';
									echo '<p><strong>Probabilité :</strong> '.htmlentities(utf8_encode($row_4['probability'])).'/10</p>';
									echo '<p><strong>Analyse :<br /></strong> '.htmlentities(utf8_encode($row_4['analysis'])).'</p>';
									$number_pronostics = $number_pronostics + 1;
									$moy_probability = ($moy_probability + $row_4['probability']);
									$cote_totale = ($cote_totale*$row_4['cote']);
									echo '<hr class="separator">';
								}
								echo '<hr class="separator">';
								echo '<center><p><strong>Cote totale</strong> : '.round($cote_totale, 2, PHP_ROUND_HALF_UP).'</p></center>';
								echo '<center><p><strong>Probabilité</strong> : '.round(($moy_probability/$number_pronostics), 2, PHP_ROUND_HALF_UP).'</p></center>';
								echo '</div>';
								$query_4->closeCursor();
							}
							echo '</div>';
							echo '</div>';
							echo '</div>';
							echo '</div>';
							echo '</div>';
							echo '</div>';
							$query->closeCursor();
							$query_2->closeCursor();
							$query_3->closeCursor();
						} 

					}

				}
			}
		}
		catch(Exception $error)
		{
			die("<center>Erreur lors de la requête SQL:<br>".$error->getMessage()."</center>");
		}
	}

	public function ExistsNVPronostics()
	{
		try
		{
			$query = $this->db()->prepare("SELECT * FROM `pronostics_categories` WHERE subscription_type = 0");
			$query->execute();
			$result = $query->rowCount();
			if($result)
			{
				return true;
			}
			else
			{
				return false;
			}
			$query->closeCursor();
		}
		catch(Exception $error)
		{
			die("<center>Erreur lors de la requête SQL:<br>".$error->getMessage()."</center>");
		}
	}

	public function ExistsStarterPronostics()
	{
		try
		{
			$query = $this->db()->prepare("SELECT * FROM `pronostics_categories` WHERE subscription_type = 1");
			$query->execute();
			$result = $query->rowCount();
			if($result)
			{
				return true;
			}
			else
			{
				return false;
			}
			$query->closeCursor();
		}
		catch(Exception $error)
		{
			die("<center>Erreur lors de la requête SQL:<br>".$error->getMessage()."</center>");
		}
	}

	public function ExistsFlashPronostics()
	{
		try
		{
			$query = $this->db()->prepare("SELECT * FROM `pronostics_categories` WHERE subscription_type = 2");
			$query->execute();
			$result = $query->rowCount();
			if($result)
			{
				return true;
			}
			else
			{
				return false;
			}
			$query->closeCursor();
		}
		catch(Exception $error)
		{
			die("<center>Erreur lors de la requête SQL:<br>".$error->getMessage()."</center>");
		}
	}

	public function ExistsDiamondPronostics()
	{
		try
		{
			$query = $this->db()->prepare("SELECT * FROM `pronostics_categories` WHERE subscription_type = 3");
			$query->execute();
			$result = $query->rowCount();
			if($result)
			{
				return true;
			}
			else
			{
				return false;
			}
			$query->closeCursor();
		}
		catch(Exception $error)
		{
			die("<center>Erreur lors de la requête SQL:<br>".$error->getMessage()."</center>");
		}
	}

}

?>