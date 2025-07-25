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

    public function verifyIntegrity($file, $hashToCompare): bool{

        $filePath = $this->getFilePath($file);
        if (!is_file($filePath) || !is_readable($filePath)) {
            return false;
        }

        $hash = hash_file("sha256", $filePath);

        return hash_equals($hash, $hashToCompare);
    }

    public function deleteFile($fileName): bool{
        $filePath = $this->getFilePath($fileName);
        if($filePath == null){
            return false;
        }
        if (!is_file($filePath) || !is_readable($filePath)) {
            return false;
        }
        if(!unlink($filePath)){
            return false;
        }
        return true;
    }
}
