<?php
// On inclut notre boîte à outils
require_once 'include/firebase_db.php';

// 1. Création d'un produit de test (Tableau PHP simple)
$nouveauKart = [
    "nom" => "Kart Test BTS",
    "prix" => 1500,
    "stock" => 5
];

// 2. Envoi vers Firebase (On utilise 'POST' pour ajouter)
// Cela va créer : produits > (id aléatoire) > { nom: ..., prix: ... }
$resultat = appelFirebase('produits', $nouveauKart, 'POST');

// 3. Affichage du résultat
echo "<h3>Test d'envoi :</h3>";
var_dump($resultat); // Doit afficher un truc comme ["name" => "-N76dhs..."]

// 4. Lecture pour vérifier
$mesProduits = appelFirebase('produits');
echo "<h3>Lecture de la base :</h3>";
echo "<pre>";
print_r($mesProduits);
echo "</pre>";
