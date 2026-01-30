<?php
include 'include/header.php';
require_once 'include/firebase_db.php';

$message = "";

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. Récupération des champs
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];

    // 2. Vérification (Simple)
    if (!empty($nom) && !empty($prenom) && !empty($email) && !empty($password)) {

        // 3. SÉCURITÉ : Hachage du mot de passe
        // On ne stocke JAMAIS le mot de passe en clair !
        $mdp_hash = password_hash($password, PASSWORD_DEFAULT);

        // 4. Préparation des données pour Firebase
        $nouvel_utilisateur = [
            "nom" => $nom,
            "prenom" => $prenom,
            "email" => $email,
            "password" => $mdp_hash // On envoie le hash
        ];

        // 5. Envoi à Firebase
        // Table 'utilisateurs', Données, Méthode POST (pour créer un nouvel ID unique)
        $resultat = appelFirebase('utilisateurs', $nouvel_utilisateur, 'POST');

        if ($resultat && isset($resultat['name'])) {
            // Succès (Firebase retourne le 'name' de la nouvelle entrée)
            // Redirection vers la page de connexion
            header("Location: login.php?inscription=succes");
            exit;
        } else {
            $message = "Erreur lors de l'enregistrement dans Firebase.";
        }
    } else {
        $message = "Veuillez remplir tous les champs.";
    }
}
?>

<main class="page-inscription" style="max-width: 500px; margin: 50px auto; padding: 20px;">
    <h1>Inscription Membre</h1>

    <?php if ($message): ?>
        <p style="color: red; background: #ffe6e6; padding: 10px; border-radius: 5px;"><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="POST" action="inscription.php" style="display: flex; flex-direction: column; gap: 15px;">

        <div class="form-group">
            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom" required class="form-control">
        </div>

        <div class="form-group">
            <label for="prenom">Prénom :</label>
            <input type="text" id="prenom" name="prenom" required class="form-control">
        </div>

        <div class="form-group">
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" required class="form-control">
        </div>

        <div class="form-group">
            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required class="form-control">
        </div>

        <button type="submit" class="btn btn-grand">S'inscrire</button>
    </form>

    <p style="text-align: center; margin-top: 20px;">
        Déjà membre ? <a href="login.php">Connectez-vous ici</a>
    </p>

</main>

<?php include 'include/footer.php'; ?>