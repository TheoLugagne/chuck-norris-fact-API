<?php
    $co = null;

    function getInstance(): PDO | null
    {
        if (!isset($co)) {
            $host = "localhost";
            $db_name = "chuckn_facts";
            $username = "client_chuck_api";
            $password = "0Jln*zBt@BG])!hO";
            try {
                $co = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
                $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                //echo "<script>console.log('Connexion à la base de donnée réussie');</script>";
            } catch (Exception $e) {
                echo "Erreur de connexion : ". $e->getMessage();
            }
        }
        return $co;
    }
