<?php

function upload_file() {
    // On spécifie notre répertoire cible
    global $repertoireCible;
    global $fichierCible;
    $repertoireCible = 'assets/img/';
    var_dump($repertoireCible);
    // On spécifie le nom du fichier à créér
    $fichierCible = $repertoireCible . basename($_FILES["photo"]["name"]);
    var_dump($fichierCible);
    // On regarde si le fichier existe déjà
    if (file_exists($fichierCible)) { 
        global $fichierExiste;
        $fichierExiste = false;
        return $fichierExiste;
    }
    // On regarde si le fichier n'est pas trop gros
    if ($_FILES["photo"]["size"] > 500000) {
        global $fichierTropGros;
        $fichierTropGros = false;
        return $fichierTropGros;
    }
    // On regarde si le fichier est d'un type accepté
    $imageFileType = strtolower(pathinfo($fichierCible, PATHINFO_EXTENSION));
    if($imageFileType !== "jpg" && $imageFileType !== "png" && $imageFileType !== "jpeg") {
        global $mauvaisType;
        $mauvaisType = false;
        return $mauvaisType;
    }
    // On a passé les tests, on upload le fichier
    echo "On tente d'uploader";
    $upload = move_uploaded_file($_FILES["photo"]["tmp_name"], $fichierCible);
    var_dump($upload);
    return ($upload === true) ? true : false;
} 

?>