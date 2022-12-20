<?php
require 'assets/include/init.php'; // On inclut le fichier d'initialisation
include 'assets/include/components/Card.php'; // On importe le composant Card
include 'assets/include/components/Message.php'; // On inclut le composant Message
include 'assets/include/upload_file.php'; // On inclut le fichier d'upload de fichiers

//////////////////////////////////////////////
// Pour supprimer un élément
//////////////////////////////////////////////

if (isset($_POST['submitDelete'])) { // Si le formulaire a été envoyé
    if ($_POST['csrf_token'] === $_SESSION['csrf_token']) { // Si l'input 'token_csrf' est valide
        
        $id = $_POST['id'];
        $queryDelete = "SELECT * FROM annonces WHERE id = $id"; // On prépare notre requête à l'avance
        $queryDelete = $pdo->prepare($queryDelete); // On utilise la méthode 'prepare' de l'objet PDO pour se protéger des injections SQL
        $queryDelete->execute(); // On exécute notre requête
        $data = $queryDelete->fetchAll(PDO::FETCH_ASSOC); // On stocke le résultat de la requête dans un array

        if ($queryDelete->rowCount() === 1) {
            // On supprime la photo
            unlink($data[0]['photo']);

            // On supprime l'annonce
            $queryDelete = "DELETE FROM annonces WHERE id = $id";
            $queryDelete = $pdo->prepare($queryDelete);
            $queryDelete->execute();

            // On vérifie que l'annonce est supprimée
            $queryDelete = "SELECT * FROM annonces WHERE id = $id";
            $queryDelete = $pdo->prepare($queryDelete);
            $queryDelete->execute();

            if ($queryDelete->rowCount() === 0) {
                $annonceDelete = true;
            } else {
                $annonceDelete = false;
            }

        } else {
            $annonceInexistante = true;
        }
    }
}

//////////////////////////////////////////////
//Pour modifier un élément
//////////////////////////////////////////////

if (isset($_POST['submitModifier'])) {
    if ($_POST['csrf_token'] === $_SESSION['csrf_token']) {

        $id = $_POST['id'];
        $queryModif = "SELECT * FROM annonces WHERE id = $id";
        $queryModif = $pdo->prepare($queryModif);
        $queryModif->execute();
        $data = $queryModif->fetchAll()[0]; // On récupère le rang à traiter

        if ($queryModif->rowCount() === 1) {

            $arrayTrue = []; // On crée un array pour stocker les modifications valides
            $arrayFalse = []; // On crée un array pour stocker les modifications erronées

            if (!empty($_FILES['photo']['name'])) { // Si un fichier a été uploadé
                // On essaye d'uploader la photo
                $photo = upload_file();
                if ($photo !== true) { // Si on échoue, on arrête le processus
                    array_push($arrayFalse, 'La photo n\'a pas pû être modifiée.'); // On insère le msg d'erreur dans l'array correspondant

                } else {
                    unlink($data['photo']); // On supprime l'ancienne photo
                    array_push($arrayTrue, 'La photo a été modifiée&nbsp!');
                    // On modifie l'adresse de la photo dans la base de données
                    $query = "UPDATE annonces SET photo = '$fichierCible' WHERE id = $id";
                    $query = $pdo->prepare($query);
                    $query->execute();

                }
            }

            if (!empty($_POST['titre'])) {
                // htmlspecialchars convertit des caractères spéciaux (comme '&' ou '" "') en entités html (comme '&amp;' ou '&quot')
                $titre = htmlspecialchars($_POST['titre'], ENT_QUOTES); // On transforme les caractères spéciaux en code HTML
                if (strlen($titre) <= 100) { // Condition
                    // Si la condition est remplie, on met à jour
                    $query = "UPDATE annonces SET titre = '$titre' WHERE id = $id";
                    $query = $pdo->prepare($query);
                    $query->execute();
                    // On ajoute la data à l'array tenant compte des succès
                    array_push($arrayTrue, 'Le titre a été modifié&nbsp!');
                } else {
                    // Ou à celui tenant compte des erreurs
                    array_push($arrayFalse, 'Le titre doit faire maximum 100 caractères&nbsp!');
                }
            }

            if (!empty($_POST['tarif'])) {
                $tarif = htmlspecialchars($_POST['tarif'], ENT_QUOTES) * 100;
                if ($tarif / 100 > 0 && $tarif / 100 < 100000) {
                    $query = "UPDATE annonces SET tarif = $tarif WHERE id = $id";
                    $query = $pdo->prepare($query);
                    $query->execute();
                    array_push($arrayTrue, 'Le tarif a été modifié&nbsp!');
                } else {
                    array_push($arrayFalse, 'Le tarif doit être compris entre 1 et 99999€&nbsp!');
                }
            }

            if (!empty($_POST['ville'])) {
                $ville = htmlspecialchars($_POST['ville'], ENT_QUOTES);
                if (strlen($ville) <= 100) {
                    $query = "UPDATE annonces SET ville = '$ville' WHERE id = $id";
                    $query = $pdo->prepare($query);
                    $query->execute();
                    array_push($arrayTrue, 'La ville a été modifiée&nbsp!');
                } else {
                    array_push($arrayFalse, 'La ville doit faire maximum 100 caractères&nbsp!');
                }
            }

            if (!empty($_POST['m2'])) {
                $m2 = htmlspecialchars($_POST['m2'], ENT_QUOTES);
                if ($m2 > 0 && $m2 < 100000){
                    $query = "UPDATE annonces SET m2 = '$m2' WHERE id = $id";
                    $query = $pdo->prepare($query);
                    $query->execute();
                    array_push($arrayTrue, 'La surface a été modifiée&nbsp!');
                } else {
                    array_push($arrayFalse, 'La surface doit être comprise entre 0 et 99999m2&nbsp!');
                }
            }

            if (!empty($_POST['description'])) {
                $description = htmlspecialchars($_POST['description'], ENT_QUOTES);
                if (strlen($description) <= 2500) {
                    $query = "UPDATE annonces SET description = '$description' WHERE id = $id";
                    $query = $pdo->prepare($query);
                    $query->execute();
                    array_push($arrayTrue, 'La description a été modifiée&nbsp!');
                } else {
                    array_push($arrayFalse, 'La description doit faire maximum 2500 caractères&nbsp!');
                }
            }

        } else {
            $annonceModifInexistante = true;
        }
    }
}

/////////////////////////////////////////
// On sélectionne les annonces de la BDD
/////////////////////////////////////////

$query = "SELECT * FROM annonces";
// On détermine de quelle manière trier les annonces
if (isset($_GET['submitSort'])) {
    if ($_GET['sortBy'] === 'Date ↓') {
        $query .= " ORDER BY date_enregistrement DESC";
    }
    if ($_GET['sortBy'] === 'Date ↑') {
        $query .= " ORDER BY date_enregistrement ASC";
    }
    if ($_GET['sortBy'] === 'Tarif ↓') {
        $query .= " ORDER BY tarif DESC";
    }
    if ($_GET['sortBy'] === 'Tarif ↑') {
        $query .= " ORDER BY tarif ASC";
    }
    if ($_GET['sortBy'] === 'M2 ↓') {
        $query .= " ORDER BY m2 DESC";
    }
    if ($_GET['sortBy'] === 'M2 ↑') {
        $query .= " ORDER BY m2 ASC";
    }  
} else {
    $query .= " ORDER BY date_enregistrement DESC";
}
$query = $pdo->prepare($query);
$query->execute();

// On récupère les annonces sous forme d'array
$data = $query->fetchAll(PDO::FETCH_ASSOC);
$count = count($data); // On garde en mémoire la taille de l'array

include 'assets/include/components/Head.html'; // On inclut le Head de notre page HTML
?>

    <meta name="description" content="Nous insérerions ici la description de notre page">
    <meta name="keywords" content="Et, ici, les, mots-, clés">
    <title>Accueil - Airbnb</title>
</head>
<body>

<?php
include 'assets/include/components/Header.html'; // On inclut le Header

//Nous affichons les messages à destination de l'utilisateur
if ($annonceDelete === true) {
    new MessageOK(['L\'annonce a bien été supprimée&nbsp!']);
}
if ($annonceDelete === false) {
    new MessageERROR(['Quelque chose s\'est mal passée&nbsp!']);
}
if ($annonceInexistante === true || $annonceModifInexistante === true) {
    new MessageERROR(['Cette annonce n\'existe pas&nbsp!']);
}
if (count($arrayFalse) > 0)
    new MessageERROR($arrayFalse);
if (count($arrayTrue) > 0)
    new MessageOK($arrayTrue);
?>

<div class="container-fluid">

    <div class="row justify-content-center">
        <div class="col-sm-10 col-md-6 col-lg-4 col-xl-3">
            <form method="GET" action="index.php" class="mt-3 mx-3">
                <div class="input-group shadow p-2 rounded justify-content-around">
                    <select name="sortBy" class="ipt-search px-1 mx-2 rounded border my-1 textMoyen" style="max-width: 90%;" role="button">
                        <?php
                        /* Nous stockons toutes les options de tri dans un array, avec en première position la data que l'on passera à la requête,
                            et en seconde le texte affiché dans le navigateur */
                        $array = [['Date ↓', 'Date de publication ↓'], 
                            ['Date ↑', 'Date de publication ↑'], 
                            ['Tarif ↓', 'Tarif ↓'], 
                            ['Tarif ↑', 'Tarif ↑'], 
                            ['M2 ↓', 'Surface ↓'], 
                            ['M2 ↑', 'Surface ↑']];
                        /* Nous créons une option de tri par champ de l'array*/
                        foreach ($array as $arr) {
                            echo "<option value=\"$arr[0]\"";
                            if (isset($_GET['sortBy'])) {
                                if ($_GET['sortBy'] === $arr[0]) {
                                    echo " selected";
                                }
                            }
                            // Valeur par défaut
                            if ($arr[0] === 'Date ↓') {
                                if (!isset($_GET['sortBy'])) {
                                    echo " selected";
                                }
                            }
                            echo ">$arr[1]</option>";
                        }
                        ?>
                    </select>
                    <input class="btn bg-transparent text-primary btn-form border mx-2 rounded my-1" type="submit" name="submitSort" value="Trier"></input>
                </div>
            </form>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col">
            <p class="text-center" style="margin-bottom: 0;">
                <?php 
                // Nous affichons le nombre d'annonces
                if ($count < 2) {
                    echo "$count annonce";
                } else {
                    echo "$count annonces";   
                }
                ?>
            </p>
        </div>
    </div>
            
    <div class="row justify-content-center" style="min-height: 90vh;">
        <div class="d-flex flex-wrap justify-content-around mt-3 mx-2 mb-5">
            <?php
            // Pour chaque annonce, nous affichons un composant Card
            foreach ($data as $dt) {
                $card = new Card($dt['id'], $dt['titre'], $dt['photo'], $dt['tarif'], $dt['m2'], $dt['ville'], $dt['description']);
                $card->showHTML();
            }
            ?>
        </div>
    </div>

</div>

<?php
include 'assets/include/components/Footer.html'; // On inclut le Footer
?>
