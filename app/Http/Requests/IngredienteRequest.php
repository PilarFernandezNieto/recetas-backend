<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IngredienteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string'],
            'imagen' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:1024'],
            'descripcion' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio',
            'imagen.image' => 'El archivo debe ser una imagen',
            'imagen.mimes' => 'La imagen debe ser de tipo jpeg, png o jpg',
            'imagen.max' => 'La imagen no debe pesar más de 1MB',
            'descripcion.required' => 'La descripción es obligatoria',
        ];
    }
}
