<?php
include_once 'storageManager.php';
include_once 'historyManager.php';

function sortBooks($books, $column, $order) {
    // Vérifier si le tableau de livres n'est pas vide
    if (empty($books)) {
        return [];
    }

    usort($books, function ($a, $b) use ($column, $order) {
        // Vérifier si les clés existent dans les éléments
        $valA = $a[$column] ?? '';
        $valB = $b[$column] ?? '';

        if ($order == 'ASC') {
            return $valA <=> $valB;
        } else {
            return $valB <=> $valA;
        }
    });

    return $books;
}
?>
