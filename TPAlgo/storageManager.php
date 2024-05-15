<?php
function loadBooks() {
    return json_decode(file_get_contents("books.json"), true) ?? [];
}

function saveBooks($books) {
    file_put_contents("books.json", json_encode($books, JSON_PRETTY_PRINT));
}
?>
