<?php

function play() {

    $filePath = __DIR__ . '/Data.json';

    $json = file_get_contents($filePath);
    $data = json_decode($json, true) ?? [];

    $tableau = ["pierre", "papier", "ciseaux"];

    $debut = microtime(true); // début du chronomètre

    echo "pierre, papier ou ciseaux ?\n";

    $handle = fopen("php://stdin", "r");
    $choix = trim(fgets($handle));

    if (!in_array($choix, $tableau)) {
        echo "Choix invalide. Entrez pierre, papier ou ciseaux.\n";
        return;
    }

    $choixOrdinateur = $tableau[array_rand($tableau)];

    echo "Vous avez choisi : $choix\n";
    echo "L'ordinateur a choisi : $choixOrdinateur\n";

    if ($choix === $choixOrdinateur) {
        $resultat = "Egalite";
        echo "Egalite !\n";
    } elseif (
        ($choix === "pierre" && $choixOrdinateur === "ciseaux") ||
        ($choix === "papier" && $choixOrdinateur === "pierre") ||
        ($choix === "ciseaux" && $choixOrdinateur === "papier")
    ) {
        $resultat = "Victoire";
        echo "Vous gagnez !\n";
        $data['score'] = isset($data['score']) ? $data['score'] + 1 : 1;
        $data['victoires'] = isset($data['victoires']) ? $data['victoires'] + 1 : 1;
        $data['mains_gagnantes'][$choix] = ($data['mains_gagnantes'][$choix] ?? 0) + 1;
    } else {
        $resultat = "Défaite";
        echo "L'ordinateur gagne !\n";
        $data['score'] = 0;
    }

    $data['parties_jouees'] = ($data['parties_jouees'] ?? 0) + 1;
    $data['utilisation_mains'][$choix] = ($data['utilisation_mains'][$choix] ?? 0) + 1;

    // Fin du chronomètre
    $tempsPartie = microtime(true) - $debut;
    $data['temps_jeu'] = ($data['temps_jeu'] ?? 0) + round($tempsPartie);

    echo "Score actuel : " . ($data['score'] ?? 0) . "\n";

    // Sauvegarde Data.json
    file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT));

    // Historique
    $partie = [
        'joueur' => $choix,
        'cpu' => $choixOrdinateur,
        'resultat' => $resultat,
        'date' => date('Y-m-d H:i:s')
    ];

    $fichier = __DIR__ . '/historique.json';

    if (!file_exists($fichier)) {
        file_put_contents($fichier, json_encode(['historique' => []], JSON_PRETTY_PRINT));
    }

    $contenu = file_get_contents($fichier);
    $donneesExistantes = json_decode($contenu, true);
    
    if (!is_array($donneesExistantes) || !isset($donneesExistantes['historique'])) {
        $donneesExistantes = ['historique' => []];
    }

    $donneesExistantes['historique'][] = $partie;
    file_put_contents($fichier, json_encode($donneesExistantes, JSON_PRETTY_PRINT));
}
