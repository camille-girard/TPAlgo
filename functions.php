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

