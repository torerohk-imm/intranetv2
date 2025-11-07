<?php
namespace App\Support;

use RuntimeException;

class UploadManager
{
    /**
     * @param array $file Datos del archivo proveniente de $_FILES
     * @param string $directory Directorio relativo dentro de storage/
     * @param array $allowedExtensions Extensiones permitidas en minúsculas
     * @param array $allowedMimeTypes Tipos MIME permitidos
     * @param int $maxSizeBytes Tamaño máximo en bytes
     * @return string Ruta relativa almacenada
     */
    public static function store(
        array $file,
        string $directory,
        array $allowedExtensions,
        array $allowedMimeTypes,
        int $maxSizeBytes
    ): string {
        if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            throw new RuntimeException('Error al cargar el archivo.');
        }

        if (empty($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            throw new RuntimeException('La carga del archivo no es válida.');
        }

        $size = $file['size'] ?? 0;
        if ($size <= 0 || $size > $maxSizeBytes) {
            throw new RuntimeException('El archivo supera el tamaño permitido.');
        }

        $extension = strtolower(pathinfo($file['name'] ?? '', PATHINFO_EXTENSION));
        if (!$extension || !in_array($extension, $allowedExtensions, true)) {
            throw new RuntimeException('Tipo de archivo no permitido.');
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);
        if ($mimeType === false || !in_array($mimeType, $allowedMimeTypes, true)) {
            throw new RuntimeException('El tipo de archivo no coincide con la extensión.');
        }

        $storageDirectory = rtrim(\storage_path($directory), '/');
        if (!is_dir($storageDirectory) && !mkdir($storageDirectory, 0770, true) && !is_dir($storageDirectory)) {
            throw new RuntimeException('No se pudo crear el directorio de almacenamiento.');
        }

        try {
            $filename = bin2hex(random_bytes(16)) . '.' . $extension;
        } catch (\Throwable $exception) {
            throw new RuntimeException('No se pudo generar un nombre de archivo seguro.', 0, $exception);
        }
        $destination = $storageDirectory . '/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            throw new RuntimeException('No se pudo guardar el archivo cargado.');
        }

        return trim($directory, '/') . '/' . $filename;
    }

    public static function delete(string $relativePath): void
    {
        if ($relativePath === '') {
            return;
        }

        if (strpos($relativePath, '/storage/') === 0) {
            $fullPath = \public_path(ltrim($relativePath, '/'));
        } elseif (strpos($relativePath, 'storage/') === 0) {
            $fullPath = \public_path($relativePath);
        } else {
            $fullPath = \storage_path($relativePath);
        }

        if (is_file($fullPath)) {
            @unlink($fullPath);
        }
    }
}
