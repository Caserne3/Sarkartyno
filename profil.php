<?php
// On inclut le header qui devrait gérer session_start() s'il était bien fait
// Mais par sécurité, on vérifie si la session est active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. SÉCURITÉ : Vérification de connexion
if (!isset($_SESSION['user'])) {
    // Pas connecté ? Dehors !
    header('Location: login.php');
    exit;
}

// 2. Gestion de la Déconnexion
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    header("Location: catalogue.php"); // Retour à l'accueil
    exit;
}

include 'include/header.php';
?>

<main class="page-profil" style="max-width: 800px; margin: 50px auto; padding: 20px;">

    <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); text-align: center;">

        <h1 style="color: #333; margin-bottom: 10px;">Mon Espace Pilote 🏎️</h1>

        <div style="font-size: 5rem; margin: 20px 0;">👤</div>

        <h2 style="color: #e74c3c;">
            Bienvenue, <?php echo htmlspecialchars($_SESSION['user']['prenom']) . " " . htmlspecialchars($_SESSION['user']['nom']); ?> !
        </h2>

        <p style="font-size: 1.2rem; color: #666; margin-bottom: 30px;">
            <strong>Email :</strong> <?php echo htmlspecialchars($_SESSION['user']['email']); ?>
        </p>

        <hr style="border: 0; border-top: 1px solid #eee; margin: 30px 0;">

        <h3>Mes Actions</h3>

        <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;">
            <a href="panier.php" class="btn">Voir mon panier</a>
            <a href="configurateur.php" class="btn">Configurer un nouveau Kart</a>
            <a href="profil.php?action=logout" class="btn-retour" style="color: white; background: #e74c3c; padding: 10px 20px; border-radius: 5px; text-decoration: none;">Se déconnecter</a>
        </div>

    </div>

</main>

<?php include 'include/footer.php'; ?>