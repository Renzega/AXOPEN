<?php 

class Essay extends Database {

	public function ShowAsideEssayCategories(){

		try {

				if($this->CountEssayCategories() > 0){

					$query = $this->db()->prepare("SELECT * FROM `essay_categories` ORDER BY name DESC");
					$query->execute();
					while($row = $query->fetch()) {
						echo '<li><a href="#">'.$row['name'].' ('.$this->CountCategoryEssays($row['id']).')</a><i class="line"></i></li>';
					}
					$query->closeCursor();
				} else { 
					echo 'Aucune rubrique n\'est disponible pour le moment.'; 
				}


		} catch (Exception $e) {

			die('<center>Erreur de requête SQL :<br><strong>'.$e->getMessage().'</strong></center>');
		}

	}

	public function ShowMenuEssayCategories(){

		try {

				if($this->CountEssayCategories() > 0){

					echo '<ul class="dropdown-menu" role="menu">';

					$query = $this->db()->prepare("SELECT * FROM `essay_categories` ORDER BY name");
					$query->execute();
					while($row = $query->fetch()) {
						echo '<li><a href="#">'.$row['name'].'</a></li>';
					}
					$query->closeCursor();

					echo '</ul>';
				} 


		} catch (Exception $e) {

			die('<center>Erreur de requête SQL :<br><strong>'.$e->getMessage().'</strong></center>');
		}
		
	}

	public function ShowAsideEssayTags(){

		try {

				if($this->CountEssayTags() > 0){

					$query = $this->db()->prepare("SELECT * FROM `essay_tags` ORDER BY name");
					$query->execute();
					while($row = $query->fetch()) {
						echo '<a href="#">'.utf8_encode($row['name']).'</a>';
					}
					$query->closeCursor();
				} else { 
					echo 'Aucune rubrique n\'est disponible pour le moment.'; 
				}


		} catch (Exception $e) {

			die('<center>Erreur de requête SQL :<br><strong>'.$e->getMessage().'</strong></center>');
		}

	}

	public function CountEssayCategories(){

		try
		{
			$query = $this->db()->prepare("SELECT * FROM `essay_categories`");
			$query->execute();
			$result = $query->rowCount();
			$query->closeCursor();
			return $result;
		}
		catch(Exception $error)
		{
			die("<center>Erreur lors de la requête SQL:<br>".$error->getMessage()."</center>");
		}

	}

	public function CountEssayTags(){

		try
		{
			$query = $this->db()->prepare("SELECT * FROM `essay_tags`");
			$query->execute();
			$result = $query->rowCount();
			$query->closeCursor();
			return $result;
		}
		catch(Exception $error)
		{
			die("<center>Erreur lors de la requête SQL:<br>".$error->getMessage()."</center>");
		}

	}

	public function CountCategoryEssays($category){
		// ...
	}

}

?>