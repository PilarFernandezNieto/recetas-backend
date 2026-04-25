<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;

trait ImageHandler
{
    private function borraImagen($imagen)
    {
        if (!$imagen) {
            return;
        }
        $relativePath = ltrim(str_replace('storage/', '', $imagen), '/');

        if (Storage::disk('public')->exists($relativePath)) {
           Storage::disk('public')->delete($relativePath);
        }
    }

    /**
     * Sube una imagen usando el nombre original del archivo sanitizado
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $directory Carpeta destino dentro del disco public
     * @return string Ruta relativa del archivo guardado (ej: "img/mi-foto-a1b2c3.jpg")
     */
    protected function guardarImagen($file, string $directory = 'img'): string
    {
        $baseName  = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension() ?: 'jpg';

        // Transliterar acentos y caracteres Unicode a ASCII
        $baseName = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $baseName) ?: 'imagen';

        // Minúsculas
        $baseName = strtolower($baseName);

        // Reemplazar todo lo que no sea a-z, 0-9 o guion por guion
        $baseName = preg_replace('/[^a-z0-9]+/', '-', $baseName);

        // Eliminar guiones al inicio y al final
        $baseName = trim($baseName, '-');

        // Fallback si queda vacío
        if ($baseName === '') {
            $baseName = 'imagen';
        }

        // Sufijo único de 6 caracteres para evitar colisiones
        $baseName = $baseName . '-' . substr(uniqid(), -6);

        $filename = $baseName . '.' . $extension;

        return $file->storeAs($directory, $filename, 'public');
    }

    /**
     * Convierte una imagen a WebP y la guarda, eliminando el original
     *
     * @param string $imagenPath Ruta relativa de la imagen (ej: "img/filename.png")
     * @return string URL de la imagen WebP convertida
     */
    protected function convertToWebp($imagenPath)
    {
        if (!$imagenPath) {
            return null;
        }

        try {
            // Obtener la ruta completa del archivo
            $fullPath = Storage::disk('public')->path($imagenPath);

            // Crear instancia de ImageManager con GdDriver
            $manager = new ImageManager(new GdDriver());

            // Leer la imagen
            $image = $manager->read($fullPath);

            // Generar nombre WebP (reemplazar extensión)
            $webpPath = preg_replace('/\.[^.]+$/', '.webp', $imagenPath);
            $webpFullPath = Storage::disk('public')->path($webpPath);

            // Guardar como WebP
            $image->toWebp(quality: 80)->save($webpFullPath);

            // Eliminar el archivo original
            Storage::disk('public')->delete($imagenPath);

            // Devolver URL de la imagen WebP
            return Storage::url($webpPath);
        } catch (\Exception) {
            return Storage::url($imagenPath);
        }
    }
}
