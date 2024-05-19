<?php
include_once 'storageManager.php';
include_once 'historyManager.php';

function mergeSort(&$array, $column, $order = 'ASC') {
    if (count($array) < 2) {
        return;
    }
    $mid = floor(count($array) / 2);
    $left = array_slice($array, 0, $mid);
    $right = array_slice($array, $mid);

    mergeSort($left, $column, $order);
    mergeSort($right, $column, $order);

    $i = 0;
    $j = 0;
    $k = 0;

    while ($i < count($left) && $j < count($right)) {
        if (($order === 'ASC' && $left[$i][$column] <= $right[$j][$column]) ||
            ($order === 'DESC' && $left[$i][$column] > $right[$j][$column])) {
            $array[$k] = $left[$i];
            $i++;
        } else {
            $array[$k] = $right[$j];
            $j++;
        }
        $k++;
    }

    while ($i < count($left)) {
        $array[$k] = $left[$i];
        $i++;
        $k++;
    }

    while ($j < count($right)) {
        $array[$k] = $right[$j];
        $j++;
        $k++;
    }
}


function binarySearch($array, $column, $value) {
    $low = 0;
    $high = count($array) - 1;

    while ($low <= $high) {
        $mid = floor(($low + $high) / 2);

        if ($array[$mid][$column] == $value) {
            return $mid; // Returns the index of the found element
        }

        if ($array[$mid][$column] < $value) {
            $low = $mid + 1;
        } else {
            $high = $mid - 1;
        }
    }

    return -1; // Returns -1 if the element is not found
}

