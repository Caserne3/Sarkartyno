<?php
echo "<h1>Debug d'Assets</h1>";
echo "<p>Dossier actuel : " . __DIR__ . "</p>";

$cssPath = __DIR__ . '/assets/css/style.css';
$imgPath = __DIR__ . '/assets/img/casque.jpg';

echo "<p>Vérification CSS : " . (file_exists($cssPath) ? "OK (Trouvé)" : "ERREUR (Pas trouvé)") . "</p>";
echo "<p>Vérification Image : " . (file_exists($imgPath) ? "OK (Trouvé)" : "ERREUR (Pas trouvé)") . "</p>";

// Test d'affichage direct
echo "<h2>Test Visuel</h2>";
echo '<img src="assets/img/casque.jpg" alt="Test Relatif" style="width:100px;">';
echo '<img src="/Sarkartyno/assets/img/casque.jpg" alt="Test Absolu" style="width:100px;">';
