<?php
function error($text) {
    echo "\n";
    echo "\033[31m" . $text . "\033[0m\n";
    echo "\n";
}

function success($text) {
    echo "\n";
    echo "\033[32m" . $text . "\033[0m\n";
    echo "\n";
}

