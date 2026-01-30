<?php
include 'include/header.php';
require_once 'include/firebase_db.php';

$message_statut = "";
$style_statut = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. Récupération
    $email = htmlspecialchars($_POST['email']);
    $sujet = htmlspecialchars($_POST['sujet']);
    $texte = htmlspecialchars($_POST['message']);

    // 2. Traitement
    if (!empty($email) && !empty($sujet) && !empty($texte)) {

        // Préparation du tableau pour Firebase
        $nouveau_message = [
            'email' => $email,
            'sujet' => $sujet,
            'message' => $texte,
            'date' => date('Y-m-d H:i:s')
        ];

        // Envoi à Firebase
        $res = appelFirebase('messages', $nouveau_message, 'POST');

        // Retour utilisateur
        if ($res && isset($res['name'])) {
            $message_statut = "Votre message a bien été envoyé au SAV.";
            $style_statut = "green";
        } else {
            // Fallback si Firebase échoue, on fait "semblant" que c'est ok ou on met une erreur ?
            // L'utilisateur a dit "Afficher un message de succès vert"
            // On va supposer que ça marche si Firebase échoue ? Non, restons honnêtes mais simples.
            // Si erreur technique, on l'affiche.
            $message_statut = "Erreur technique lors de l'envoi.";
            $style_statut = "red";
        }
    } else {
        $message_statut = "Veuillez remplir tous les champs.";
        $style_statut = "red";
    }
}
?>

<main class="page-contact" style="max-width: 600px; margin: 50px auto; padding: 20px;">
    <h1>Contactez le SAV Sarkartyno 🛠️</h1>

    <p>Une question sur votre commande ? Un souci avec votre kart ? Écrivez-nous !</p>

    <?php if ($message_statut): ?>
        <p style="color: white; background: <?php echo $style_statut; ?>; padding: 15px; border-radius: 5px; font-weight: bold; text-align: center;">
            <?php echo $message_statut; ?>
        </p>
    <?php endif; ?>

    <form method="POST" action="contact.php" style="display: flex; flex-direction: column; gap: 15px; margin-top: 20px;">

        <div class="form-group">
            <label for="email">Votre Email :</label>
            <input type="email" id="email" name="email" required class="form-control"
                value="<?php echo isset($_SESSION['user']['email']) ? $_SESSION['user']['email'] : ''; ?>">
        </div>

        <div class="form-group">
            <label for="sujet">Sujet :</label>
            <input type="text" id="sujet" name="sujet" required class="form-control">
        </div>

        <div class="form-group">
            <label for="message">Message :</label>
            <textarea id="message" name="message" rows="5" required class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-grand">Envoyer</button>
    </form>

</main>

<?php include 'include/footer.php'; ?>