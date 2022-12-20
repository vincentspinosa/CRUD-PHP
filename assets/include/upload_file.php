<?php

// Pour uploader un fichier
function upload_file() {
    global $fichierCible;
    // On spécifie notre répertoire cible
    $repertoireCible = 'assets/img/';
    // On spécifie le nom du fichier à créer
    $fichierCible = $repertoireCible . basename($_FILES["photo"]["name"]);
    // On regarde si le fichier existe déjà
    if (file_exists($fichierCible)) { 
        return false;
    }
    // On regarde si le fichier n'est pas trop gros
    if ($_FILES["photo"]["size"] > 500000) {
        return false;
    }
    // On regarde si le fichier est d'un type accepté
    $imageFileType = strtolower(pathinfo($fichierCible, PATHINFO_EXTENSION));
    if($imageFileType !== "jpg" && $imageFileType !== "png" && $imageFileType !== "jpeg") {
        return false;
    }
    // On a passé les tests, on upload le fichier
    $upload = move_uploaded_file($_FILES["photo"]["tmp_name"], $fichierCible);
    return ($upload === true) ? true : false;
} 

?>