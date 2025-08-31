<?php
// Importation de fichiers poue êttre utilisés
require_once "Character.php";
require_once "Guerrier.php";
require_once "Orc.php";

// Démarrage de la session pour manipuler les données de la session en question
session_start();
// session_unset();
// session_destroy();

$quiVaGagner = '';
$quiCommence = '';
$resumeTour = '';
// Fonction pour savoir qui va gagner la partie
function quiVaGagner()
{
    if ($_SESSION['guerrier']->getPointsDeVie() <= 0 || $_SESSION['orc']->getPointsDeVie() <= 0) {
        if ($_SESSION['guerrier']->getPointsDeVie() <= 0) {
            $quiVaGagner = " L'Orc a gagné ! :)";
        } elseif ($_SESSION['orc']->getPointsDeVie() <= 0) {
            $quiVaGagner = " Le Guerrier a gagné ! :)";
        } else {
            $quiVaGagner = " Egalité ! :)";
        }

        return " Le Combat Légendaire est terminé ! :) " . $quiVaGagner;
    }
}

// Fonction pour lancer le Dé pour savoir qui va commencer la partie
function lancerLeDe()
{
    $nbAleatoireGuerrier = mt_rand(1, 6);
    $nbAleatoireOrc = mt_rand(1, 6);

    if ($nbAleatoireGuerrier > $nbAleatoireOrc) {

        $_SESSION['commencer'] = 'guerrier';
        $quiCommence = "Le Guerrier va commencer !";
        return $quiCommence;
    } elseif ($nbAleatoireGuerrier < $nbAleatoireOrc) {

        $_SESSION['commencer'] = 'orc';
        $quiCommence = "L'Orc va commencer !";
        return $quiCommence;
    } elseif ($nbAleatoireGuerrier == $nbAleatoireOrc) {

        while ($nbAleatoireGuerrier == $nbAleatoireOrc) {

            $nbAleatoireGuerrier = mt_rand(1, 6);
            $nbAleatoireOrc = mt_rand(1, 6);

            if ($nbAleatoireGuerrier > $nbAleatoireOrc) {

                $_SESSION['commencer'] = 'guerrier';
                $quiCommence = "Le Guerrier va commencer !";
                return $quiCommence;
            } elseif ($nbAleatoireGuerrier < $nbAleatoireOrc) {
                $_SESSION['commencer'] = 'orc';
                $quiCommence = "L'Orc va commencer !";
                return $quiCommence;
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
            $_SESSION["guerrier"] = new Guerrier(2000, 500, "Ultima", 250, "Bouclier Ultime", 200, "assets/img/Chibi_Guerrier_6.png");
            $_SESSION["pointsDeVieTotalGuerrier"] = $_SESSION["guerrier"]->getPointsDeVie();
        } else {
            $errors['ouiGuerrier'] = "Le Guerrier existe déjà ! :) Pas besoin de le recréer ! :)";
        }
    } elseif (isset($_POST["orc"])) {

        if (!isset($_SESSION["orc"])) {

            $errors['pasOrc'] = "Il y a pas d'Orc dans le jeu, il sera crée maintenant";
            $_SESSION["orc"] = new Orc(1500, 200, 100, 400, "assets/img/Chibi_Orc_2.png");
            $_SESSION["pointsDeVieTotalOrc"] = $_SESSION["orc"]->getPointsDeVie();
        } else {
            $errors['ouiOrc'] = "L'orc existe déjà ! :) Pas besoin de le recréer ! :)";
        }
    } elseif (isset($_POST["commencer"])) {

        if (isset($_SESSION['commencer'])) {

            $errors["commencer"] = "On sais déjà qui commence ! C'est " . $_SESSION['commencer'] . " qui commence !";
        } elseif (!isset($_SESSION["orc"]) || !isset($_SESSION["guerrier"])) {

            $errors['peutPasCommencer'] = "La partie peut pas commencer sans le Guerrier et l'Orc !";
        } elseif (isset($_SESSION['guerrier']) && isset($_SESSION['orc'])) {

            $quiCommence = lancerLeDe();
        }
    } elseif (isset($_POST['modeJourNuit'])) {
        $_SESSION['modeJourNuit'] = $_POST['modeJourNuit'];
    } elseif (isset($_POST['reset'])) {
        $_POST['resumeTour'] = "Vous avez reset la partie ! Vous pouvez maintenant en faire une nouvelle ! :)";
        session_unset();
        session_destroy();
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
                    $stringFrappe = "Le Guerrier attaque avec une frappe de " . $_SESSION['guerrier']->attack() . " ! ";
                    $_SESSION['orc']->setPointsDeVie($_SESSION['orc']->getPointsDeVie() - $_SESSION['guerrier']->attack());
                    $stringPointsDeVie = "L'Orc a perdu " . $_SESSION['guerrier']->attack() . " points de vie ! :) " . " Il lui reste " . $_SESSION['orc']->getPointsDeVie() . " points de vie ! :)";
                    $_SESSION['commencer'] = "orc";
                    $quiCommence = $_SESSION["commencer"];
                    $quiVaGagner = quiVaGagner();
                    $resumeTour = $stringFrappe . $stringPointsDeVie . $quiVaGagner;
                    $_SESSION['resumeTour'] = $resumeTour;
                } else {
                    // L'Orc va attaquer le Guerrier
                    $attackAleatoire = $_SESSION['orc']->attack();
                    $stringFrappe =  "L'Orc attaque avec une frappe de " . $attackAleatoire . " ! ";
                    $degats = $_SESSION['guerrier']->getDamage($attackAleatoire);
                    $stringPointsDeVie = "Le Guerrier a perdu " . $degats . " points de vie ! :) " . " Il lui reste " . $_SESSION['guerrier']->getPointsDeVie() . " points de vie ! :)";
                    $_SESSION['commencer'] = "guerrier";
                    $quiCommence = $_SESSION["commencer"];
                    $quiVaGagner = quiVaGagner();
                    $resumeTour = $stringFrappe . $stringPointsDeVie . $quiVaGagner;
                    $_SESSION['resumeTour'] = $resumeTour;
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
<?php if (isset($_SESSION['modeJourNuit'])) { ?>
    <?php if ($_SESSION['modeJourNuit'] == 'Mode Nuit') { ?>
         mode-nuit
    <?php } else { ?>
        mode-jour
    <?php } ?>
<?php } ?>mode-jour">
    <div class="d-flex align-items-center">
        <h1 class="mt-5 ms-5
        <?php if (isset($_SESSION['modeJourNuit'])) { ?>
            <?php if ($_SESSION['modeJourNuit'] == 'Mode Nuit') { ?>
                titre-nuit
            <?php } else { ?>
                titre-jour
            <?php } ?>
        <?php } ?>titre-jour">Combat Légendaire !!!!</h1>
        <div class="w-100 d-flex justify-content-end align-items-center">
            <form action="" method="POST">
                <input class="btn mx-5 text-white
                <?php if (isset($_SESSION['modeJourNuit'])) { ?>
                    <?php if ($_SESSION['modeJourNuit'] == 'Mode Nuit') { ?>
                        btns-nuit
                    <?php } else { ?>
                        btns-jour
                    <?php } ?>
                <?php } ?>btns-jour" type="submit" name="modeJourNuit" id="modeJourNuit"
                    <?php if (isset($_SESSION['modeJourNuit'])) { ?>
                    <?php if ($_SESSION['modeJourNuit'] == 'Mode Nuit') { ?>
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
                <div class="d-flex justify-content-center align-items-center">
                    <img class="text-white" src="assets/img/heart_3.png" alt="assets/img/heart_3.png">
                    <p class="ms-3 mx-3 mt-3"><?= $_SESSION['guerrier']->getPointsDeVie() ?> / <?= $_SESSION["pointsDeVieTotalGuerrier"] ?></p>
                </div>
                <div class="d-flex justify-content-center mt-3">
                    <div class="perso-informations mt-3">
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
                    <div class="d-flex justify-content-center align-items-center">
                        <img class="text-white" src="assets/img/heart_3.png" alt="assets/img/heart_3.png">
                        <p class="ms-3 mx-3 mt-3"><?= $_SESSION['orc']->getPointsDeVie() ?> / <?= $_SESSION["pointsDeVieTotalOrc"] ?></p>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        <div class="perso-informations mt-3">
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
                                <p class="ms-3 mx-3 mt-3"><?= $_SESSION['orc']->getDamageMin() ?> - <?= $_SESSION['orc']->getDamageMax() ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
    <?php if (isset($_SESSION['modeJourNuit'])) { ?>
        <?php if ($_SESSION['modeJourNuit'] == 'Mode Nuit') { ?>
            <div class="d-flex justify-content-center mt-2 fs-1">
                <p class="text-white"><?= isset($errors['pasGuerrier']) ? $errors['pasGuerrier'] : '' ?></p>
                <p class="text-white"><?= isset($errors['ouiGuerrier']) ? $errors['ouiGuerrier'] : '' ?></p>
                <p class="text-white"><?= isset($errors['pasOrc']) ? $errors['pasOrc'] : '' ?></p>
                <p class="text-white"><?= isset($errors['ouiOrc']) ? $errors['ouiOrc'] : '' ?></p>
                <p class="text-white"><?= isset($errors['peutPasCommencer']) ? $errors['peutPasCommencer'] : '' ?></p>
                <p class="text-white"><?= isset($errors['pasCommencerCombat']) ? $errors['pasCommencerCombat'] : '' ?></p>
                <p class="text-white"><?= isset($errors['commencer']) ? $errors['commencer'] : '' ?></p>
                <p class="text-white"><?= isset($_SESSION['resumeTour']) ? $_SESSION['resumeTour'] : '' ?></p>
                <p class="text-white"><?= isset($_POST['resumeTour']) ? $_POST['resumeTour'] : '' ?></p>
            </div>
        <?php } else { ?>
            <div class="d-flex justify-content-center mt-2 fs-1">
                <p class="text-black"><?= isset($errors['pasGuerrier']) ? $errors['pasGuerrier'] : '' ?></p>
                <p class="text-black"><?= isset($errors['ouiGuerrier']) ? $errors['ouiGuerrier'] : '' ?></p>
                <p class="text-black"><?= isset($errors['pasOrc']) ? $errors['pasOrc'] : '' ?></p>
                <p class="text-black"><?= isset($errors['ouiOrc']) ? $errors['ouiOrc'] : '' ?></p>
                <p class="text-black"><?= isset($errors['peutPasCommencer']) ? $errors['peutPasCommencer'] : '' ?></p>
                <p class="text-black"><?= isset($errors['pasCommencerCombat']) ? $errors['pasCommencerCombat'] : '' ?></p>
                <p class="text-black"><?= isset($errors['commencer']) ? $errors['commencer'] : '' ?></p>
                <p class="text-black"><?= isset($_SESSION['resumeTour']) ? $_SESSION['resumeTour'] : '' ?></p>
                <p class="text-black"><?= isset($_POST['resumeTour']) ? $_POST['resumeTour'] : '' ?></p>
            </div>
        <?php } ?>
    <?php } else { ?>
        <div class="d-flex justify-content-center mt-2 fs-1">
            <p class="text-black"><?= isset($errors['pasGuerrier']) ? $errors['pasGuerrier'] : '' ?></p>
            <p class="text-black"><?= isset($errors['ouiGuerrier']) ? $errors['ouiGuerrier'] : '' ?></p>
            <p class="text-black"><?= isset($errors['pasOrc']) ? $errors['pasOrc'] : '' ?></p>
            <p class="text-black"><?= isset($errors['ouiOrc']) ? $errors['ouiOrc'] : '' ?></p>
            <p class="text-black"><?= isset($errors['peutPasCommencer']) ? $errors['peutPasCommencer'] : '' ?></p>
            <p class="text-black"><?= isset($errors['pasCommencerCombat']) ? $errors['pasCommencerCombat'] : '' ?></p>
            <p class="text-black"><?= isset($errors['commencer']) ? $errors['commencer'] : '' ?></p>
            <p class="text-black"><?= isset($_SESSION['resumeTour']) ? $_SESSION['resumeTour'] : '' ?></p>
            <p class="text-black"><?= isset($_POST['resumeTour']) ? $_POST['resumeTour'] : '' ?></p>
        </div>
    <?php } ?>
    <form action="" method="POST">
        <div class="d-flex justify-content-center">
            <?php if (isset($_SESSION['modeJourNuit'])) { ?>
                <?php if ($_SESSION['modeJourNuit'] == 'Mode Nuit') { ?>
                    <input class="btn btns-nuit mx-3 ms-3 mt-5 text-white" type="submit" name="guerrier" id="guerrier" value="Créer Guerrier" <?= isset($_SESSION['guerrier']) ? 'disabled' : '' ?>>
                    <input class="btn btns-nuit mx-3 ms-3 mt-5 text-white" type="submit" name="orc" id="orc" value="Créer Orc" <?= isset($_SESSION['orc']) ? 'disabled' : '' ?>>
                    <input class="btn btns-nuit mx-3 ms-3 mt-5 text-white" type="submit" name="commencer" id="commencer" value="Qui commence ?" <?= isset($_SESSION['commencer']) ? 'disabled' : '' ?>>
                    <input class="btn btns-nuit mx-3 ms-3 mt-5 text-white" type="submit" name="combat" id="combat" value="Combat !" <?= isset($_SESSION['guerrier']) && isset($_SESSION['orc']) ? ($_SESSION['guerrier']->getPointsDeVie() <= 0 || $_SESSION['orc']->getPointsDeVie() <= 0 ? 'disabled' : '') : '' ?>>
                    <input class="btn btns-nuit mx-3 ms-3 mt-5 text-white" type="submit" name="reset" id="reset" value="Reset">
                <?php } else { ?>
                    <input class="btn btns-jour mx-3 ms-3 mt-5 text-white" type="submit" name="guerrier" id="guerrier" value="Créer Guerrier" <?= isset($_SESSION['guerrier']) ? 'disabled' : '' ?>>
                    <input class="btn btns-jour mx-3 ms-3 mt-5 text-white" type="submit" name="orc" id="orc" value="Créer Orc" <?= isset($_SESSION['orc']) ? 'disabled' : '' ?>>
                    <input class="btn btns-jour mx-3 ms-3 mt-5 text-white" type="submit" name="commencer" id="commencer" value="Qui commence ?" <?= isset($_SESSION['commencer']) ? 'disabled' : '' ?>>
                    <input class="btn btns-jour mx-3 ms-3 mt-5 text-white" type="submit" name="combat" id="combat" value="Combat !" <?= isset($_SESSION['guerrier']) && isset($_SESSION['orc']) ? ($_SESSION['guerrier']->getPointsDeVie() <= 0 || $_SESSION['orc']->getPointsDeVie() <= 0 ? 'disabled' : '') : '' ?>>
                    <input class="btn btns-jour mx-3 ms-3 mt-5 text-white" type="submit" name="reset" id="reset" value="Reset">
                <?php } ?>
            <?php } else { ?>
                <input class="btn btns-jour mx-3 ms-3 mt-5 text-white" type="submit" name="guerrier" id="guerrier" value="Créer Guerrier" <?= isset($_SESSION['guerrier']) ? 'disabled' : '' ?>>
                <input class="btn btns-jour mx-3 ms-3 mt-5 text-white" type="submit" name="orc" id="orc" value="Créer Orc" <?= isset($_SESSION['orc']) ? 'disabled' : '' ?>>
                <input class="btn btns-jour mx-3 ms-3 mt-5 text-white" type="submit" name="commencer" id="commencer" value="Qui commence ?" <?= isset($_SESSION['commencer']) ? 'disabled' : '' ?>>
                <input class="btn btns-jour mx-3 ms-3 mt-5 text-white" type="submit" name="combat" id="combat" value="Combat !" <?= isset($_SESSION['guerrier']) && isset($_SESSION['orc']) ? ($_SESSION['guerrier']->getPointsDeVie() <= 0 || $_SESSION['orc']->getPointsDeVie() <= 0 ? 'disabled' : '') : '' ?>>
                <input class="btn btns-jour mx-3 ms-3 mt-5 text-white" type="submit" name="reset" id="reset" value="Reset">
            <?php } ?>
        </div>
    </form>
</body>

</html>