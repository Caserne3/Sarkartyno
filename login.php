<?php
include 'include/header.php'; // Inclut session_start() si présent dans header ou parent
require_once 'include/firebase_db.php';

$message = "";

// Petit message si on vient de l'inscription
if (isset($_GET['inscription']) && $_GET['inscription'] == 'succes') {
    $message = "Inscription réussie ! Connectez-vous maintenant.";
    $msg_color = "green";
} else {
    $msg_color = "red";
}

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email_saisi = htmlspecialchars($_POST['email']);
    $password_saisi = $_POST['password'];

    if (!empty($email_saisi) && !empty($password_saisi)) {

        // 1. Récupérer TOUS les utilisateurs
        $users = appelFirebase('utilisateurs');

        $user_found = false;

        // 2. Algorithme de Recherche (Boucle Manuelle)
        if ($users && is_array($users)) {
            foreach ($users as $key => $user_data) {
                // Vérification de l'email
                if (isset($user_data['email']) && $user_data['email'] === $email_saisi) {

                    // Email trouvé ! Vérification du mot de passe
                    // On utilise password_verify pour comparer le clair avec le hash
                    if (password_verify($password_saisi, $user_data['password'])) {

                        // SUCCÈS : Connexion
                        $_SESSION['user'] = [
                            'nom' => $user_data['nom'],
                            'prenom' => $user_data['prenom'],
                            'email' => $user_data['email'],
                            'id' => $key // On stocke la clé Firebase (ID unique)
                        ];

                        // --- RECUPERATION DU PANIER ---
                        // On regarde si cet utilisateur a un panier sauvegardé dans Firebase
                        $user_cart = appelFirebase('paniers/' . $key);

                        if ($user_cart) {
                            // Il a un panier sauvegardé, on le charge dans la session
                            // Note: On pourrait merger avec le panier actuel si le visiteur a commencé sans être connecté
                            // Pour l'instant, on remplace ou on fusionne simplement
                            if (isset($_SESSION['panier']) && !empty($_SESSION['panier'])) {
                                $_SESSION['panier'] = array_merge($_SESSION['panier'], $user_cart);
                            } else {
                                $_SESSION['panier'] = $user_cart;
                            }
                        }

                        $user_found = true;

                        // Redirection vers profil (ou index pour l'instant)
                        // TODO: Créer profil.php plus tard
                        header("Location: catalogue.php");
                        exit;
                    } else {
                        // Mot de passe faux
                        $message = "Mot de passe incorrect.";
                    }

                    // On a trouvé l'email, pas la peine de continuer la boucle pour les autres users
                    // sauf si on veut gérer des doublons (ce qui ne devrait pas arriver)
                    break;
                }
            }
        }

        if (!$user_found && empty($message)) {
            $message = "Aucun utilisateur trouvé avec cet email.";
        }
    } else {
        $message = "Veuillez remplir tous les champs.";
    }
}
?>

<main class="page-login" style="max-width: 500px; margin: 50px auto; padding: 20px;">
    <h1>Connexion</h1>

    <?php if ($message): ?>
        <p style="color: <?php echo $msg_color; ?>; background: #f0f0f0; padding: 10px; border-radius: 5px; border-left: 5px solid <?php echo $msg_color; ?>;">
            <?php echo $message; ?>
        </p>
    <?php endif; ?>

    <form method="POST" action="login.php" style="display: flex; flex-direction: column; gap: 15px;">

        <div class="form-group">
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" required class="form-control" value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; ?>">
        </div>

        <div class="form-group">
            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required class="form-control">
        </div>

        <button type="submit" class="btn btn-grand">Se connecter</button>
    </form>

    <p style="text-align: center; margin-top: 20px;">
        Pas encore membre ? <a href="inscription.php">Créer un compte</a>
    </p>

</main>

<?php include 'include/footer.php'; ?>