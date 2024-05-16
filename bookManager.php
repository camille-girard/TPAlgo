<?php
include_once 'storageManager.php';
function createBook($title, $description, $inStock) {
    $books = loadBooks();
    $id = uniqid("book_", true);
    $books[$id] = ['titre' => $title, 'description' => $description, 'inStock' => $inStock];
    saveBooks($books);
    return $id;
}

function modifyBook($id, $title, $description, $inStock) {
    $books = loadBooks();
    if (!isset($books[$id])) {
        echo "Aucun livre n'a été trouvé avec cet id: $id.\n";
        return;
    }
    if ($title !== '') $books[$id]['titre'] = $title;
    if ($description !== '') $books[$id]['description'] = $description;
    if ($inStock !== null) $books[$id]['inStock'] = $inStock;
    saveBooks($books);
    echo "Le livre est bien modifié.\n";
}

function deleteBook($title) {
    $books = loadBooks();
    $found = false;
    foreach ($books as $id => $book) {
        if ($book['titre'] == $title) {
            unset($books[$id]);
            $found = true;
        }
    }
    if ($found) {
        saveBooks($books);
        echo "Le livre '$title' est supprimé.\n";
    } else {
        echo "Il n'y aucun livre '$title'.\n";
    }
}


function displayBooks() {
    $books = loadBooks();
    foreach ($books as $id => $book) {
        echo "ID: $id, Titre: {$book['titre']}, Description: {$book['description']}, En Stock: " . ($book['inStock'] ? 'Oui' : 'Non') . "\n";
    }
}
?>
