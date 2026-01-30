/**
 * Fonction de filtrage des produits
 * @param {string} categorie - La catégorie à afficher ('tout', 'thermique', 'electrique', 'accessoire')
 */
function filtrer(categorie) {
    console.log("Filtre activé :", categorie);
    // 1. On sélectionne tous les articles produits
    const produits = document.querySelectorAll('.carte-produit');

    // 2. On parcourt chaque produit
    produits.forEach(produit => {
        // 3. On récupère la catégorie du produit via l'attribut data-cat
        const catProduit = produit.getAttribute('data-cat');

        // 4. Si la catégorie demandée est 'tout' OU correspond à celle du produit
        if (categorie === 'tout' || categorie === catProduit) {
            produit.style.display = ''; // Réinitialise l'affichage (visible)
        } else {
            produit.style.display = 'none'; // Cache le produit
        }
    });
}
