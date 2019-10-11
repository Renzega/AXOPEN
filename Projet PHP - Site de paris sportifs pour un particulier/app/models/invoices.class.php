<?php 

class Invoices extends Database {

	public function CreateInvoice($client_email, $invoice_title, $invoice_amount, $payment_id){

		try
		{

			$invoice_date = date("d/m/Y");

			$query = $this->db()->prepare("INSERT INTO `invoices` (client_email, invoice_date, invoice_title, invoice_amount, payment_id) VALUES (:client_email, :invoice_date, :invoice_title, :invoice_amount, :payment_id)");
			$query->execute(array(":client_email" => $client_email, ":invoice_date" => $invoice_date, ":invoice_title" => $invoice_title, ":invoice_amount" => $invoice_amount, ":payment_id" => $payment_id));
			$query->closeCursor();

		}
		catch(Exception $error)
		{
			die("<center>Erreur lors de la requête SQL:<br>".$error->getMessage()."</center>");
		}

	}

}

?>