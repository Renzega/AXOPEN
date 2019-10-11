<?php 

class Code_Promo extends Database 
{

	public function GetPromoCode($id, $key)
	{
		try
		{
			if($this->ExistsPromoCodeWithId($id))
			{
				$query = $this->db()->prepare("SELECT ".$key." FROM `codes_promo` WHERE id = :id");
				$query->execute(array(":id" => $id));
				$key = $query->fetchColumn();
				$query->closeCursor();
				return $key;
			}
		}
		catch(Exception $error)
		{
			die("<center>Erreur lors de la requête SQL:<br>".$error->getMessage()."</center>");
		}
	}

	public function ExistsPromoSecretCode($secret_code)
	{
		try
		{
			$query = $this->db()->prepare("SELECT * FROM `codes_promo` WHERE secret_code = :secret_code");
			$query->execute(array(":secret_code" => $secret_code));
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

	public function ExistsPromoCodeWithId($id)
	{
		try
		{
			$query = $this->db()->prepare("SELECT * FROM `codes_promo` WHERE id = :id");
			$query->execute(array(":id" => $id));
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

	public function GetPromoCodeIdFromSecretCode($secret_code)
	{
		try
		{
			$query = $this->db()->prepare("SELECT id FROM `codes_promo` WHERE secret_code = :secret_code");
			$query->execute(array(":secret_code" => $secret_code));
			$key = $query->fetchColumn();
			$query->closeCursor();
			return $key;
		}
		catch(Exception $error)
		{
			die("<center>Erreur lors de la requête SQL:<br>".$error->getMessage()."</center>");
		}
	}

	public function DeletePromoCode($id)
	{
		try
		{
			if($this->ExistsPromoCodeWithId($id))
			{
				$query = $this->db()->prepare("DELETE FROM `codes_promo` WHERE id = :id");
				$query->execute(array(":id" => $id));
				$query->closeCursor();
			}
		}
		catch(Exception $error)
		{
			die("<center>Erreur lors de la requête SQL:<br>".$error->getMessage()."</center>");
		}
	}

}

?>