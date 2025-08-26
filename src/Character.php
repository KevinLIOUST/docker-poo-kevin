<?php

// Classe Character pour Créer un personnage qui va combattre quelqu'un d'autre
class Character
{
    // // Attribut pour le prénom du personnage
    // private string $prenom;

    // // Attribut pour le nom du personnage
    // private string $nom;

    // Attribut $pointsDeVie pour définir les points de vie du personnage
    private int $pointsDeVie;

    // Attibut $pointsDeMana pour définir les points de magie ou d'énergie du joueur
    private int $pointsDeMana;

    // // Méthode (fonction) pour modifier le prénom du joueur
    // public function setPrenom(string $newPrenom)
    // {
    //     $this->prenom = $newPrenom;
    // }

    // // Méthode (fonction) pour récupérer le prénom du joueur
    // public function getPrenom()
    // {
    //     return $this->prenom;
    // }

    // // Méthode (fonction) pour modifier le nom du joueur
    // public function setNom(string $newNom)
    // {
    //     $this->nom = $newNom;
    // }

    // // Méthode (fonction) pour récupérer le nom du joueur
    // public function getNom()
    // {
    //     return $this->nom;
    // }

    // Méthode pour modifier les points de vie du personnage quand il se prend des dégâts, ou se soigne par exemple
    public function setPointsDeVie(int $newPointsDeVie)
    {
        $this->pointsDeVie = $newPointsDeVie;
    }

    // Méthode pour modifier les points de mana quand le personnage attaque sa cible
    public function setPointsDeMana(int $newPointsDeMana)
    {
        $this->pointsDeMana = $newPointsDeMana;
    }

    // Méthode pour récupérer les points de mana du personnage
    public function getPointsDeVie()
    {
        return $this->pointsDeVie;
    }

    // Méthode pour récupérer les points de mana du personnage
    public function getMana()
    {
        return $this->pointsDeMana;
    }

    // Constructeur pour construire un personnage avec des points de vie et des points de mana
    public function __construct(int $pointsDeVie, int $pointsDeMana)
    {
        $this->setPointsDeVie($pointsDeVie);
        $this->setPointsDeMana($pointsDeMana);
    }
}
?>