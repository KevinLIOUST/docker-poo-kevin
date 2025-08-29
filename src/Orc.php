<?php

// Classe Orc pour définir un Orc qui va combattre
class Orc extends Character
{
    // Attribut $damageMin pour déterminer les dégâts minimum
    private int $damageMin;

    // Attribut $damageMin pour déterminer les dégâts maximum
    private int $damageMax;

    // Attribut $pathImage pour le chemin de l'image du monstre en question
    private string $pathImage;

    // Méthode pour modifier les dégâts minimum quand l'orc attaquera
    public function setDamageMin($newDamageMin)
    {
        $this->damageMin = $newDamageMin;
    }

    // Méthode pour récupérer les dégâts minimum quand l'orc attaquera
    public function getDamageMin()
    {
        return $this->damageMin;
    }

    // Méthode pour modifier les dégâts maximum quand l'orc attaquera
    public function setDamageMax($newDamageMax)
    {
        $this->damageMax = $newDamageMax;
    }

    // Méthode pour récupérer les dégâts maximum quand l'orc attaquera
    public function getDamageMax()
    {
        return $this->damageMax;
    }

    // Méthode pour modifier l'image du monstre en question
    public function setPathImage($newPathImage) {
        $this->pathImage = $newPathImage;
    }

    // Méthode pour récupérer l'image du monstre en question
    public function getPathImage() {
        return $this->pathImage;
    }

    // Fonction pour que l'orc puisse attaquer entre une valeur min et max inclus
    public function attack()
    {
        $nbAleatoire = mt_rand($this->getDamageMin(), $this->getDamageMax());
        return $nbAleatoire;
    }

    // Constructeur pour construire un Orc avec ses caractéristiques
    public function __construct(int $pointsDeVie, int $pointsDeMana, int $damageMin, int $damageMax, string $pathImage)
    {
        parent::__construct($pointsDeVie, $pointsDeMana);
        $this->setDamageMin($damageMin);
        $this->setDamageMax($damageMax);
        $this->setPathImage($pathImage);
    }
}
?>