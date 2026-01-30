// Constantes de Prix
const PRIX_BASE = 1500; // Prix Loisir 125cc
const PRIX_CHASSIS_SPORT = 500;
const PRIX_MOTEUR_270 = 300;
const PRIX_MOTEUR_ELEC = 800; // Delta par rapport au 125cc standard

// Sélection des éléments du DOM
const elModeles = document.querySelectorAll('input[name="modele_visuel"]');
const elChassisInput = document.getElementById('chassis'); // Input hidden
const elGroupMoteur = document.getElementById('group-moteur');
const elMoteurRadios = document.querySelectorAll('input[name="moteur"]');
const elCouleur = document.getElementById('couleur');
const elOptions = document.querySelectorAll('input[name="options[]"]');
const elTotalDisplay = document.getElementById('total-display');
const elFinalPrice = document.getElementById('final_price');
const elVisual = document.getElementById('kart-visual');

// Fonction principale : Mise à jour complète
function updateAll() {
    // 1. Récupérer le modèle visuel choisi
    let modele = 'loisir';
    elModeles.forEach(m => {
        if (m.checked) modele = m.value;
    });

    // 2. Appliquer les contraintes (Moteur & Couleur & Input Hidden) selon le modèle
    applyConstraints(modele);

    // 3. Calculer le prix
    calculatePrice(modele);
}

function applyConstraints(modele) {
    // Reset availabilities (visual only, logic below enforces selection)
    Array.from(elCouleur.options).forEach(opt => opt.disabled = false);

    if (modele === 'loisir') {
        // --- LOISIR (Standard Thermique) ---
        elChassisInput.value = 'standard';
        elGroupMoteur.style.display = 'block';

        // Image mapping :
        // Base -> kart_thermique.jpg
        // Noir -> kart_thermique1.jpg
        // Rouge -> kart_thermique2.jpg
        // Saumon -> kart_thermique3.jpg

        let imageSrc = "kart_thermique.jpg"; // Default Base

        if (elCouleur.value === 'noir') imageSrc = "kart_thermique1.jpg";
        else if (elCouleur.value === 'rouge') imageSrc = "kart_thermique2.jpg";
        else if (elCouleur.value === 'saumon') imageSrc = "kart_thermique3.jpg";
        else {
            // Si standard ou autre
            imageSrc = "kart_thermique.jpg";
        }

        // Désactiver Bleu
        Array.from(elCouleur.options).forEach(opt => {
            if (opt.value === 'bleu') opt.disabled = true;
        });

        // Si la couleur sélectionnée est désactivée (ex: on vient de Electric/Blue), on remet Standard
        if (elCouleur.options[elCouleur.selectedIndex].disabled) {
            elCouleur.value = 'standard';
            imageSrc = "kart_thermique.jpg";
        }

        elVisual.src = "/Sarkartyno/assets/img/" + imageSrc;

    } else if (modele === 'sport') {
        // --- SPORT (Competition) ---
        elChassisInput.value = 'competition';
        elGroupMoteur.style.display = 'block';

        // Mappings:
        // Base -> kart_race.jpg
        // Noir -> kart_race1.jpg

        let imageSrc = "kart_race.jpg";

        if (elCouleur.value === 'noir') imageSrc = "kart_race1.jpg";
        else {
            // Force Standard/Noir mainly
            // If user selected Standard, keep standard image.
            // If user selected Red/Saumon -> invalid for Race? 
            // Let's allow Standard and Noir only for now based on images.
            if (elCouleur.value !== 'standard' && elCouleur.value !== 'noir') {
                elCouleur.value = 'standard';
            }
            imageSrc = (elCouleur.value === 'noir') ? "kart_race1.jpg" : "kart_race.jpg";
        }

        // Désactiver les autres
        Array.from(elCouleur.options).forEach(opt => {
            if (opt.value !== 'standard' && opt.value !== 'noir') opt.disabled = true;
        });

        elVisual.src = "/Sarkartyno/assets/img/" + imageSrc;

    } else if (modele === 'electrique') {
        // --- ELECTRIQUE ---
        elChassisInput.value = 'standard';
        elGroupMoteur.style.display = 'none';

        // Mappings:
        // Base -> kart_elec.jpg
        // Rouge -> kart_elec1.png

        let imageSrc = "kart_elec.jpg";

        if (elCouleur.value === 'rouge') imageSrc = "kart_elec1.jpg";
        else {
            if (elCouleur.value !== 'standard' && elCouleur.value !== 'rouge') {
                elCouleur.value = 'standard';
            }
            imageSrc = (elCouleur.value === 'rouge') ? "kart_elec1.jpg" : "kart_elec.jpg";
        }

        // Désactiver les autres
        Array.from(elCouleur.options).forEach(opt => {
            if (opt.value !== 'standard' && opt.value !== 'rouge') opt.disabled = true;
        });

        elVisual.src = "/Sarkartyno/assets/img/" + imageSrc;
    }
}

function calculatePrice(modele) {
    let totalPrice = PRIX_BASE;

    // Ajout Modèle Spécifique
    if (modele === 'sport') {
        totalPrice += PRIX_CHASSIS_SPORT;
    } else if (modele === 'electrique') {
        totalPrice += PRIX_MOTEUR_ELEC;
    }

    // Ajout Moteur Thermique (Si pas électrique)
    if (modele !== 'electrique') {
        elMoteurRadios.forEach(radio => {
            if (radio.checked && radio.value === '270cc') {
                totalPrice += PRIX_MOTEUR_270;
            }
        });
    }

    // Ajout Options
    elOptions.forEach(chk => {
        if (chk.checked) {
            totalPrice += parseInt(chk.getAttribute('data-prix') || 0);
        }
    });

    // Affichage
    elTotalDisplay.innerText = totalPrice;
    elFinalPrice.value = totalPrice;
}

// Écouteurs d'événements
elModeles.forEach(m => m.addEventListener('change', updateAll));
elMoteurRadios.forEach(r => r.addEventListener('change', updateAll));
elCouleur.addEventListener('change', updateAll);
elOptions.forEach(o => o.addEventListener('change', updateAll));

// Init
updateAll();
