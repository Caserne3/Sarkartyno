<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sarkartyno</title>
    <link rel="stylesheet" href="/Sarkartyno/assets/css/style.css">
</head>

<body>

    <header>
        <nav>
            <div class="logo">🏁 Sarkartyno</div>
            <ul>
                <li><a href="/Sarkartyno/index.php">Accueil</a></li>
                <li><a href="/Sarkartyno/catalogue.php">Catalogue</a></li>
                <li><a href="/Sarkartyno/configurateur.php">Configurateur</a></li>
                <?php if (isset($_SESSION['user'])): ?>
                    <li><a href="/Sarkartyno/profil.php" style="color: #e74c3c; font-weight: bold;">Mon Profil (<?php echo htmlspecialchars($_SESSION['user']['prenom']); ?>)</a></li>
                <?php else: ?>
                    <li><a href="/Sarkartyno/login.php">Espace Membre</a></li>
                <?php endif; ?>
                <li><a href="/Sarkartyno/panier.php">Panier</a></li>
            </ul>
        </nav>
    </header>

    <main>