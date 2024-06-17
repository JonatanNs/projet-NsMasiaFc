<?php

abstract class AbstractManager{
    protected PDO $db;

    public function __construct()
    {
        try {
            $connexion = "mysql:host=".$_ENV["DB_HOST"].";port=3306;charset=".$_ENV["DB_CHARSET"].";dbname=".$_ENV["DB_NAME"];
            $this->db = new PDO(
                $connexion,
                $_ENV["DB_USER"],
                $_ENV["DB_PASSWORD"]
            );
        } catch (PDOException $e) {
            error_log("Database error : " . $e->getMessage());
            throw new Exception("Error connecting to the database : " . $e->getMessage());
        }
    } 
}


