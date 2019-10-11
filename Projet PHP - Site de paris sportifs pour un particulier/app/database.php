<?php 

class Database {

    public static function db() {

        $db['conn_inf'] = array('host' => 'localhost',
                        'dbname' => 'rock-money',
                        'user' => 'root',
                        'password' => ''
                        );

        try {

            $database = new PDO('mysql:host='.$db['conn_inf']['host'].';dbname='.$db['conn_inf']['dbname'], $db['conn_inf']['user'], $db['conn_inf']['password']);
            $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $database;

        } catch (Exception $e) {

            die('<center>Erreur de connexion à la base de données :<br><strong>'.$e->getMessage().'</strong></center>');

        }

    }

}

?>