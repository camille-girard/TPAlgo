<?php

//Charge les livres
function loadBooks()
{
    $file = 'books.json';
    if (file_exists($file)) { //vérifier si le fichier existe
        $json = file_get_contents($file); //récupérer le contenu du fichier
        $books = json_decode($json, true); //décoder le contenu JSON en tableau associatif
        return is_array($books) ? $books : [];
    }
    return [];
}


//Sauvegarde les livres
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
