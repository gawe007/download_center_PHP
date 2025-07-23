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

function getIconByExt($extension){
    $ext = strtolower(trim($extension));

    // List of supported Bootstrap Icon filetypes
    $supportedIcons = [
        'csv', 'doc', 'docx', 'html', 'js', 'json', 'md',
        'pdf', 'php', 'ppt', 'pptx', 'py', 'rb', 'svg',
        'tsx', 'txt', 'xls', 'xlsx', 'xml', 'zip', 'exe', 'mp4', 
        'ai', 'bmp', 'cs', 'css', 'gif', 'heic', 'java', 'jpg',
        'jsx', 'key', 'm4p', 'mdx', 'mov', 'mp3', 'otf', 'png',
        'psd', 'py', 'raw', 'sass', 'scss', 'sh', 'sql', 'tiff',
        'ttf', 'wav', 'woff', 'yml'
    ];

    // Return matching Bootstrap icon class or fallback
    return in_array($ext, $supportedIcons)
        ? "bi-filetype-$ext"
        : "bi-file-earmark-check";

}

