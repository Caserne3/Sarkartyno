<?php
include 'include/header.php';
?>

<main class="page-configurateur">
    <h1>Configurateur de Kart</h1>

    <div class="config-grid">
        <!-- Colonne Gauche : Visuel -->
        <div class="col-visuel">
            <!-- Image par défaut : Thermique Noir -->
            <img id="kart-visual" src="/Sarkartyno/assets/img/kart_thermique1.jpg" alt="Aperçu du Kart">
        </div>

        <!-- Colonne Droite : Formulaire -->
        <div class="col-form">
            <form method="POST" action="panier.php" id="form-config">

                <!-- 1. Choix du Modèle (Remplace Châssis + Moteur initiaux) -->
                <div class="form-group">
                    <label class="label-titre">1. Choisissez votre Modèle</label>
                    <div class="modele-selector">

                        <!-- Option 1: Loisir (Thermique Standard) -->
                        <label class="modele-card">
                            <input type="radio" name="modele_visuel" value="loisir" checked>
                            <div class="card-content">
                                <img src="/Sarkartyno/assets/img/kart_thermique.jpg" alt="Loisir">
                                <span>Loisir</span>
                                <small>Châssis Acier + Moteur 4T</small>
                            </div>
                        </label>

                        <!-- Option 2: Sport (Thermique Compétition) -->
                        <label class="modele-card">
                            <input type="radio" name="modele_visuel" value="sport">
                            <div class="card-content">
                                <img src="/Sarkartyno/assets/img/kart_race.jpg" alt="Sport">
                                <span>Sport</span>
                                <small>Châssis Alu + Moteur 2T</small>
                            </div>
                        </label>

                        <!-- Option 3: Électrique -->
                        <label class="modele-card">
                            <input type="radio" name="modele_visuel" value="electrique">
                            <div class="card-content">
                                <img src="/Sarkartyno/assets/img/kart_elec.jpg" alt="Électrique">
                                <span>Électrique</span>
                                <small>Châssis Spécial + Moteur 20kW</small>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Champs cachés pour compatibilité avec le reste du formulaire/panier -->
                <!-- On les remplira via JS selon le modèle choisi -->
                <input type="hidden" name="chassis" id="chassis" value="standard">

                <!-- 2. Puissance (Affiché uniquement si Modèle = Loisir/Sport) -->
                <div class="form-group" id="group-moteur">
                    <label>2. Puissance Moteur</label>
                    <div class="radio-group">
                        <label>
                            <input type="radio" name="moteur" value="125cc" data-prix="0" checked>
                            125cc (Inclus)
                        </label>
                        <label>
                            <input type="radio" name="moteur" value="270cc" data-prix="300">
                            270cc (+300€)
                        </label>
                        <!-- L'option électrique est gérée par le modèle visuel désormais -->
                    </div>
                </div>

                <!-- 3. Couleur -->
                <div class="form-group">
                    <label for="couleur">Couleur</label>
                    <select name="couleur" id="couleur" class="form-control">
                        <option value="standard">Couleur Standard (Base)</option>
                        <option value="noir">Noir</option>
                        <option value="rouge">Rouge</option>
                        <option value="bleu">Bleu</option>
                        <option value="saumon">Saumon (Special Thermique)</option>
                    </select>
                    <small>Note : Certaines couleurs sont spécifiques à une motorisation.</small>
                </div>

                <!-- 4. Options -->
                <div class="form-group">
                    <label>Options</label>
                    <div class="checkbox-group">
                        <label>
                            <input type="checkbox" name="options[]" value="pluie" data-prix="150">
                            Pneus Pluie (+150€)
                        </label>
                        <label>
                            <input type="checkbox" name="options[]" value="gps" data-prix="200">
                            Chronomètre GPS (+200€)
                        </label>
                    </div>
                </div>

                <!-- Zone Prix -->
                <div class="prix-zone">
                    <h2>Prix Total : <span id="total-display">0</span> €</h2>
                    <input type="hidden" name="final_price" id="final_price" value="0">
                </div>

                <!-- Bouton -->
                <button type="submit" class="btn btn-grand">Commander</button>

            </form>
        </div>
    </div>
</main>
<script src="/Sarkartyno/assets/js/configurateur.js"></script>

<?php
include 'include/footer.php';
?>