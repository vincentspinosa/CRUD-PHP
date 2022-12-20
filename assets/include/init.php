<?php

session_start(); // On démarre la session utilisateur si elle n'est pas créée

// On tente de se connecter à notre base de données
try {
    $pdo = new PDO('mysql:host=localhost;dbname=test_technique_evogue;charset=utf8', 'root', 'root');
}
// Si on échoue, on renvoie une erreur
catch(Exception $e) {
    die('Erreur : '.$e->getMessage());
}

// On crée un Token CSRF qui nous servira à nous protéger des Cross-Site Request Forgery
if(!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

?>