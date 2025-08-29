<?php
// Importation de fichiers poue êttre utilisés
require_once "Character.php";
require_once "Guerrier.php";
require_once "Orc.php";

// Démarrage de la session pour manipuler les données de la session en question
session_start();
// session_unset();
// session_destroy();

// Fonction pour savoir qui va gagner la partie
function quiVaGagner()
{
    if ($_SESSION['guerrier']->getPointsDeVie() <= 0 || $_SESSION['orc']->getPointsDeVie() <= 0) {
        echo "Le Combat Légendaire est terminé";
        if ($_SESSION['guerrier']->getPointsDeVie() <= 0) {
            echo "L'Orc a gagné ! :)";
        } elseif ($_SESSION['orc']->getPointsDeVie() <= 0) {
            echo "Le Guerrier a gagné ! :)";
        } else {
            echo "Egalité ! :)";
        }
    }
}

// Fonction pour lancer le Dé pour savoir qui va commencer la partie
function lancerLeDe()
{
    $nbAleatoireGuerrier = mt_rand(1, 6);
    $nbAleatoireOrc = mt_rand(1, 6);

    if ($nbAleatoireGuerrier > $nbAleatoireOrc) {

        $_SESSION['commencer'] = 'guerrier';
    } elseif ($nbAleatoireGuerrier < $nbAleatoireOrc) {

        $_SESSION['commencer'] = 'orc';
    } elseif ($nbAleatoireGuerrier == $nbAleatoireOrc) {

        while ($nbAleatoireGuerrier == $nbAleatoireOrc) {

            $nbAleatoireGuerrier = mt_rand(1, 6);
            $nbAleatoireOrc = mt_rand(1, 6);

            if ($nbAleatoireGuerrier > $nbAleatoireOrc) {

                $_SESSION['commencer'] = 'guerrier';
            } elseif ($nbAleatoireGuerrier < $nbAleatoireOrc) {
                $_SESSION['commencer'] = 'orc';
            }
        }
    }
}

// On regarde si la méthode est bien POST pour envoyer des données au serveur
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = [];

    if (isset($_POST["guerrier"])) {

        if (!isset($_SESSION["guerrier"])) {

            $errors['pasGuerrier'] = "Il y a pas de Guerrier dans le jeu, il sera crée maintenant";
            $_SESSION["guerrier"] = new Guerrier(2000, 500, "Ultima", 250, "Bouclier Ultime", 200, "assets/img/Chibi_Guerrier_1.png");
        } else {
            $errors['ouiGuerrier'] = "Le Guerrier existe déjà ! :) Pas besoin de le recréer ! :)";
        }
    } elseif (isset($_POST["orc"])) {

        if (!isset($_SESSION["orc"])) {

            $errors['pasOrc'] = "Il y a pas d'Orc dans le jeu, il sera crée maintenant";
            $_SESSION["orc"] = new Orc(1500, 200, 100, 400, "assets/img/Chibi_Orc_1.png");
        } else {
            $errors['ouiOrc'] = "L'orc existe déjà ! :) Pas besoin de le recréer ! :)";
        }
    } elseif (isset($_POST["commencer"])) {

        if (!isset($_SESSION["orc"]) || !isset($_SESSION["guerrier"])) {

            $errors['peutPasCommencer'] = "La partie peut pas commencer sans le Guerrier et l'Orc !";
        } elseif (isset($_SESSION['guerrier']) && isset($_SESSION['orc'])) {

            lancerLeDe();
        }
    }

    // Algo de combat

    if (isset($_POST['combat'])) {
        if (!isset($_SESSION['guerrier']) || !isset($_SESSION['orc']) || !isset($_SESSION['commencer'])) {
            $errors['pasCommencerCombat'] = 'Le combat peut pas commencer sans savoir qui commence, ou s\'il manque quelqu\'un !';
        } else {
            // Si les points de vie de l'Orc ou du Guerrier sont plus grands que 0, on continue le combat
            if ($_SESSION['guerrier']->getPointsDeVie() > 0 || $_SESSION['orc']->getPointsDeVie() > 0) {

                // On regarde c'est qui qui commence
                if ($_SESSION['commencer'] == "guerrier") {
                    // Le Guerrier va attaquer l'Orc
                    echo "Le Guerrier attaque avec une frappe de " . $_SESSION['guerrier']->attack() . " !";
                    $_SESSION['orc']->setPointsDeVie($_SESSION['orc']->getPointsDeVie() - $_SESSION['guerrier']->attack());
                    echo "L'Orc a perdu " . $_SESSION['guerrier']->attack() . " points de vie ! :) " . "Il lui reste " . $_SESSION['orc']->getPointsDeVie() . " points de vie ! :)";
                    $_SESSION['commencer'] = "orc";
                    quiVaGagner();
                } else {
                    // L'Orc va attaquer le Guerrier
                    $attackAleatoire = $_SESSION['orc']->attack();
                    echo "L'Orc attaque avec une frappe de " . $attackAleatoire . " !";
                    $degats = $_SESSION['guerrier']->getDamage($attackAleatoire);
                    echo "Le Guerrier a perdu " . $degats . " points de vie ! :) " . "Il lui reste " . $_SESSION['guerrier']->getPointsDeVie() . " points de vie ! :)";
                    $_SESSION['commencer'] = "guerrier";
                    quiVaGagner();
                }
            }
        }
    }

    var_dump($errors);
}
var_dump($_POST);
var_dump($_SESSION);

// $_SESSION["guerrier"]->getDamage(800);
// var_dump($_SESSION["guerrier"]);


?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Combat de Fantasy !!!!</title>

    <!-- Lien vers Bootstrap -->
    <link rel="stylesheet" href="../../node_modules/bootstrap/dist/css/bootstrap.min.css" />

    <!-- Lien vers les icônes Bootstrap -->
    <link rel="stylesheet" href="../../node_modules/bootstrap-icons/font/bootstrap-icons.min.css">

    <!-- Lien vers le fichier pour designer le site web -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="
<?php if (isset($_POST['modeJourNuit'])) { ?>
    <?php if ($_POST['modeJourNuit'] == 'Mode Nuit') { ?>
         mode-nuit
    <?php } else { ?>
        mode-jour
    <?php } ?>
<?php } ?>mode-jour">
    <div class="d-flex align-items-center">
        <h1 class="mt-5 ms-5
        <?php if (isset($_POST['modeJourNuit'])) { ?>
            <?php if ($_POST['modeJourNuit'] == 'Mode Nuit') { ?>
                titre-nuit
            <?php } else { ?>
                titre-jour
            <?php } ?>
        <?php } ?>titre-jour">Combat Légendaire !!!!</h1>
        <div class="w-100 d-flex justify-content-end align-items-center">
            <form action="" method="POST">
                <input class="btn mx-5 text-white
                <?php if (isset($_POST['modeJourNuit'])) { ?>
                    <?php if ($_POST['modeJourNuit'] == 'Mode Nuit') { ?>
                        btns-nuit
                    <?php } else { ?>
                        btns-jour
                    <?php } ?>
                <?php } ?>btns-jour" type="submit" name="modeJourNuit" id="modeJourNuit"
                    <?php if (isset($_POST['modeJourNuit'])) { ?>
                    <?php if ($_POST['modeJourNuit'] == 'Mode Nuit') { ?>
                    value='Mode Jour' ;
                    <?php } else { ?>
                    value='Mode Nuit' ;
                    <?php } ?>
                    <?php } ?>value="Mode Nuit">
            </form>
        </div>
    </div>
    <div class="div-img-fond-combat">
        <?php if (isset($_SESSION['guerrier'])) { ?>
            <div class="d-block fs-3 overlay">
                <p class="text-center">Guerrier</p>
                <div class="d-flex justify-content-center mt-5">
                    <img src="assets/img/Chibi_Guerrier_6.png" alt="assets/img/Chibi_Guerrier_6.png">
                </div>
                <div class="d-flex justify-content-center mt-5">
                    <div class="perso-informations mt-5">
                        <div class="d-flex justify-content-center align-items-center px-5 ps-5">
                            <img class="text-white" src="assets/img/heart_3.png" alt="assets/img/heart_3.png">
                            <p class="ms-3 mx-3 mt-3"><?= $_SESSION['guerrier']->getPointsDeVie() ?></p>
                        </div>
                        <div class="d-flex justify-content-center align-items-center px-5 ps-5">
                            <img class="text-white" src="assets/img/magic_2.png" alt="assets/img/magic_2.png">
                            <p class="ms-3 mx-3 mt-3"><?= $_SESSION['guerrier']->getPointsDeMana() ?></p>
                        </div>
                        <div class="d-flex justify-content-center align-items-center px-5 ps-5">
                            <img class="text-white" src="assets/img/shield_2.png" alt="assets/img/shield_2.png">
                            <p class="ms-3 mx-3 mt-3"><?= $_SESSION['guerrier']->getDefenceBouclier() ?></p>
                        </div>
                        <div class="d-flex justify-content-center align-items-center px-5 ps-5">
                            <img class="text-white" src="assets/img/sword_2.png" alt="assets/img/sword_2.png">
                            <p class="ms-3 mx-3 mt-3"><?= $_SESSION['guerrier']->getDegatsArme() ?></p>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php if (isset($_SESSION['orc'])) { ?>
            <div class="d-flex justify-content-center fs-1 overlay">
                <div class="d-block fs-3 overlay">
                    <p class="text-center">Orc</p>
                    <div class="d-flex justify-content-center mt-5">
                        <img src="assets/img/Chibi_Orc_2.png" alt="assets/img/Chibi_Orc_2.png">
                    </div>
                    <div class="d-flex justify-content-center mt-5">
                        <div class="perso-informations mt-5">
                            <div class="d-flex justify-content-center align-items-center px-5 ps-5">
                                <img class="text-white" src="assets/img/heart_3.png" alt="assets/img/heart_3.png">
                                <p class="ms-3 mx-3 mt-3"><?= $_SESSION['orc']->getPointsDeVie() ?></p>
                            </div>
                            <div class="d-flex justify-content-center align-items-center px-5 ps-5">
                                <img class="text-white" src="assets/img/magic_2.png" alt="assets/img/magic_2.png">
                                <p class="ms-3 mx-3 mt-3"><?= $_SESSION['orc']->getPointsDeMana() ?></p>
                            </div>
                            <div class="d-flex justify-content-center align-items-center px-5 ps-5">
                                <img class="text-white" src="assets/img/shield_2.png" alt="assets/img/shield_2.png">
                                <p class="ms-3 mx-3 mt-3">0</p>
                            </div>
                            <div class="d-flex justify-content-center align-items-center px-5 ps-5">
                                <img class="text-white" src="assets/img/sword_2.png" alt="assets/img/sword_2.png">
                                <p class="ms-3 mx-3 mt-3"><?= $_SESSION['orc']->getDamageMin() ?> / <?= $_SESSION['orc']->getDamageMax() ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
    <div class="d-flex justify-content-center mt-2 fs-1">
        <?= isset($errors['pasGuerrier']) ? $errors['pasGuerrier'] : '' ?>
        <?= isset($errors['ouiGuerrier']) ? $errors['ouiGuerrier'] : '' ?>
        <?= isset($errors['pasOrc']) ? $errors['pasOrc'] : '' ?>
        <?= isset($errors['ouiOrc']) ? $errors['ouiOrc'] : '' ?>
        <?= isset($errors['peutPasCommencer']) ? $errors['peutPasCommencer'] : '' ?>
        <?= isset($errors['pasCommencerCombat']) ? $errors['pasCommencerCombat'] : '' ?>
    </div>
    <form action="" method="POST">
        <div class="d-flex justify-content-center">
            <?php if (isset($_POST['modeJourNuit'])) { ?>
                <?php if ($_POST['modeJourNuit'] == 'Mode Nuit') { ?>
                    <input class="btn btns-nuit mx-3 ms-3 mt-5 text-white" type="submit" name="guerrier" id="guerrier" value="Créer Guerrier">
                    <input class="btn btns-nuit mx-3 ms-3 mt-5 text-white" type="submit" name="orc" id="orc" value="Créer Orc">
                    <input class="btn btns-nuit mx-3 ms-3 mt-5 text-white" type="submit" name="commencer" id="commencer" value="Qui commence ?">
                    <input class="btn btns-nuit mx-3 ms-3 mt-5 text-white" type="submit" name="combat" id="combat" value="Combat !">
                <?php } else { ?>
                    <input class="btn btns-jour mx-3 ms-3 mt-5 text-white" type="submit" name="guerrier" id="guerrier" value="Créer Guerrier">
                    <input class="btn btns-jour mx-3 ms-3 mt-5 text-white" type="submit" name="orc" id="orc" value="Créer Orc">
                    <input class="btn btns-jour mx-3 ms-3 mt-5 text-white" type="submit" name="commencer" id="commencer" value="Qui commence ?">
                    <input class="btn btns-jour mx-3 ms-3 mt-5 text-white" type="submit" name="combat" id="combat" value="Combat !">
                <?php } ?>
            <?php } else { ?>
                <input class="btn btns-jour mx-3 ms-3 mt-5 text-white" type="submit" name="guerrier" id="guerrier" value="Créer Guerrier">
                <input class="btn btns-jour mx-3 ms-3 mt-5 text-white" type="submit" name="orc" id="orc" value="Créer Orc">
                <input class="btn btns-jour mx-3 ms-3 mt-5 text-white" type="submit" name="commencer" id="commencer" value="Qui commence ?">
                <input class="btn btns-jour mx-3 ms-3 mt-5 text-white" type="submit" name="combat" id="combat" value="Combat !">
            <?php } ?>
        </div>
    </form>
</body>

</html>