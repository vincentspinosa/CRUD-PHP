<?php
require 'assets/include/init.php'; // On inclut le fichier d'initialisation
include 'assets/include/components/Card.php'; // On importe le composant Card
include 'assets/include/components/Message.php'; // On inclut le composant Message

echo 'GG';

// Pour supprimer un élément
if (isset($_POST['submitDelete'])) {
    if ($_POST['csrf_token'] === $_SESSION['csrf_token']) {
        
        $id = $_POST['id'];
        $queryDelete = "SELECT * FROM annonces WHERE id = $id";
        $queryDelete = $pdo->prepare($queryDelete);
        $queryDelete->execute();
        $data = $queryDelete->fetchAll(PDO::FETCH_ASSOC);

        if ($queryDelete->rowCount() === 1) {
            unlink($data[0]['photo']); // On supprime la photo
            $queryDelete = "DELETE FROM annonces WHERE id = $id";
            $queryDelete = $pdo->prepare($queryDelete);
            $queryDelete->execute();

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

//Pour modifier un élément
if (isset($_POST['submitModifier'])) {
    if ($_POST['csrf_token'] === $_SESSION['csrf_token']) {

        $id = $_POST['id'];
        $queryModif = "SELECT * FROM annonces WHERE id = $id";
        $queryModif = $pdo->prepare($queryModif);
        $queryModif->execute();

        if ($queryModif->rowCount() === 1) {
            if (isset($_POST['titre']) && !empty($_POST['titre'])) {
                // htmlspecialchars dit à l'interpréteur de considérer les caractères HTML spéciaux (comme "") comme des caractères normaux
                $titre = htmlspecialchars($_POST['titre']);
                $query = "UPDATE annonces SET titre = '$titre' WHERE id = $id";
                $query = $pdo->prepare($query);
                $query->execute();
            }
            if (isset($_POST['tarif']) && !empty($_POST['tarif'])) {
                $tarif = $_POST['tarif'];
                $query = "UPDATE annonces SET tarif = $tarif * 100 WHERE id = $id";
                $query = $pdo->prepare($query);
                $query->execute();
            }
            if (isset($_POST['ville']) && !empty($_POST['ville'])) {
                $ville = htmlspecialchars($_POST['ville']);
                $query = "UPDATE annonces SET ville = '$ville' WHERE id = $id";
                $query = $pdo->prepare($query);
                $query->execute();
            }
            if (isset($_POST['m2']) && !empty($_POST['m2'])) {
                $m2 = $_POST['m2'];
                $query = "UPDATE annonces SET m2 = '$m2' WHERE id = $id";
                $query = $pdo->prepare($query);
                $query->execute();
            }
            if (isset($_POST['description']) && !empty($_POST['description'])) {
                $description = htmlspecialchars($_POST['description']);
                $query = "UPDATE annonces SET description = '$description' WHERE id = $id";
                $query = $pdo->prepare($query);
                $query->execute();
            }
            $annonceModif = true;

        } else {
            $annonceModifInexistante = true;
        }
    }
}

/////////////////////////////////////////
// On sélectionne les annonces de la BDD
/////////////////////////////////////////

// On prépare notre requête
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
if ($annonceModif === true) {
    new MessageOK(['Les modifications ont bien été effectuées&nbsp!']);
}
?>

<div class="container-fluid">

    <div class="row justify-content-center">
        <div class="col-sm-10 col-md-6 col-lg-4 col-xl-3">
            <form method="GET" action="index.php" class="mt-3 mx-3">
                <div class="input-group shadow p-2 rounded justify-content-around">
                    <select name="sortBy" class="ipt-search px-1 mx-2 rounded border my-1 textMoyen" style="max-width: 90%;" role="button">
                        <?php
                        /* Nous stockons toutes les options de tri dans un array, avec en première position la data passée à la requête,
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
            
    <div class="row justify-content-center" style=" min-height: 90vh;">
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