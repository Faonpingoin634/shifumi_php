<?php

function afficherStatistiques($data) {
    echo ">>> Statistiques :\n\n";

    $nbParties = $data['parties_jouees'] ?? 0;
    echo "- Nombre de parties jouées : $nbParties\n";

    $victoires = $data['victoires'] ?? 0;
    echo "- Nombre de victoires : $victoires\n";

    $tauxVictoire = $nbParties > 0 ? round(($victoires / $nbParties) * 100, 2) : 0;
    echo "- Taux de victoire : $tauxVictoire%\n";

    $mains = $data['mains_gagnantes'] ?? ['pierre' => 0, 'feuille' => 0, 'ciseaux' => 0];
    arsort($mains);
    $meilleureMain = array_key_first($mains);
    echo "- Main la plus gagnante : $meilleureMain\n";

    echo "- Taux de victoire par main :\n";
    foreach ($mains as $main => $nb) {
        $totalMain = $data['utilisation_mains'][$main] ?? 1;
        $taux = round(($nb / $totalMain) * 100, 2);
        echo "  * $main : $taux% de victoire\n";
    }

    $temps = $data['temps_jeu'] ?? 0;
    $minutes = floor($temps / 60);
    $secondes = $temps % 60;
    echo "- Temps total passé à jouer : {$minutes}min {$secondes}s\n";

    echo "\nAppuie sur Entrée pour revenir au menu principal...";
    readline();
}
