<?php
require_once 'config.php';

/**
 * Fonction simple pour parler à Firebase
 * $table : le nom du dossier (ex: "produits")
 * $donnees : ce qu'on veut envoyer (tableau PHP)
 * $method : "GET" (lire), "POST" (ajouter), "PUT" (modifier), "DELETE" (supprimer)
 */
function appelFirebase($table, $donnees = null, $method = 'GET')
{
    // 1. On prépare l'URL (ex: https://.../produits.json)
    $url = URL_FIREBASE . $table . '.json';

    // 2. On initialise cURL (le messager)
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Pour éviter les soucis SSL en local

    // 3. Si on envoie des données (POST, PUT, DELETE)
    if ($method != 'GET') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        if ($donnees != null) {
            // On transforme le tableau PHP en texte JSON pour Firebase
            $json = json_encode($donnees);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        }
    }

    // 4. On exécute l'envoi
    $reponse = curl_exec($ch);

    // 5. On ferme la connexion
    curl_close($ch);

    // 6. On transforme la réponse JSON de Firebase en tableau PHP
    return json_decode($reponse, true);
}
