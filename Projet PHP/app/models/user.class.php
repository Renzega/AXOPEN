<?php 

class User extends Database {

	public function CreateUser($username, $email, $secret_question, $secret_question_answer){

		try
		{
			if(!($this->ExistsUserWithUsername(strtolower($username)))){

				if(!($this->ExistsUserWithEmail(strtolower($email)))){

					$user_password = $this->GenerateUserPassword();
					$user_profile_image = 'unknown.png';
					$current_r_date = date("d/m/Y");
					$current_l_date = date("d/m/Y");

					$query = $this->db()->prepare("INSERT INTO `users` (username, email, password, secret_question, secret_question_answer, profile_image, registration_date, old_login_date, donations_value, newpassword_key) VALUES (:username, :email, :user_password, :secret_question, :secret_question_answer, :user_profile_image, :current_r_date, :current_l_date, '0', 'N')");
					$query->execute(array(":username" => $username, ":email" => $email, ":user_password" => sha1($user_password), ":secret_question" => $secret_question, ":secret_question_answer" => $secret_question_answer, ":user_profile_image" => $user_profile_image, ":current_r_date" => $current_r_date, ":current_l_date" => $current_l_date));
					$query->closeCursor();

					$this->AddUserRealPassword($this->GetUserIdFromEmail($email), $user_password);
					$this->SendUserRegistrationEmail($email, $username, $user_password, $secret_question, $secret_question_answer);
				}

			} 
		}
		catch(Exception $error)
		{
			die("<center>Erreur lors de la requête SQL:<br>".$error->getMessage()."</center>");
		}

	}

	public function SendUserRegistrationEmail($user_email, $username, $user_password, $secret_question, $secret_question_answer){

		$email_from = "inscription@la-taverne-aux-essais.fr";
		$email_to = $user_email;
		$email_subject = "Bienvenue sur La Taverne Aux Essais !";
		$email_message = "<center><h1>Bienvenue sur La Taverne Aux Essais !</h1></center><br /><br /><br />
							Bonjour ".$username.",<br /><br />
							Votre mot de passe généré automatiquement est le suivant : <strong>".$user_password."</strong>. Vous pouvez, si vous le souhaitez, le modifier à votre guise en vous rendant sur votre espace personnel La Taverne Aux Essais (fortement conseillé).<br /><br />
							Votre question secrète est : <strong>".$secret_question."</strong>, dont la réponse est <strong>".$secret_question_answer."</strong>.<br /><br />
							En tant que nouveau membre, votre statut attribué est celui de <strong>Visiteur</strong>. Si vous êtes, vous aussi, intéressé par l'écriture d'essais, vous pouvez profiter dès maintenant de nos offres d'abonnement en vous rendant dans la rubrique Abonnement du menu principal.<br /><br />
							Par ailleurs, vous êtes automatiquement inscrit à notre newsletter. Vous pouvez, à tout moment, choisir de désactiver cette option depuis votre espace personnel La Taverne Aux Essais.<br /><br />
							Nous espérons que votre expérience sur La Taverne Aux Essais sera des plus irréprochables et vous invitons, en cas de problème éventuel, à nous contacter depuis notre formulaire de contact accessible depuis le menu principal.<br /><br />
							Nous vous souhaitons la bienvenue parmi nos membres et vous remercions de la confiance que vous nous portez !<br /><br />
							Cordialement, L'équipe <i>La Taverne Aux Essais</i>.<br /><br />
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

	public function AddUserRealPassword($user_id, $user_password){

		try
		{

			if($this->ExistsUserWithId($user_id)){

				$query = $this->db()->prepare("INSERT INTO `users_real_passwords` (id, password) VALUES (:user_id, :user_password)");
				$query->execute(array(":user_id" => $user_id, ":user_password" => $user_password));
				$query->closeCursor();

			}

		}
		catch(Exception $error)
		{
			die("<center>Erreur lors de la requête SQL:<br>".$error->getMessage()."</center>");
		}

	}

	public function DeleteUserRealPassword($user_id){

		try
		{

			if($this->ExistsUserWithId($user_id)){

				$query = $this->db()->prepare("DELETE FROM `users_real_passwords` WHERE id = :user_id");
				$query->execute(array(":user_id" => $user_id));
				$query->closeCursor();

			}

		}
		catch(Exception $error)
		{
			die("<center>Erreur lors de la requête SQL:<br>".$error->getMessage()."</center>");
		}

	}

	public function ExistsUserRealPassword($user_id){

		try
		{
			$query = $this->db()->prepare("SELECT * FROM `users_real_passwords` WHERE id = :user_id");
			$query->execute(array(":user_id" => $user_id));
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

	public function GetUserRealPassword($user_id)
	{
		try
		{
			if($this->ExistsUserWithId($user_id))
			{
				$query = $this->db()->prepare("SELECT password FROM `users_real_passwords` WHERE id = :user_id");
				$query->execute(array(":user_id" => $user_id));
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

	public function GetUsernameFromId($id)
	{
		try
		{
			if($this->ExistsUserWithId($id))
			{
				$query = $this->db()->prepare("SELECT username FROM `users` WHERE id = :id");
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

	public function SendUserNewPasswordEmail($username, $user_email, $newpasswordkey){

		$email_from = "inscription@la-taverne-aux-essais.fr";
		$email_to = $user_email;
		$email_subject = "Réinitialisation de votre mot de passe La Taverne Aux Essais";
		$email_message = "<center><h1>Réinitialisation de votre mot de passe La Taverne Aux Essais</h1></center><br /><br /><br />
							Bonjour ".$username.",<br /><br />
							Vous avez récemment demandé à réinitialiser votre mot de passe. Veuillez cliquer sur ce lien spécifique attribué pour votre requête :<br /><br />
							<center><a href='www.la-taverne-aux-essais.fr/index.php?p=reinitialisation_mdp&email=".$user_email."&npk=".$newpasswordkey."' target='_blank'>Cliquez-ici</a></center><br /><br />
							Ou rendez-vous sur ce lien : www.la-taverne-aux-essais.fr/index.php?p=reinitialisation_mdp&email=".$user_email."&npk=".$newpasswordkey." !<br /><br />
							Cordialement, L'équipe <i>La Taverne Aux Essais</i>.<br /><br />
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

	public function SendUserNewPasswordEmailReq($username, $user_email, $new_password){

		$email_from = "inscription@la-taverne-aux-essais.fr";
		$email_to = $user_email;
		$email_subject = "Confirmation de réinitialisation de votre mot de passe La Taverne Aux Essais";
		$email_message = "<center><h1>Confirmation de réinitialisation de votre mot de passe La Taverne Aux Essais</h1></center><br /><br /><br />
							Bonjour ".$username.",<br /><br />
							Conformément à votre demande, nous vous confirmons le changement de votre mot de passe.<br /><br />
							Votre nouveau mot de passe vous permettant de vous connecter à votre espace personnel est le suivant : <strong>".$new_password."</strong><br /><br />
							Nous espérons faire de votre expérience sur La Taverne Aux Essais un souvenir inoubliable.<br /><br />
							Cordialement, L'équipe <i>La Taverne Aux Essais</i>.<br /><br />
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

	private function GenerateUserAccountKey()
	{
		$characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$registrationKey = array();
		$charactersLength = strlen($characters) - 1;
		for($i = 0; $i < 9; $i++)
		{
			$character = rand(0, $charactersLength);
			$registrationKey[] = $characters[$character];
		}
		return implode($registrationKey);	
	}

}

?>