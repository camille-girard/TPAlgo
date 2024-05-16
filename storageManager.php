<?php

function loadBooks() {
    $filePath = 'books.json';
    if (!file_exists($filePath)) {
        return null;
    }

    $json = file_get_contents($filePath);
    $data = json_decode($json, true);

    // Vérifier si le décodage JSON a réussi
    if (json_last_error() !== JSON_ERROR_NONE) {
        return null;
    }

    return $data;
}

function saveBooks($books) {
    $filePath = 'books.json';
    $json = json_encode($books, JSON_PRETTY_PRINT);

    // Vérifier si l'encodage JSON a réussi
    if ($json === false) {
        return false;
    }

    file_put_contents($filePath, $json);
    return true;
}
?>
