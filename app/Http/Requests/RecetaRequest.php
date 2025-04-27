<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class RecetaRequest extends FormRequest
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
            'nombre' => ['required', 'string', Rule::unique('recetas')->ignore($this->receta)],
            'imagen' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:1024'],
            'intro' => 'nullable|string',
            'instrucciones' => 'required|string',
            'origen' => 'nullable|string',
            'tiempo' => 'nullable|integer',
            'comensales' => 'nullable|integer',
            'dificultad_id' => 'required|integer|exists:dificultades,id',
            'categoria_id' => 'required|integer|exists:categorias,id',
            'ingredientes' => 'required|array',
            'ingredientes.*.ingrediente_id' => 'required|integer|exists:ingredientes,id',
            'ingredientes.*.cantidad' => 'nullable|string',
            'ingredientes.*.unidad' => 'nullable|string'
        ];
    }
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre de la receta es obligatorio.',
            'nombre.string' => 'El nombre de la receta debe ser un texto.',
            'nombre.unique' => 'Ya existe una receta con este nombre.',

            'imagen.image' => 'El archivo debe ser una imagen.',
            'imagen.mimes' => 'La imagen debe ser de tipo JPEG, PNG o JPG.',
            'imagen.max' => 'La imagen no debe superar 1MB de tamaño.',

            'instrucciones.required' => 'Las instrucciones de la receta son obligatorias.',
            'instrucciones.string' => 'Las instrucciones deben ser un texto válido.',
            'intro.string' => 'La intro debe ser un texto válido.',

            'origen.string' => 'El origen debe ser un texto válido.',

            'tiempo.integer' => 'El tiempo de preparación debe ser un número entero.',

            'comensales.integer' => 'La cantidad de comensales debe ser un número entero.',

            'dificultad_id.required' => 'Debes seleccionar una dificultad.',
            'dificultad_id.integer' => 'El valor de dificultad debe ser un número entero.',
            'dificultad_id.exists' => 'La dificultad seleccionada no es válida.',

            'categoria_id.required' => 'Debes seleccionar una categoría.',
            'categoria_id.integer' => 'El valor de categoría debe ser un número entero.',
            'categoria_id.exists' => 'La categoría seleccionada no es válida.',

            'ingredientes.required' => 'Debes agregar al menos un ingrediente.',
            'ingredientes.array' => 'Los ingredientes deben enviarse en formato de lista.',

            'ingredientes.*.ingrediente_id.required' => 'Cada ingrediente debe tener un ID.',
            'ingredientes.*.ingrediente_id.integer' => 'El ID del ingrediente debe ser un número entero.',
            'ingredientes.*.ingrediente_id.exists' => 'Uno o más ingredientes seleccionados no existen.',
        ];
    }
}
