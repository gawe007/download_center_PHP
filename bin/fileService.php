<?php
// src/FileService.php

class FileService
{
    private $basePath = __DIR__ . '/../files/';

    public function getFilePath(string $fileId): ?string
    {
        // Map fileId to actual file path; in production, check DB
        $path = realpath($this->basePath . basename($fileId));
        if ($path && strpos($path, realpath($this->basePath)) === 0 && is_file($path)) {
            return $path;
        }
        return null;
    }
}
