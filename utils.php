<?php
include_once 'storageManager.php';

function sortBooks($books, $column, $order) {
    // Vérifier si le tableau de livres n'est pas vide
    if (empty($books)) {
        return [];
    }

    usort($books, function ($a, $b) use ($column, $order) {
        // Vérifier si les clés existent dans les éléments
        $valA = isset($a[$column]) ? $a[$column] : '';
        $valB = isset($b[$column]) ? $b[$column] : '';

        if ($order == 'ASC') {
            return $valA <=> $valB;
        } else {
            return $valB <=> $valA;
        }
    });

    return $books;
}
?>
