<?php
require_once 'include/firebase_db.php';

// 1. Récupération de l'ID depuis l'URL
$id_produit = $_GET['id'] ?? null;

// 2. Sécurité : Si pas d'ID, redirection vers le catalogue
if (!$id_produit) {
    header('Location: catalogue.php');
    exit;
}

// 3. Récupération du produit spécifique dans Firebase
$produit = appelFirebase("produits/$id_produit");

include 'include/header.php';
?>

<main class="page-detail">
    <?php if (!$produit): ?>
        <!-- Gestion d'erreur -->
        <div class="erreur-container">
            <h1>Oups !</h1>
            <p>Ce produit est introuvable.</p>
            <a href="catalogue.php" class="btn">Retour au catalogue</a>
        </div>
    <?php else:
        // Image par défaut si manquante
        $img = $produit['image'] ?? 'default.jpg';
    ?>
        <!-- Affichage du produit -->
        <div class="detail-container">
            <a href="catalogue.php" class="btn-retour">← Retour au catalogue</a>

            <div class="detail-grid">
                <!-- Colonne Gauche : Image -->
                <div class="col-image">
                    <img src="/Sarkartyno/assets/img/<?php echo $img; ?>" alt="<?php echo $produit['nom']; ?>">
                </div>

                <!-- Colonne Droite : Infos -->
                <div class="col-infos">
                    <h1><?php echo $produit['nom']; ?></h1>
                    <p class="categorie-tag"><?php echo strtoupper($produit['categorie']); ?></p>

                    <p class="prix-gros"><?php echo $produit['prix']; ?> €</p>

                    <div class="description-bloc">
                        <h3>Description</h3>
                        <p><?php echo $produit['description']; ?></p>
                    </div>

                    <?php if (isset($produit['details_moteur'])): ?>
                        <div class="tech-bloc">
                            <h3>Détails Techniques</h3>
                            <p>⚙️ <?php echo $produit['details_moteur']; ?></p>
                        </div>
                    <?php endif; ?>

                    <div class="actions">
                        <button class="btn btn-grand" disabled>Ajouter au panier (Bientôt)</button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</main>

<?php
include 'include/footer.php';
?>