<?php

class Notification extends User {

	public function DeleteNotif($notif_id, $session_id){

		try
		{
			if($this->ExistsNotifWithId($notif_id))
			{
				if($this->GetNotif($notif_id, "user_get") == $session_id){

				$query = $this->db()->prepare("DELETE FROM `users_notifications` WHERE id = :notif_id");
				$query->bindParam(':notif_id', $notif_id);
				$query->execute();
				if(!($query->rowCount())){
					return false;
				}
				$query->closeCursor();

				}
			}
		}
		catch(Exception $error)
		{
			die("<center>Erreur lors de la requête SQL:<br>".$error->getMessage()."</center>");
		}

	}

	public function ShowNotifs($session_id){

		try {

			$query = $this->db()->prepare("SELECT * FROM `users_notifications` WHERE user_get = :session_id ORDER BY send_date DESC");
			$query->execute(array(':session_id' => $session_id));
			while($row = $query->fetch()) {
				switch($this->GetNotif($row['id'], "type")){
					case 0:{
						if($this->ExistsUserWithId($this->GetNotif($row['id'], "user_send"))){
							echo '<li id="notification-'.$this->GetNotif($row['id'], "id").'" class="pt-notifications notif-'.$this->GetNotif($row['id'], "id").'"><div><h5><i class="fa fa-heart"></i><a href="#">'.$this->GetUsernameFromId($this->GetNotif($row['id'], $this->GetNotif($row['id'], "user_send"))).' a aimé votre essai.</a></h5><div class="post-subinfo"><span>Le '.$this->GetNotif($row['id'], "send_date").'   •   <a href="#" id="'.$this->GetNotif($row['id'], "id").'" class="delete-notification"><i class="fa fa-archive text-warning"></i></a></span></div></div></li>';
						} else {
							echo '<li id="notification-'.$this->GetNotif($row['id'], "id").'" class="pt-notifications"><div><h5><i class="fa fa-heart"></i><a href="#">Vous avez un nouveau J\'aime sur votre essai.</a></h5><div class="post-subinfo"><span>Le '.$this->GetNotif($row['id'], "send_date").'   •   <a href="#" id="'.$this->GetNotif($row['id'], "id").'" class="delete-notification"><i class="fa fa-archive text-warning"></i></a></span></div></div></li>';
						}

						break;
					}
				}
			}
			$query->closeCursor();

		} catch (Exception $e) {

			die('<center>Erreur de requête SQL :<br><strong>'.$e->getMessage().'</strong></center>');

		}

	}

	public function ReadAllNotifs($session_id){

		try
		{
			$query = $this->db()->prepare("UPDATE `users_notifications` SET `read` = 1 WHERE user_get = :session_id");
			$query->bindValue(":session_id", $session_id);
			$query->execute();
			$query->closeCursor();
		}
		catch(Exception $error)
		{
			die("<center>Erreur lors de la requête SQL:<br>".$error->getMessage()."</center>");
		}

	}

	public function ExistsNotifsNotRead($session_id){
		try
		{
			$query = $this->db()->prepare("SELECT * FROM `users_notifications` WHERE user_get = :session_id AND `read` < 1");
			$query->execute(array(":session_id" => $session_id));
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

	public function GetNotif($id, $key){

		try
		{
			if($this->ExistsNotifWithId($id))
			{
				$query = $this->db()->prepare("SELECT ".$key." FROM `users_notifications` WHERE id = :id");
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

	public function ExistsNotifWithId($id){
		try
		{
			$query = $this->db()->prepare("SELECT * FROM `users_notifications` WHERE id = :id");
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

}

?>