<?php
require_once "Character.php";
require_once "Guerrier.php";

$erza = new Character(10000, 10000);
var_dump($erza);

$lucy = new Character(10000, 10000);
var_dump($lucy);

$kevin = new Guerrier(10000, 10000, "Ultima", 100, "Master Shield", 19);
var_dump($kevin);

// $audrey = new Guerrier("Audrey", "DUVAL", 10000, "Ultima", 100, "Master Shield", 19);
// var_dump($audrey);
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

</body>

</html>