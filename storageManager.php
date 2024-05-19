<?php

//fonction pour charger les livres
function loadBooks() {
    $filePath = 'books.json';
    if (!file_exists($filePath)) { //vérifier si le fichier existe
        return null;
    }

    $json = file_get_contents($filePath); //récupérer le contenu du fichier
    $data = json_decode($json, true); //décoder le contenu JSON en tableau associatif

    // Vérifier si le décodage JSON a réussi
    if (json_last_error() !== JSON_ERROR_NONE) {
        return null;
    }

    if (empty($data)) {
        return [];
    }

    return $data;
}

//fonction pour sauvegarder les livres
function saveBooks($books) {
    $filePath = 'books.json';
    $json = json_encode($books, JSON_PRETTY_PRINT); //encoder le tableau en JSON

    // Vérifier si l'encodage JSON a réussi
    if ($json === false) {
        return false;
    }

    file_put_contents($filePath, $json); //écrire le contenu JSON dans le fichier
    return true;
}

