<?php
session_start();
require_once 'include/firebase_db.php';

// 1. Gestion de l'action "Vider le panier"
if (isset($_GET['action']) && $_GET['action'] == 'vider') {
    unset($_SESSION['panier']);

    // Si connecté, on vide aussi chez Firebase
    if (isset($_SESSION['user']['id'])) {
        appelFirebase('paniers/' . $_SESSION['user']['id'], null, 'DELETE');
    }

    header("Location: panier.php");
    exit;
}

// 2. Traitement du formulaire CONFIGURATEUR
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['modele_visuel'])) {

    // Initialisation
    $prix = 1500; // Base (Loisir 125cc)
    $details_sup = [];

    // Récupération des champs
    $modele_visuel = $_POST['modele_visuel']; // loisir, sport, electrique
    $chassis = $_POST['chassis'] ?? 'standard'; // standard, competition
    $moteur = $_POST['moteur'] ?? '125cc';     // 125cc, 270cc
    $couleur = $_POST['couleur'] ?? 'standard';
    $options_recues = $_POST['options'] ?? []; // Tableau checkox

    // --- LOGIQUE DE PRIX SERVEUR (SECURITE) ---

    /* 
       Rappel des règles :
       - Sport (Chassis Compet) : +500
       - Electrique (Moteur Elec) : +800
       - Moteur 270cc (si thermique) : +300
    */

    // 1. Modèle / Châssis
    if ($modele_visuel === 'sport' || $chassis === 'competition') {
        $prix += 500;
        $details_sup[] = "Châssis Compétition";
    }

    // 2. Moteur
    if ($modele_visuel === 'electrique') {
        $prix += 800; // Delta vs 125cc
        $details_sup[] = "Moteur Électrique 20kW";
        $moteur_label = "Électrique";
    } else {
        // Thermique
        if ($moteur === '270cc') {
            $prix += 300;
            $details_sup[] = "Moteur 270cc";
        }
        $moteur_label = $moteur;
    }

    // 3. Options
    if (in_array('pluie', $options_recues)) {
        $prix += 150;
        $details_sup[] = "Pneus Pluie";
    }
    if (in_array('gps', $options_recues)) {
        $prix += 200;
        $details_sup[] = "Chrono GPS";
    }

    // --- DETERMINATION IMAGE ---
    // On essaie de refaire la logique JS pour l'image
    $image_finale = "kart_thermique.jpg"; // Default

    if ($modele_visuel === 'electrique') {
        $image_finale = ($couleur === 'rouge') ? "kart_elec1.jpg" : "kart_elec.jpg";
    } elseif ($modele_visuel === 'sport') {
        $image_finale = ($couleur === 'noir') ? "kart_race1.jpg" : "kart_race.jpg";
    } else {
        // Loisir
        if ($couleur === 'noir') $image_finale = "kart_thermique1.jpg";
        elseif ($couleur === 'rouge') $image_finale = "kart_thermique2.jpg";
        elseif ($couleur === 'saumon') $image_finale = "kart_thermique3.jpg";
        else $image_finale = "kart_thermique.jpg";
    }

    // --- CONSTRUCTION DE L'ARTICLE ---
    $nom_complet = "Kart " . ucfirst($modele_visuel);
    if ($couleur != 'standard') $nom_complet .= " " . ucfirst($couleur);

    $description_options = implode(", ", $details_sup);
    if (empty($description_options)) $description_options = "Aucune option";

    $article = [
        "nom" => $nom_complet,
        "details" => $description_options,
        "prix" => $prix,
        "image" => $image_finale,
        "qte" => 1
    ];

    // Ajout en Session
    if (!isset($_SESSION['panier'])) {
        $_SESSION['panier'] = [];
    }
    $_SESSION['panier'][] = $article;

    // --- SAUVEGARDE PERSISTANTE (Si connecté) ---
    if (isset($_SESSION['user']['id'])) {
        // On met à jour le panier dans Firebase sous paniers/USER_ID
        // On re-envoie tout le tableau session panier
        appelFirebase('paniers/' . $_SESSION['user']['id'], $_SESSION['panier'], 'PUT');
    }

    // Redirection pour éviter le renvoi du formulaire (PRG Pattern)
    header("Location: panier.php");
    exit;
}
?>

<?php include 'include/header.php'; // Inclut le début du HTML (doctype, head, body, header) 
?>

<style>
    .page-panier {
        max-width: 1000px;
        margin: 0 auto;
        padding: 2rem;
    }

    .table-panier {
        border-collapse: collapse;
        width: 100%;
        margin-top: 20px;
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    }

    .table-panier th,
    .table-panier td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid #eee;
    }

    .table-panier th {
        background: #333;
        color: white;
    }

    .img-panier {
        width: 80px;
        height: 60px;
        object-fit: contain;
    }

    .total-row {
        font-weight: bold;
        font-size: 1.2rem;
        background: #f9f9f9;
    }

    .empty-cart {
        text-align: center;
        padding: 50px;
        color: #777;
    }
</style>

<main class="page-panier">
    <h1>Votre Panier</h1>

    <?php if (empty($_SESSION['panier'])): ?>
        <div class="empty-cart">
            <h2>Votre panier est vide 🛒</h2>
            <p>Configurez votre bolide dès maintenant !</p>
            <a href="configurateur.php" class="btn">Aller au configurateur</a>
        </div>
    <?php else: ?>
        <table class="table-panier">
            <thead>
                <tr>
                    <th>Aperçu</th>
                    <th>Produit</th>
                    <th>Options / Détails</th>
                    <th>Prix</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total_global = 0;
                foreach ($_SESSION['panier'] as $item):
                    $total_global += $item['prix'];
                ?>
                    <tr>
                        <td><img src="/Sarkartyno/assets/img/<?php echo $item['image']; ?>" class="img-panier" alt="Kart"></td>
                        <td><strong><?php echo $item['nom']; ?></strong></td>
                        <td><?php echo $item['details']; ?></td>
                        <td><?php echo $item['prix']; ?> €</td>
                    </tr>
                <?php endforeach; ?>

                <tr class="total-row">
                    <td colspan="3" style="text-align: right;">Total :</td>
                    <td><?php echo $total_global; ?> €</td>
                </tr>
            </tbody>
        </table>

        <div style="margin-top: 20px; text-align: right;">
            <a href="panier.php?action=vider" class="btn-retour" style="color: red; margin-right: 20px;">Vider le panier</a>
            <a href="login.php" class="btn btn-grand" style="width: auto; text-decoration: none; padding: 15px 30px;">Valider la commande</a>
        </div>
    <?php endif; ?>

</main>

<?php include 'include/footer.php'; ?>