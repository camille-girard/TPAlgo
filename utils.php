<?php
include_once 'storageManager.php';
include_once 'historyManager.php';

function mergeSort(&$array, $column, $order = 'ASC') {
    if (count($array) < 2) {
        return;  // Le tableau est déjà trié s'il contient 0 ou 1 élément
    }
    $mid = floor(count($array) / 2);
    $left = array_slice($array, 0, $mid);
    $right = array_slice($array, $mid);

    mergeSort($left, $column, $order);  // Tri récursif de la partie gauche
    mergeSort($right, $column, $order); // Tri récursif de la partie droite

    $i = 0;
    $j = 0;
    $k = 0;

    while ($i < count($left) && $j < count($right)) {
        // Comparaison tenant compte du type des données
        $leftVal = $left[$i][$column];
        $rightVal = $right[$j][$column];

        // Convertir les valeurs en minuscules si ce sont des chaînes pour une comparaison insensible à la casse
        if (is_string($leftVal) && is_string($rightVal)) {
            $leftVal = strtolower($leftVal);
            $rightVal = strtolower($rightVal);
        }

        if (($order === 'ASC' && $leftVal <= $rightVal) || ($order === 'DESC' && $leftVal > $rightVal)) {
            $array[$k++] = $left[$i++];
        } else {
            $array[$k++] = $right[$j++];
        }
    }

    while ($i < count($left)) {
        $array[$k++] = $left[$i++];
    }

    while ($j < count($right)) {
        $array[$k++] = $right[$j++];
    }
}


function binarySearchAll(&$array, $column, $value) {
    $low = 0;
    $high = count($array) - 1;
    $result = [];

    while ($low <= $high) {
        $mid = floor(($low + $high) / 2);

        if (strtolower($array[$mid][$column]) < strtolower($value)) {
            $low = $mid + 1;
        } elseif (strtolower($array[$mid][$column]) > strtolower($value)) {
            $high = $mid - 1;
        } else {
            // Trouve l'index initial
            array_push($result, $mid);

            // Recherche à gauche
            $left = $mid - 1;
            while ($left >= 0 && strtolower($array[$left][$column]) == strtolower($value)) {
                array_push($result, $left);
                $left--;
            }

            // Recherche à droite
            $right = $mid + 1;
            while ($right < count($array) && strtolower($array[$right][$column]) == strtolower($value)) {
                array_push($result, $right);
                $right++;
            }

            return $result;
        }
    }

    return $result;
}
