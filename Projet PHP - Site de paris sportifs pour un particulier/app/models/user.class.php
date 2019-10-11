<?php 

class User extends Database {

	public function CreateUser($email, $password, $phone_number){

		try
		{
			if(!($this->ExistsUserWithEmail(strtolower($email)))){

				$registration_date = date("d/m/Y");

				$query = $this->db()->prepare("INSERT INTO `users` (email, password, phone_number, member_rank, subscription_type, subscription_date_end, registration_date, newpassword_key) VALUES (:email, :password, :phone_number, '0', '0', 'N', :registration_date, 'NULL')");
				$query->execute(array(":email" => $email, ":password" => sha1($password), ":phone_number" => $phone_number, ":registration_date" => $registration_date));
				$query->closeCursor();

				$this->SendUserRegistrationEmail($email, $password, $phone_number);
			}
		}
		catch(Exception $error)
		{
			die("<center>Erreur lors de la requête SQL:<br>".$error->getMessage()."</center>");
		}

	}

	public function SendUserRegistrationEmail($email, $password, $phone_number){

		$email_from = "inscription@rock-money.fr";
		$email_to = $email;
		$email_subject = "Bienvenue sur Rock Money !";
		$email_message = "<center><h1>Bienvenue sur Rock Money !</h1></center><br /><br /><br />
							Bonjour ".$email.",<br /><br />
						Nous vous remercions de vous être inscrit sur Rock Money.<br /><br />
						Vous pouvez dès maintenant vous connecter à l'aide de vos identifiants et choisir l'une de nos offres d'abonnement.<br /><br />
						N'hésitez pas à nous suivre sur Snapchat, Twitter et Instagram pour rester informé !<br /><br />
						Nous espérons que votre expérience Rock Money sera irréprochable.<br /><br >
						Cordialement, L'équipe Rock Money<br /><br />
						<u>Ce message est automatique. Merci de ne pas y répondre.</u>";

		$email_headers   = "";
		$email_headers .= "MIME-Version: 1.0\r\n";
		$email_headers .= "Content-type: text/html; charset=UTF-8\r\n";
		$email_headers .= "From: ".$email_from." <".$email_from.">\r\n";
		$email_headers .= "Reply-To: <".$email_from.">\r\n";
		$email_headers .= "Subject: {".$email_subject."}\r\n";
		$email_headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
		mail($email_to, $email_subject, $email_message, $email_headers);

	}

	public function GetUser($id, $key)
	{
		try
		{
			if($this->ExistsUserWithId($id))
			{
				$query = $this->db()->prepare("SELECT ".$key." FROM `users` WHERE id = :id");
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

	public function SetUser($id, $key, $newvalue){

		try
		{
			if($this->ExistsUserWithId($id))
			{
				$query = $this->db()->prepare("UPDATE `users` SET ".$key." = :newvalue WHERE id = :id");
				$query->execute(array(":newvalue" => $newvalue, ":id" => $id));
				$query->closeCursor();
			}
		}
		catch(Exception $error)
		{
			die("<center>Erreur lors de la requête SQL:<br>".$error->getMessage()."</center>");
		}

	}

	public function ExistsUserWithUsername($username)
	{

		try
		{
			$query = $this->db()->prepare("SELECT * FROM `users` WHERE username = :username");
			$query->execute(array(":username" => $username));
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

	public function ExistsUserWithPhoneNumber($phone_number)
	{

		try
		{
			$query = $this->db()->prepare("SELECT * FROM `users` WHERE phone_number = :phone_number");
			$query->execute(array(":phone_number" => $phone_number));
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

	public function ExistsUserWithEmail($email)
	{

		try
		{
			$query = $this->db()->prepare("SELECT * FROM `users` WHERE email = :email");
			$query->execute(array(":email" => $email));
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

	public function ExistsUserWithId($id)
	{
		try
		{
			$query = $this->db()->prepare("SELECT * FROM `users` WHERE id = :id");
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

	public function GetUserIdFromEmail($email)
	{
		try
		{
			$query = $this->db()->prepare("SELECT id FROM `users` WHERE email = :email");
			$query->execute(array(":email" => $email));
			$key = $query->fetchColumn();
			$query->closeCursor();
			return $key;
		}
		catch(Exception $error)
		{
			die("<center>Erreur lors de la requête SQL:<br>".$error->getMessage()."</center>");
		}	
	}

	public function GenerateUserPasswordKey(){

		$characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$registrationKey = array();
		$charactersLength = strlen($characters) - 1;
		for($i = 0; $i < 11; $i++)
		{
			$character = rand(0, $charactersLength);
			$registrationKey[] = $characters[$character];
		}
		$newpasswordkey = implode($registrationKey);

		if($this->ExistsUsersNewPasswordKey($newpasswordkey)){

			$this->GenerateUserNewPasswordKey();

		} else {

			return $newpasswordkey;

		}

	}

	public function ExistsUsersNewPasswordKey($newpasswordkey){

		try
		{
			$query = $this->db()->prepare("SELECT * FROM `users` WHERE newpassword_key = :newpasswordkey");
			$query->execute(array(":newpasswordkey" => $newpasswordkey));
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

	public function SendUserNewPasswordEmail($email, $newpasswordkey){

		$email_from = "inscription@rock-money.fr";
		$email_to = $email;
		$email_subject = "Réinitialisation de votre mot de passe Rock Money";
		$email_message = "<center><h1>Réinitialisation de votre mot de passe Rock Money</h1></center><br /><br /><br />
							Bonjour,<br /><br />
							Vous avez récemment demandé à réinitialiser votre mot de passe. Veuillez cliquer sur ce lien spécifique attribué pour votre requête :<br /><br />
							<center><a href='www.rock-money.fr/vip.php?p=reinitialisation_mdp&email=".$email."&npk=".$newpasswordkey."' target='_blank'>Cliquez-ici</a></center><br /><br />
							Ou rendez-vous sur ce lien : www.rock-money.fr/vip.php?p=reinitialisation_mdp&email=".$email."&npk=".$newpasswordkey." !<br /><br />
							Cordialement, L'équipe <i>Rock Money</i>.<br /><br />
							<u>Ce message est automatique. Merci de ne pas y répondre.</u>";

		$email_headers = array(
		    'From' => $email_from,
		    'Reply-To' => $email_from,
		    'X-Mailer' => 'PHP/' . phpversion(),
		    'MIME-Version' => '1.0',
		    'Content-type' => 'text/html; charset=UTF-8'
		);

		$email_headers   = "";
		$email_headers .= "MIME-Version: 1.0\r\n";
		$email_headers .= "Content-type: text/html; charset=UTF-8\r\n";
		$email_headers .= "From: ".$email_from." <".$email_from.">\r\n";
		$email_headers .= "Reply-To: <".$email_from.">\r\n";
		$email_headers .= "Subject: {".$email_subject."}\r\n";
		$email_headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
		mail($email_to, $email_subject, $email_message, $email_headers);

	}

	public function SendUserNewPasswordEmailReq($email, $new_password){

		$email_from = "inscription@rock-money.fr";
		$email_to = $email;
		$email_subject = "Confirmation de réinitialisation de votre mot de passe Rock Money";
		$email_message = "<center><h1>Confirmation de réinitialisation de votre mot de passe Rock Money</h1></center><br /><br /><br />
							Bonjour,<br /><br />
							Conformément à votre demande, nous vous confirmons le changement de votre mot de passe.<br /><br />
							Votre nouveau mot de passe vous permettant de vous connecter à votre espace personnel est le suivant : <strong>".$new_password."</strong><br /><br />
							Nous espérons faire de votre expérience sur La Taverne Aux Essais un souvenir inoubliable.<br /><br />
							Cordialement, L'équipe <i>Rock Money</i>.<br /><br />
							<u>Ce message est automatique. Merci de ne pas y répondre.</u>";

		$email_headers = array(
		    'From' => $email_from,
		    'Reply-To' => $email_from,
		    'X-Mailer' => 'PHP/' . phpversion(),
		    'MIME-Version' => '1.0',
		    'Content-type' => 'text/html; charset=UTF-8'
		);

		$email_headers   = "";
		$email_headers .= "MIME-Version: 1.0\r\n";
		$email_headers .= "Content-type: text/html; charset=UTF-8\r\n";
		$email_headers .= "From: ".$email_from." <".$email_from.">\r\n";
		$email_headers .= "Reply-To: <".$email_from.">\r\n";
		$email_headers .= "Subject: {".$email_subject."}\r\n";
		$email_headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
		mail($email_to, $email_subject, $email_message, $email_headers);

	}

	private function GenerateUserPassword()
	{
		$characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$registrationKey = array();
		$charactersLength = strlen($characters) - 1;
		for($i = 0; $i < 10; $i++)
		{
			$character = rand(0, $charactersLength);
			$registrationKey[] = $characters[$character];
		}
		return implode($registrationKey);	
	}

	public function GenerateUserAccountKey()
	{
		$characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$registrationKey = array();
		$charactersLength = strlen($characters) - 1;
		for($i = 0; $i <= 20; $i++)
		{
			$character = rand(0, $charactersLength);
			$registrationKey[] = $characters[$character];
		}
		return implode($registrationKey);	
	}

	public function GetMemberRank($member_rank)
	{
		$rank = "";
		switch($member_rank)
		{
			case 0:
			{
				$rank = "Membre";
				break;
			}
			case 1:
			{
				$rank = "Administrateur";
				break;
			}
			default:
			{
				$rank = "Membre";
				break;
			}
		}

		return $rank;

	}

	public function GetMemberName($user_email)
	{
		$member_name = strstr($user_email, '@', true);

		return $member_name;
	}

}

?>