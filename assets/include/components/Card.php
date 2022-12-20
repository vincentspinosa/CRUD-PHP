<?php

class Card {
    // On construit notre objet
    // Chaque paramètre passé au constructeur sera une propriété de l'objet
    function __construct(int $id, string $title, string $image, int $tarif, int $m2, string $city, string $description) {
        $this->ID = $id;
        $this->Title = $title;
        $this->Image = $image;
        $this->Tarif = $tarif;
        $this->M2 = $m2;
        $this->City = $city;
        // On construit le code HTML qui sera affiché
        $this->HTML = "
            <div class=\"col-10 col-md-5 col-lg-4 col-xl-3 mx-3 mb-3\">
                <div class=\"shadow rounded p-3\">
                    <div>
                        <img src=\"$image\" alt=\"Photo de ".$title."\" width=\"100%\">
                    </div>
                    <div class=\"mt-3\">
                        <div>
                            <p>".$title."</p>
                            <hr>
                        </div>
                        <div>
                            <p>Tarif : ".$tarif / 100 ."€/nuit</p>
                            <hr>
                        </div>
                        <div>
                            <p>Surface : ".$m2." m2</p>
                            <hr>
                        </div>
                        <div>
                            <p>Ville : ".$city."</p>
                            <hr>
                        </div>
                        <div>
                            <p>".$description."</p>
                            <hr>
                        </div>
                    </div>
                    <div class=\"d-flex justify-content-around\">
                        <!---<div>
                            <button type=\"button\" class=\"btn btn-primary\" data-bs-toggle=\"modal\" data-bs-target=\"#ModalVoir".$id."\">
                                Voir
                            </button>
                        </div>--->
                        <div>
                            <button type=\"button\" class=\"btn btn-primary\" data-bs-toggle=\"modal\" data-bs-target=\"#ModalModifier".$id."\">
                                Modifier
                            </button>
                        </div>
                        <div>
                            <button type=\"button\" class=\"btn btn-dark\" data-bs-toggle=\"modal\" data-bs-target=\"#ModalSupprimer".$id."\">
                                Supprimer
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!--- MODAL MODIFIER --->

            <div class=\"modal fade\" id=\"ModalModifier".$id."\" tabindex=\"-1\" aria-labelledby=\"ModalModifierLabel\" aria-hidden=\"true\">
                <div class=\"modal-dialog\">
                    <div class=\"modal-content\">
                        <div class=\"modal-header\">
                            <h1 class=\"modal-title fs-5\" id=\"ModalModifierLabel\">Modifier l'annonce ".$title."</h1>
                            <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Close\"></button>
                        </div>
                        <div class=\"modal-body\">
                            <form action=\"index.php\" method=\"POST\" enctype=\"multipart/form-data\" class=\"rounded p-4\">
                                <input type=\"hidden\" name=\"csrf_token\" value=\"".$_SESSION['csrf_token']."\">
                                <input type=\"hidden\" name=\"id\" value=\"".$id."\">
                                <div class=\"mb-3\">
                                    <label for=\"photo\" class=\"textMoyen pb-2\">Nouvelle photo&nbsp;:</label>
                                    <input type=\"file\" name=\"photo\" class=\"form-control bg-transparent\">
                                </div>
                                <div class=\"mb-3\">
                                    <label for=\"titre\" class=\"textMoyen pb-2\">Nouveau titre&nbsp;:</label>
                                    <input type=\"text\" name=\"titre\" class=\"form-control bg-transparent\">
                                </div>
                                <div class=\"mb-3\">
                                    <label for=\"tarif\" class=\"textMoyen pb-2\">Nouveau tarif&nbsp;: (en €)</label>
                                    <input type=\"number\" name=\"tarif\" class=\"form-control bg-transparent\">
                                </div>
                                <div class=\"mb-3\">
                                    <label for=\"m2\" class=\"textMoyen pb-2\">Nouveaux mètres carrés&nbsp;:</label>
                                    <input type=\"number\" name=\"m2\" class=\"form-control bg-transparent\">
                                </div>
                                <div class=\"mb-3\">
                                    <label for=\"ville\" class=\"textMoyen pb-2\">Nouvelle ville&nbsp;:</label>
                                    <input type=\"text\" name=\"ville\" class=\"form-control bg-transparent\">
                                </div>
                                <div class=\"mb-3\">
                                    <label for=\"description\" class=\"textMoyen pb-2\">Nouvelle description&nbsp;:</label>
                                    <textarea name=\"description\" cols=\"30\" rows=\"10\" class=\"form-control bg-transparent\"></textarea>
                                </div>
                                <div class=\"text-center\">
                                    <input type=\"submit\" name=\"submitModifier\" value=\"Modifier\" class=\"btn bg-primary text-white btn-form\">
                                </div>
                            </form>
                        </div>
                        <!---<div class=\"modal-footer\">
                            <button type=\"button\" class=\"btn btn-primary\" data-bs-dismiss=\"modal\">Modifier</button>
                            <button type=\"button\" class=\"btn btn-secondary\">Annuler</button>
                        </div>--->
                    </div>
                </div>
            </div>

            <!--- MODAL SUPPRIMER --->

            <div class=\"modal fade\" id=\"ModalSupprimer".$id."\" tabindex=\"-1\" aria-labelledby=\"ModalSupprimerLabel\" aria-hidden=\"true\">
                <div class=\"modal-dialog\">
                    <div class=\"modal-content\">
                    <div class=\"modal-header\">
                        <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Close\"></button>
                    </div>
                    <div class=\"modal-body\">
                        <p><b>Attention, l'annonce \"".$title."\" va être supprimée.</b></p>
                    </div>
                    <div class=\"modal-footer justify-content-center\">
                        <form action=\"index.php\" method=\"POST\" style=\"width: 100%;\">
                            <div class=\"d-flex flex-wrap justify-content-around align-items-center\">
                                <input type=\"hidden\" name=\"csrf_token\" value=\"".$_SESSION['csrf_token']."\">
                                <input type=\"hidden\" name=\"id\" value=\"".$id."\">
                                <input type=\"submit\" name=\"submitDelete\" value=\"Supprimer\" class=\"btn bg-danger text-white btn-form\">
                                <button type=\"button\" class=\"btn btn-secondary\" data-bs-dismiss=\"modal\" >Annuler</button>
                            </div>
                        <!--<button type=\"button\" class=\"btn btn-danger\" data-bs-dismiss=\"modal\"><a href=\"index.php?delete=".$id."\" class=\"text-white text-decoration-none\">Supprimer</a></button>-->
                        </form>
                    </div>
                    </div>
                </div>
            </div>
        ";
    }

    // Nous permet d'afficher le HTML
    function showHTML() {
        echo $this->HTML;
    }
}

?>