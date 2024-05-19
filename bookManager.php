<?php
include_once 'storageManager.php';

// Charger les livres depuis le fichier JSON
function createBook($title, $description, $inStock) {
    $books = loadBooks();
    $maxId = 0;

    // Parcourir les clés pour trouver le plus grand ID
    foreach ($books as $key => $value) {
        $idNumber = intval(str_replace('book_', '', $key));  // Extrait le numéro après 'book_'
        if ($idNumber > $maxId) {
            $maxId = $idNumber;
        }
    }

    // L'ID suivant est le maximum actuel + 1
    $newId = 'book_' . ($maxId + 1);

    // Ajouter le nouveau livre avec l'ID incrémenté
    $books[$newId] = ['titre' => $title, 'description' => $description, 'inStock' => $inStock];

    // Sauvegarder les livres mis à jour
    saveBooks($books);

    return $newId;
}


function modifyBook($id, $title, $description, $inStock) {
    $books = loadBooks();
    if ($title !== '') $books[$id]['titre'] = $title;
    if ($description !== '') $books[$id]['description'] = $description;
    if ($inStock !== null) $books[$id]['inStock'] = $inStock;
    saveBooks($books);
}


function deleteBookByCriterion(&$books, $criterion, $value) {
    if ($criterion === 'id') {
        deleteBookById($books, $value);
    } else {
        deleteBookByOtherCriteria($books, $criterion, $value);
    }
}

function deleteBookById(&$books, $id) {
    if (isset($books[$id])) {
        unset($books[$id]);
        saveBooks($books);
        echo "Livre supprimé avec succès !\n";
    } else {
        echo "Aucun livre trouvé avec l'ID : $id.\n";
    }
}

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
            echo "Aucun livre trouvé avec le critère $criterion '$value'.\n";
            break;
        case 1:
            $id = array_key_first($foundBooks);
            unset($books[$id]);
            saveBooks($books);
            echo "Le livre avec le critère '$value' a été supprimé.\n";
            break;
        default:
            handleMultipleBooksDeletion($books, $foundBooks, $criterion, $value);
            break;
    }
}

function handleMultipleBooksDeletion(&$books, $foundBooks, $criterion, $value) {
    echo "Plusieurs livres trouvés avec le critère $criterion '$value':\n";
    foreach ($foundBooks as $id => $book) {
        echo "ID: $id, Titre: {$book['titre']}, Description: {$book['description']}\n";
    }

    $choice = readline("Voulez-vous supprimer tous les livres trouvés ? (oui/non) : ");
    if (strtolower($choice) === 'oui') {
        foreach ($foundBooks as $id => $book) {
            unset($books[$id]);
        }
        saveBooks($books);
        echo "Tous les livres avec le critère $criterion '$value' ont été supprimés.\n";
    } else {
        $chosenId = readline("Entrez l'ID du livre à supprimer : ");
        if (isset($foundBooks[$chosenId])) {
            unset($books[$chosenId]);
            saveBooks($books);
            echo "Livre avec l'ID $chosenId supprimé.\n";
        } else {
            echo "ID invalide. Aucun livre supprimé.\n";
        }
    }
}



// Fonction pour afficher les livres
function displayBooks($books) {
    // Déterminer la longueur maximale pour chaque colonne pour un alignement propre
    $maxLengthId = $maxLengthTitle = $maxLengthDesc = 0;

    foreach ($books as $id => $book) {
        $maxLengthId = max($maxLengthId, strlen($id));
        $maxLengthTitle = max($maxLengthTitle, strlen($book['titre'] ?? ''));
        $maxLengthDesc = max($maxLengthDesc, strlen($book['description'] ?? ''));
    }

    // En-tête du tableau
    echo "+-" . str_repeat('-', $maxLengthId) . "-+-" . str_repeat('-', $maxLengthTitle) . "-+-" . str_repeat('-', $maxLengthDesc) . "-+-" . str_repeat('-', 10) . "-+\n";
    echo "| " . str_pad("ID", $maxLengthId) . " | " . str_pad("Title", $maxLengthTitle) . " | " . str_pad("Description", $maxLengthDesc) . " | " . str_pad("In Stock", 10) . " |\n";
    echo "+-" . str_repeat('-', $maxLengthId) . "-+-" . str_repeat('-', $maxLengthTitle) . "-+-" . str_repeat('-', $maxLengthDesc) . "-+-" . str_repeat('-', 10) . "-+\n";

    // Afficher les données de chaque livre
    foreach ($books as $id => $book) {
        $title = $book['titre'] ?? '';
        $description = $book['description'] ?? '';
        $inStock = $book['inStock'] ?? 'No';
        echo "| " . str_pad($id, $maxLengthId) . " | " . str_pad($title, $maxLengthTitle) . " | " . str_pad($description, $maxLengthDesc) . " | " . str_pad(($inStock ? 'Yes' : 'No'), 10) . " |\n";
    }

    // Pied de page du tableau
    echo "+-" . str_repeat('-', $maxLengthId) . "-+-" . str_repeat('-', $maxLengthTitle) . "-+-" . str_repeat('-', $maxLengthDesc) . "-+-" . str_repeat('-', 10) . "-+\n";
}

