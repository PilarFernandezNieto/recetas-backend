<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait ImageHandler
{
    private function borraImagen($imagen)
    {
        $relativePath = str_replace(asset('storage') . '/', '', $imagen);

        if (!$imagen) {
            return;
        }
        if (Storage::disk('public')->exists($relativePath)) {
            Storage::disk('public')->delete($relativePath);
        }
    }
}
