<?php
include_once 'storageManager.php';
include_once 'functions.php';

// Charge les livres depuis le fichier JSON
function createBook(&$books, $title, $description, $inStock) {
    $maxId = 0;

    // Parcours les clés pour trouver le plus grand ID
    foreach ($books as $key => $value) {
        if ($key > $maxId) {
            $maxId = $key;
        }
    }

    // L'ID suivant est le maximum actuel + 1
    $newId = $maxId + 1;

    // Ajoute le nouveau livre avec l'ID incrémenté
    $books[$newId] = ['titre' => $title, 'description' => $description, 'inStock' => $inStock];

    // Sauvegarde les livres mis à jour
    saveBooks($books);

    return $newId;
}


function modifyBook(&$books, $id, $title, $description, $inStock) {
    if ($title !== '') $books[$id]['titre'] = $title;
    if ($description !== '') $books[$id]['description'] = $description;
    if ($inStock !== null) $books[$id]['inStock'] = $inStock;
    saveBooks($books);
}

// Supprime un livre en fonction du critère et de la valeur fournie
function deleteBookByCriterion(&$books, $criterion, $value) {
    if ($criterion === 'id') {
        deleteBookById($books, $value);
    } else {
        deleteBookByOtherCriteria($books, $criterion, $value);
    }
}

// Supprime un livre par ID
function deleteBookById(&$books, $id) {
    if (isset($books[$id])) {
        unset($books[$id]);
        saveBooks($books);
        success("Livre supprimé avec succès !");
    } else {
        error("Aucun livre trouvé avec l'ID : $id.");
    }
}

// Supprime un livre par un autre critère que l'ID
function deleteBookByOtherCriteria(&$books, $criterion, $value) {
    $isInStock = ($criterion === 'inStock' && $value === 'oui');
    $foundBooks = array_filter($books, function ($book) use ($criterion, $value, $isInStock) {
        if ($criterion === 'inStock') {
            return ($isInStock ? $book[$criterion] === true : $book[$criterion] === false);
        } else {
            return $book[$criterion] === $value;
        }
    });


    switch (count($foundBooks)) {
        case 0:
            error("Aucun livre trouvé avec le critère $criterion '$value'.");
            break;
        case 1:
            $id = array_key_first($foundBooks);
            unset($books[$id]);
            saveBooks($books);
            success("Le livre avec le critère '$value' a été supprimé.");
            break;
        default:
            handleMultipleBooksDeletion($books, $foundBooks, $criterion, $value);
            break;
    }
}

// Gère la suppression de plusieurs livres trouvés avec le même critère
function handleMultipleBooksDeletion(&$books, $foundBooks, $criterion, $value) {
    echo "Plusieurs livres trouvés avec le critère $criterion '$value':\n";
    echo "\n";
    foreach ($foundBooks as $id => $book) {
        echo "ID: $id, Titre: {$book['titre']}, Description: {$book['description']}\n";
        echo "\n";
    }

    $choice = readline("Voulez-vous supprimer tous les livres trouvés ? (oui/non) : ");
    if (strtolower($choice) === 'oui') {
        foreach ($foundBooks as $id => $book) {
            unset($books[$id]);
        }
        saveBooks($books);
        success("Tous les livres avec le critère $criterion '$value' ont été supprimés.");
    } else {
        $chosenId = readline("Entrez l'ID du livre à supprimer : ");
        if (isset($foundBooks[$chosenId])) {
            unset($books[$chosenId]);
            saveBooks($books);
            success("Livre avec l'ID $chosenId supprimé.");
        } else {
            error("ID invalide. Aucun livre supprimé.");
        }
    }
}



// Affiche les livres
function displayBooks($books) {
    // Détermine la longueur maximale pour chaque colonne pour un alignement propre
    $maxLengthId = 2;
    $maxLengthTitle = 5;
    $maxLengthDesc = 11;
    $maxLengthStock = 8;

    foreach ($books as $id => $book) {
        $maxLengthId = max($maxLengthId, strlen($id));
        $maxLengthTitle = max($maxLengthTitle, strlen($book['titre'] ?? ''));
        $maxLengthDesc = max($maxLengthDesc, strlen($book['description'] ?? ''));
    }

    // En-tête du tableau
    echo "+-" . str_repeat('-', $maxLengthId) . "-+-" . str_repeat('-', $maxLengthTitle) . "-+-" . str_repeat('-', $maxLengthDesc) . "-+-" . str_repeat('-', $maxLengthStock) . "-+\n";
    echo "| " . str_pad("ID", $maxLengthId) . " | " . str_pad("Title", $maxLengthTitle) . " | " . str_pad("Description", $maxLengthDesc) . " | " . str_pad("In Stock", $maxLengthStock) . " |\n";
    echo "+-" . str_repeat('-', $maxLengthId) . "-+-" . str_repeat('-', $maxLengthTitle) . "-+-" . str_repeat('-', $maxLengthDesc) . "-+-" . str_repeat('-', $maxLengthStock) . "-+\n";

    // Affiche les données de chaque livre
    foreach ($books as $id => $book) {
        $title = $book['titre'] ?? '';
        $description = $book['description'] ?? '';
        $inStock = $book['inStock'] ?? 'Non';
        echo "| " . str_pad($id, $maxLengthId) . " | " . str_pad($title, $maxLengthTitle) . " | " . str_pad($description, $maxLengthDesc) . " | " . str_pad(($inStock ? 'Oui' : 'Non'), $maxLengthStock) . " |\n";
    }

    // Pied de page du tableau
    echo "+-" . str_repeat('-', $maxLengthId) . "-+-" . str_repeat('-', $maxLengthTitle) . "-+-" . str_repeat('-', $maxLengthDesc) . "-+-" . str_repeat('-', $maxLengthStock) . "-+\n";
}


function searchAndDisplayBooks($books, $searchTerm) {
    $results = array_filter($books, function($book) use ($searchTerm) {
        return stripos($book['titre'], $searchTerm) !== false ||
               stripos($book['description'], $searchTerm) !== false;
    });

    if (count($results) > 1) {
        getUserChoice($results);
    } elseif (count($results) === 1) {
        displayBooks($results);
    } else {
        error("Aucun livre trouvé pour ce terme de recherche.");
    }
}

// Demande à l'utilisateur de choisir d'afficher un ou plusieurs livres si la recherche en trouve
function getUserChoice($books) {
    echo "Plusieurs livres correspondent à votre recherche. \n";
    echo "Voulez-vous afficher tous les livres? (oui/non): ";
    $choice = strtolower(trim(readline()));

    if ($choice === 'oui') {
        displayBooks($books);
    } else {
        echo "Entrez l'ID du livre à afficher: ";
        $id = trim(readline());
        if (isset($books[$id])) {
            displayBooks([$id => $books[$id]]);
        } else {
            error("ID invalide. Aucun livre affiché.");
        }
    }
}
