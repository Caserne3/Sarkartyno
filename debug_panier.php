<?php
session_start();
require_once 'include/firebase_db.php';

echo "<h1>Debug Panier & Session</h1>";

echo "<h2>1. Etat de la Session PHP</h2>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

if (isset($_SESSION['user']['id'])) {
    $userId = $_SESSION['user']['id'];
    echo "<h2>2. ID Utilisateur détecté</h2>";
    echo "ID: " . $userId . "<br>";

    echo "<h2>3. Vérification Firebase (Lecture du panier)</h2>";
    $firebasePath = 'paniers/' . $userId;
    echo "Chemin interrogé : " . URL_FIREBASE . $firebasePath . '.json<br>';

    $cartFromFirebase = appelFirebase('paniers/' . $userId);
    echo "<strong>Résultat brut Firebase :</strong><pre>";
    print_r($cartFromFirebase);
    echo "</pre>";
} else {
    echo "<h2 style='color:red;'>Utilisateur NON connecté (pas d'ID en session)</h2>";
}

echo "<h2>4. Test d'Ecriture (Optionnel)</h2>";
echo "<p>Voulez-vous tester une écriture factice ? <a href='?test_write=1'>Oui, test écriture</a></p>";

if (isset($_GET['test_write'])) {
    if (isset($_SESSION['user']['id'])) {
        $dummyData = ["test" => "Ceci est un test " . date('H:i:s')];
        $res = appelFirebase('paniers/' . $_SESSION['user']['id'], $dummyData, 'PUT');
        echo "Résultat écriture : ";
        print_r($res);
    } else {
        echo "Impossible de tester l'écriture : non connecté.";
    }
}
