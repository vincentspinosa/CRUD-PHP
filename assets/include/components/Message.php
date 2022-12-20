<?php

class MessageOK {
    function __construct(array $texte) { // On construit notre objet
        echo "<div>";
        foreach ($texte as $t) { // Pour chaque message, on affiche un paragraphe
            echo "<p class=\"text-left text-success mt-3 mx-3 mb-0\">".$t."</p>";
        }
        echo "</div>";
    }
}

class MessageERROR {
    function __construct(array $texte) {
        echo "<div>";
        foreach ($texte as $t) {
            echo "<p class=\"text-left text-danger mt-3 mx-3 mb-0\">".$t."</p>";
        }
        echo "</div>";
    }
}

?>