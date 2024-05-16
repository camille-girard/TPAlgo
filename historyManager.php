<?php

function loadHistory() {
    $filePath = 'history.json';
    if (!file_exists($filePath)) {
        return [];
    }

    $json = file_get_contents($filePath);
    $data = json_decode($json, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        return [];
    }

    return $data;
}

function saveHistory($history): bool
{
    $filePath = 'history.json';
    $json = json_encode($history, JSON_PRETTY_PRINT);

    if ($json === false) {
        return false;
    }

    file_put_contents($filePath, $json);
    return true;
}

function addHistoryEntry(&$history, $action) {
    $entry = [
        'timestamp' => date('Y-m-d H:i:s'),
        'action' => $action
    ];
    $history[] = $entry;
    saveHistory($history);
}
?>
