<?php

require_once 'menu.php';
require_once 'play.php';
require_once 'Stat.php';

$filePath = __DIR__ . '/Data.json';

// Lire le fichier JSON data

$json = file_get_contents($filePath);
$data = json_decode($json, true);

while (true) {
    afficherMenu(); // fonction de menu

    $choix = trim(fgets(STDIN));

    if ($choix == "1") {
        do {
            play();
            echo "Voulez-vous rejouer ? (oui/non) : ";
            $handle = fopen("php://stdin", "r");
            $rejouer = trim(fgets($handle));
        } while (strtolower($rejouer) === "oui");

    } elseif ($choix == "2") {
        $fichier = __DIR__ . '/historique.json';

        // Lire le fichier JSON historique

        $contenu = file_get_contents($fichier);
        $donneesExistantes = json_decode($contenu, true);

        //affichage de l'historique
        echo ">>> Historique des parties : \n";

        $historique = $donneesExistantes['historique'] ?? [];

        if (empty($historique)) {
        echo "Aucune partie enregistrée pour le moment.\n";
        } else {
        printf("%-20s | %-13s | %-10s | %-8s\n", "Date", "Choix Joueur", "Choix CPU", "Résultat");
        echo str_repeat("-", 60) . "\n";

        foreach ($historique as $partie) {
            printf(
                "%-20s | %-13s | %-10s | %-8s\n",
                $partie['date'],
                $partie['joueur'],
                $partie['cpu'],
                $partie['resultat']
            );
        }
    }

    } elseif ($choix == "3") {
        //reload du fichier
        $filePath = __DIR__ . '/Data.json';

        $json = file_get_contents($filePath);
        $data = json_decode($json, true);

        //affichage des statistiques

        afficherStatistiques($data);
        
        //fin de partie
    } elseif ($choix == "4") {
        echo ">>> Merci d'avoir joué ! À bientôt.\n";
        break;

    } else {
        echo ">>> Choix invalide. Veuillez réessayer.\n";
    }

    echo "\n";
}
