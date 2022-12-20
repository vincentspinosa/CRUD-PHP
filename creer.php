<?php
require 'assets/include/init.php'; // On inclut le fichier d'initialisation
include 'assets/include/components/Message.php'; // On inclut le composant Message
include 'assets/include/upload_file.php'; // On inclut le fichier d'upload de fichiers

/////////////////////////////////
// Code pour proster une annonce
/////////////////////////////////

if (isset($_POST['submit'])) { // Si le formulaire a été envoyé
    
    if ($_POST['csrf_token'] === $_SESSION['csrf_token']) { // Si l'input 'token_csrf' est valide

        // Si tous les champs nécessaires sont remplis        
        if (isset($_FILES['photo']) && isset($_POST['titre']) && isset($_POST['tarif']) && isset($_POST['m2']) && isset($_POST['ville'])) {
            $titre = htmlspecialchars($_POST['titre'], ENT_QUOTES); // htmlspecialchars dit à l'interpréteur de lire les caractères HTML spéciaux comme des caractères normaux
            $tarif = htmlspecialchars($_POST['tarif'], ENT_QUOTES) * 100;
            $m2 = htmlspecialchars($_POST['m2'], ENT_QUOTES);
            $ville = htmlspecialchars($_POST['ville'], ENT_QUOTES);
            // écriture ternaire
            // $variable = (condition) ? option 1 : option 2
            $description = (isset($_POST['description'])) ? htmlspecialchars($_POST['description'], ENT_QUOTES) : NULL;

            // On essaye d'uploader la photo
            $photo = upload_file();
            if ($photo !== true) { // Si on échoue, on arrête le processus
                $photoOK = false;
                goto endgoto;
            }

            // On sélectionne le nombre d'annonces pour avoir le total
            $query = "SELECT * FROM annonces"; // On prépare notre requête à l'avance
            $query = $pdo->prepare($query); // On utilise la méthode 'prepare' de l'objet PDO pour se protéger des injections SQL
            $query->execute(); // On exécute la requête
            $resultA = $query->rowCount(); // On récupère le total

            // On insère la nouvelle annonce dans notre Base de données
            $query = "INSERT INTO annonces (titre, tarif, m2, ville, description, photo) VALUES ('$titre', $tarif, $m2, '$ville', '$description', '$fichierCible')";
            $query = $pdo->prepare($query); // On utilise la méthode 'prepare' de l'objet PDO pour se protéger des injections SQL
            $query->execute(); // On exécute la requête

            // On sélectionne le nombre d'annonces pour avoir le total
            $query = "SELECT * FROM annonces";
            $query = $pdo->prepare($query);
            $query->execute();

            // Si le total a augmenté de 1, la requête a été insérée
            if ($resultA === ($query->rowCount() - 1)) {
                $annonceCreee = true;
            }
            else {
                $annonceCreee = false;
            }

        } else {
            $messageTraite = false;
            $messageErreur = []; // On crée un array pour stocker les possibles message d'erreurs
            // Pour chaque erreur, on ajoute le message à l'array
            if (!isset($_FILES['photo'])) {
                $messageErreur += "La photo est manquante.<br>";
            }
            if (!isset($_POST['titre'])) {
                $messageErreur += "Le titre est manquant.<br>";
            }
            if (!isset($_POST['tarif'])) {
                $messageErreur += "Le tarif est manquant.<br>";                
            }
            if (!isset($_POST['m2'])) {
                $messageErreur += "La surface est manquante.<br>";                
            }
            if (!isset($_POST['ville'])) {
                $messageErreur += "La ville est manquante.<br>";                
            }
            if (!isset($_POST['description'])) {
                $messageErreur += "La description est manquante.";                
            }
        }
    }
}
endgoto:

include 'assets/include/components/Head.html'; // On inclut le Head de notre page HTML
?>

    <meta name="description" content="Nous insérerions ici la description de notre page">
    <meta name="keywords" content="Et, ici, les, mots-, clés">
    <title>Créer une annonce - Airbnb</title>
</head>
<body>

<?php
include 'assets/include/components/Header.html'; // On inclut le Header

// On affiche les messages à destination de l'utilisateur
if ($annonceCreee === true) {
    new MessageOK(['Votre annonce a bien été publiée&nbsp!']);
}
if ($annonceCreee === false) {
    new MessageERROR(['Une erreur est survenue&nbsp!']);
}
if ($messageTraite === false) {
    new MessageERROR($messageErreur);
}
if ($photoOK === false) {
    new MessageERROR(['La photo n\'a pas pû être uploadée&nbsp!']);
}
?>

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <h2 class="text-center m-3">Créer une annonce</h2>

                <form action="creer.php" method="POST" enctype="multipart/form-data" class="rounded p-4 m-3 shadow">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?>">
                    <div class="mb-3">
                        <label for="photo" class="textMoyen pb-2">Photo :</label>
                        <input type="file" name="photo" class="form-control bg-transparent">
                    </div>
                    <div class="mb-3">
                        <label for="titre" class="textMoyen pb-2">Titre de l'annonce :</label>
                        <input type="text" name="titre" class="form-control bg-transparent" required>
                    </div>
                    <div class="mb-3">
                        <label for="tarif" class="textMoyen pb-2">Tarif par nuit : (en €)</label>
                        <input type="number" name="tarif" class="form-control bg-transparent" required>
                    </div>
                    <div class="mb-3">
                        <label for="m2" class="textMoyen pb-2">Mètres carrés :</label>
                        <input type="number" name="m2" class="form-control bg-transparent" required>
                    </div>
                    <div class="mb-3">
                        <label for="ville" class="textMoyen pb-2">Ville :</label>
                        <input type="text" name="ville" class="form-control bg-transparent" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="textMoyen pb-2">Description :</label>
                        <textarea name="description" cols="30" rows="10" class="form-control bg-transparent">...</textarea>
                    </div>
                    <div class="text-center">
                        <input type="submit" name="submit" value="Créer" class="btn bg-primary text-white btn-form">
                    </div>
                </form>

            </div>
        </div>
    </div>

<?php
include 'assets/include/components/Footer.html'; // On inclut le Footer
?>