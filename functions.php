<?php
//Affiche un message d'erreur en rouge dans le terminal
function error($text) {
    echo "\n";
    echo "\033[31m" . $text . "\033[0m\n";
    echo "\n";
}

//Affiche un message de succès en vert dans le terminal
function success($text) {
    echo "\n";
    echo "\033[32m" . $text . "\033[0m\n";
    echo "\n";
}

//Valide les colonnes fournies par l'utilisateur pour rechercher ou afficher des livres
function validateColumns($columns) {
    // Défini les noms de colonnes valides
    $validColumns = ['titre', 'description', 'inStock'];

    // Vérifier chaque colonne fournie
    foreach ($columns as $column) {
        if (!in_array($column, $validColumns)) {
            // Si la colonne n'est pas valide, afficher un message d'erreur
            error("Erreur: Colonne '$column' non valide. Veuillez saisir un des termes de colonne suivants: " . implode(", ", $validColumns) . ".");
            return false; // Arrête l'exécution si une colonne est invalide
        }
    }
    return true;
}
