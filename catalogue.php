<?php
// 1. On inclut la configuration et le header
require_once 'include/firebase_db.php';
include 'include/header.php';

// 2. On récupère les produits depuis Firebase
// Rappel : la fonction 'appelFirebase' doit être dans include/firebase_db.php
$produits = appelFirebase('produits');

?>

<section class="catalogue-container">
    <h1>Nos Karts & Accessoires</h1>

    <div class="filtres">
        <button onclick="filtrer('tout')">Tout voir</button>
        <button onclick="filtrer('thermique')">Thermique</button>
        <button onclick="filtrer('electrique')">Électrique</button>
        <button onclick="filtrer('accessoire')">Accessoires</button>
    </div>

    <div class="grille-produits">
        <?php
        // Si la base est vide ou erreur de connexion
        if (!$produits) {
            echo "<p>Aucun produit trouvé. Vérifiez la connexion Firebase.</p>";
        } else {
            // Boucle pour afficher chaque produit
            foreach ($produits as $id => $kart) {
                // Protection basique si une image manque
                $img = $kart['image'] ? $kart['image'] : 'default.jpg';
        ?>

                <article class="carte-produit" data-cat="<?php echo $kart['categorie']; ?>">
                    <img src="/Sarkartyno/assets/img/<?php echo $img; ?>" alt="<?php echo $kart['nom']; ?>">
                    <div class="info">
                        <h3><?php echo $kart['nom']; ?></h3>
                        <p class="prix"><?php echo $kart['prix']; ?> €</p>
                        <a href="detail-produit.php?id=<?php echo $id; ?>" class="btn">Voir détails</a>
                    </div>
                </article>

        <?php
            }
        }
        ?>
    </div>
</section>

<script src="assets/js/catalogue.js"></script>

<?php
include 'include/footer.php';
?>