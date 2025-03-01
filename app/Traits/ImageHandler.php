<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait ImageHandler
{
    private function borraImagen($imagen)
    {
        if (!$imagen) {
            return;
        }
        $relativePath = str_replace('storage/', '', $imagen);

        if (Storage::disk('public')->exists($relativePath)) {
           Storage::disk('public')->delete($relativePath);
          //  dd("existe " . $relativePath);
        }
    }
}
