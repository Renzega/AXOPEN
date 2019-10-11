<?php

if(isset($_GET['app_key']) && isset($_GET['email']) && isset($_GET['password'])) {

	$app_key = htmlentities(trim($_GET['app_key']));
	$email = htmlentities(trim($_GET['email']));
	$password = htmlentities(trim($_GET['password']));

	if($app_key = '192a360f1358b1b7c5c0399fa8683a92') {

		$mysql_connection = array(
									"host" => "db5000097846.hosting-data.io",
									"user" => "dbu96569",
									"password" => "_RjnBe9z4_",
									"database" => "dbs92408"
								);

		try {

			$mysql = new PDO("mysql:host=".$mysql_connection["host"].";dbname=".$mysql_connection["database"], $mysql_connection["user"], $mysql_connection["password"]);
			$mysql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			$query = $mysql->prepare("SELECT * FROM students WHERE student_email = :email LIMIT 1");
			$query->execute(array(":email" => $email));
			$result = $query->fetchColumn();
			if($result > 0) {

				$query2 = $mysql->prepare("SELECT student_password FROM students WHERE student_email = :email LIMIT 1");
				$query2->execute(array(":email" => $email));
				$result2 = $query2->fetchColumn();
				if($result2 == md5($password)) {
                    
                    $query3 = $mysql->prepare("SELECT account_verification FROM students WHERE student_email = :email LIMIT 1");
                    $query3->execute(array(":email" => $email));
                    $result3 = $query3->fetchColumn();
                    if($result3 > 0) {

                    	$arrayJSON = array();
	                    array_push($arrayJSON, "Success");

						$query->closeCursor();
						$query2->closeCursor();

	                    echo json_encode($arrayJSON);

                    } else {

                    	$arrayJSON = array();
	                    array_push($arrayJSON, "No account verification");

	                    $query->closeCursor();
						$query2->closeCursor();

	                    echo json_encode($arrayJSON);

                    }

				} else {

                    $arrayJSON = array();
                    array_push($arrayJSON, "Error");
                    echo json_encode($arrayJSON);

				}

			} else {

                $arrayJSON = array();
                array_push($arrayJSON, "Error");
                echo json_encode($arrayJSON);

			}

		} catch(PDOException $e) {

			die("<center>Database connection error :<br /><strong>".$e->getMessage()."</strong></center>");

		}
	} else {

        $arrayJSON = array();
        array_push($arrayJSON, "Error");
        echo json_encode($arrayJSON);

	}

} else {

	$arrayJSON = array();
	array_push($arrayJSON, "Error");
	echo json_encode($arrayJSON);

}

?>
