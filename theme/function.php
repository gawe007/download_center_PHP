<?php
function parseSizeToBytes($size) {
    $unit = strtoupper(substr($size, -1));
    $value = (int) $size;

    switch ($unit) {
        case 'K': return $value * 1024;
        case 'M': return $value * 1024 * 1024;
        case 'G': return $value * 1024 * 1024 * 1024;
        default: return $value;
    }
}

function formatBytesHumanReadable($bytes, $decimals = 2) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];

    if ($bytes == 0) return '0 B';

    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . ' ' . $units[$factor];
}

