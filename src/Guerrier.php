<?php
// Classe Guerrier pour créer un guerrier pour combattre quelqu'un d'autre
class Guerrier extends Character
{
    // // Attribut pour le prénom du personnage
    // private string $prenom;

    // // Attribut pour le nom du personnage
    // private string $nom;

    // // Attribut pour définir les points de vie du personnage
    // private int $pointsDeVie;

    // Attribut $arme pour l'arme du personnage
    private string $arme;

    // Attribut $degatsArme pour les dégâts infligés par l'arme en question
    private int $degatsArme;

    // Attribut $nomBouclier pour donner un nom au bouclier du personnage
    private string $nomBouclier;

    // Attribut $defenseBouclier pour réduire les dégâts grâce au bouclier du personnage
    private int $defenseBouclier;

    // // Méthode (fonction) pour modifier le prénom du personnage
    // public function setPrenom(string $newPrenom)
    // {
    //     $this->prenom = $newPrenom;
    // }

    // // Méthode (fonction) pour récupérer le prénom du personnage
    // public function getPrenom()
    // {
    //     return $this->prenom;
    // }

    // // Méthode (fonction) pour modifier le nom du personnage
    // public function setNom(string $newNom)
    // {
    //     $this->nom = $newNom;
    // }

    // // Méthode (fonction) pour récupérer le nom du personnage
    // public function getNom()
    // {
    //     return $this->nom;
    // }

    // // Méthode (fonction) pour modifier le nom du personnage
    // public function setPointsDeVie(int $newPointsDeVie)
    // {
    //     $this->pointsDeVie = $newPointsDeVie;
    // }

    // // Méthode (fonction) pour récupérer les points de vie du personnage
    // public function getPointsDeVie()
    // {
    //     return $this->pointsDeVie;
    // }

    // Méthode (fonction) pour modifier l'arme du personnage
    public function setArme(string $newArme)
    {
        $this->arme = $newArme;
    }

    // Méthode (fonction) pour modifier les dégâts de l'arme du personnage
    public function setDegatsArme(int $newDegatsArme)
    {
        $this->degatsArme = $newDegatsArme;
    }

    // Méthode (fonction) pour modifier le nom du bouclier du personnage
    public function setNomBouclier(string $newNomBouclier)
    {
        $this->nomBouclier = $newNomBouclier;
    }

    // Méthode (fonction) pour modifier la réduction de dégâts du bouclier du personnage
    public function setDefenseBouclier(int $newDefenseBouclier)
    {
        $this->defenseBouclier = $newDefenseBouclier;
    }

    // Méthode (fonction) pour récupérer l'arme du personnage
    public function getArme()
    {
        return $this->arme;
    }

    // Méthode (fonction) pour récupérer les dégâts de l'arme du personnage
    public function getDegatsArme()
    {
        return $this->degatsArme;
    }

    // Méthode (fonction) pour récupérer le nom du bouclier du personnage
    public function getNomBouclier()
    {
        return $this->nomBouclier;
    }

    // Méthode (fonction) pour récupérer la réduction de dégâts du bouclier du personnage
    public function getDefenceBouclier()
    {
        return $this->defenseBouclier;
    }

    // Méthode pour attaquer
    public function attack()
    {
        return $this->getDegatsArme();
    }

    // Constructeur pour construire un nouveau guerrier avec ses caractéristiques pour le combat
    public function __construct(int $pointsDeVie, int $pointsDeMana, string $arme, int $degatsArme, string $nomBouclier, int $defenseBouclier)
    {
        parent::__construct(10000, 10000);
        $this->setPointsDeVie($pointsDeVie);
        $this->setPointsDeMana($pointsDeMana);
        $this->setArme($arme);
        $this->setDegatsArme($degatsArme);
        $this->setNomBouclier($nomBouclier);
        $this->setDefenseBouclier($defenseBouclier);
    }
}
?>