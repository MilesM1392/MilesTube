<?php

class Loginer{

    Public $dba;

    function __construct($BdAcces) {
        $this->dba = $BdAcces->pdo;
    }

    function Login($username, $password) {
        $stmt = $this->dba->prepare("SELECT * FROM utilisateur WHERE nom_utilisateur = ?");
        $stmt->execute([trim($username)]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if($user){
            if(password_verify($password, $user["mdp"])){
                $_SESSION["username"] = $user["nom_utilisateur"];
                return true;
            }else{
            return false;
            }
        }
        return false;
    }

    function Logout() {
        unset($_SESSION["username"]);
    }

    function Regist($email, $username, $password) {

        $hash_pass = password_hash($password, PASSWORD_DEFAULT);

        try {
        $stmt = $this->dba->prepare("INSERT INTO utilisateur (email, nom_utilisateur, mdp) VALUES (?, ?, ?)");
        $stmt->execute([$email, $username, $hash_pass]);
        return true;
        } catch(PDOException $e) {
            //echo $e->getMessage(); -> je veux pas d'erreurs sql en production c'est moche

        }

    }

}
