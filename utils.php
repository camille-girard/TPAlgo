<?php
include_once 'storageManager.php';

function mergeSort($array, $column, $order = 'ASC') {
    if (count($array) < 2) {
        return $array;
    }
    $mid = intval(count($array) / 2);
    $left = mergeSort(array_slice($array, 0, $mid), $column, $order);
    $right = mergeSort(array_slice($array, $mid), $column, $order);
    return merge($left, $right, $column, $order);
}

function merge($left, $right, $column, $order) {
    $result = [];
    $i = 0;
    $j = 0;

    while ($i < count($left) && $j < count($right)) {
        if (!isset($left[$i][$column]) || !isset($right[$j][$column])) {
            break;
        }
        if ($order === 'ASC' ? $left[$i][$column] <= $right[$j][$column] : $left[$i][$column] >= $right[$j][$column]) {
            $result[] = $left[$i++];
        } else {
            $result[] = $right[$j++];
        }
    }
    while ($i < count($left)) {
        $result[] = $left[$i++];
    }
    while ($j < count($right)) {
        $result[] = $right[$j++];
    }
    return $result;
}
function binarySearch($books, $column, $value) {
    $low = 0;
    $high = count($books) - 1;

    while ($low <= $high) {
        $mid = intval(($low + $high) / 2);
        if ($books[$mid][$column] < $value) {
            $low = $mid + 1;
        } else if ($books[$mid][$column] > $value) {
            $high = $mid - 1;
        } else {
            return $mid;
        }
    }
    return -1;
}
?>
