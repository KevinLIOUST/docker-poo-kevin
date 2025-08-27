<?php
// Importation de fichiers poue êttre utilisés
require_once "Character.php";
require_once "Guerrier.php";
require_once "Orc.php";

// Démarrage de la session pour manipuler les données de la session en question
session_start();
// session_unset();
// session_destroy();

// $erza = new Character(10000, 10000);
// var_dump($erza);

// $lucy = new Character(10000, 10000);
// var_dump($lucy);

// $kevin = new Guerrier(10000, 10000, "Ultima", 100, "Master Shield", 19);
// var_dump($kevin);

// $orc = new Orc(10000, 10000, 100, 200);
// var_dump($orc);

// $audrey = new Guerrier("Audrey", "DUVAL", 10000, "Ultima", 100, "Master Shield", 19);
// var_dump($audrey);

// On regarde si la méthode est bien POST pour envoyer des données au serveur
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = [];

    if (isset($_POST["guerrier"])) {

        if (!isset($_SESSION["guerrier"])) {

            $errors['pasGuerrier'] = "Il y a pas de Guerrier dans le jeu, il sera crée maintenant";
            $_SESSION["guerrier"] = new Guerrier(2000, 500, "Ultima", 250, "Bouclier Ultime", 600);

        } else {
            $errors['ouiGuerrier'] = "Le Guerrier existe déjà ! :) Pas besoin de le recréer ! :)";
        }

    } elseif (isset($_POST["orc"])) {

        if (!isset($_SESSION["orc"])) {

            $errors['pasOrc'] = "Il y a pas d'Orc dans le jeu, il sera crée maintenant";
            $_SESSION["orc"] = new Orc(1500, 200, 100, 400);

        } else {
            $errors['ouiOrc'] = "L'orc existe déjà ! :) Pas besoin de le recréer ! :)";
        }

    } elseif (isset($_POST["commencer"])) {

        if (!isset($_SESSION["orc"]) || !isset($_SESSION["guerrier"])) {

            $errors['peutPasCommencer'] = "La partie peut pas commencer sans le Guerrier et l'Orc !";

        } elseif (isset($_SESSION['guerrier']) && isset($_SESSION['orc'])) {

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
    } else {
        echo "Pas bouton cliqué";
    }

    // var_dump($errors);
}
// var_dump($_POST);
// var_dump($_SESSION);

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

<body>
    <div class="d-flex justify-content-center">
        <h1>Combat Légendaire !!!!</h1>
    </div>
    <form action="" method="POST">
        <div class="d-flex justify-content-center">
            <input class="btn btns mx-3 ms-3 mt-3 text-white" type="submit" name="guerrier" id="guerrier"
                value="Créer Guerrier">
            <input class="btn btns mx-3 ms-3 mt-3 text-white" type="submit" name="orc" id="orc" value="Créer Orc">
        </div>
        <div class="d-flex justify-content-center">
            <input class="btn btns mx-3 ms-3 mt-5 text-white" type="submit" name="commencer" id="commencer"
                value="Qui commence ?">
        </div>
    </form>
</body>

</html>