<?php 

class Paypal_Checkout extends Database
{

	public function CreatePaypalCheckout($payment_id, $payment_token, $payment_payer_id)
	{

		try
		{

			$query = $this->db()->prepare("INSERT INTO `paypal_checkouts` (payment_id, token, payer_id) VALUES (:payment_id, :payment_token, :payment_payer_id)");
			$query->execute(array(":payment_id" => $payment_id, ":payment_token" => $payment_token, ":payment_payer_id" => $payment_payer_id));
			$query->closeCursor();

		}
		catch(Exception $error)
		{
			die("<center>Erreur lors de la requête SQL:<br>".$error->getMessage()."</center>");
		}

	}

	public function GetPaypalCheckout($id, $key)
	{
		try
		{
			if($this->ExistsPaypalCheckout($id))
			{
				$query = $this->db()->prepare("SELECT ".$key." FROM `paypal_checkouts` WHERE id = :id");
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

	public function ExistsPaypalCheckout($payment_id)
	{
		try
		{
			$query = $this->db()->prepare("SELECT * FROM `paypal_checkouts` WHERE payment_id = :payment_id");
			$query->execute(array(":payment_id" => $payment_id));
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

	public function GetPaypalCheckoutIdFromPaymentId($payment_id)
	{
		try
		{
			$query = $this->db()->prepare("SELECT id FROM `paypal_checkouts` WHERE payment_id = :payment_id");
			$query->execute(array(":payment_id" => $payment_id));
			$key = $query->fetchColumn();
			$query->closeCursor();
			return $key;
		}
		catch(Exception $error)
		{
			die("<center>Erreur lors de la requête SQL:<br>".$error->getMessage()."</center>");
		}	
	}

}

?>