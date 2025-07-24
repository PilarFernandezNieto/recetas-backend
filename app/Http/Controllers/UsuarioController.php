<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsuarioRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UsuarioCollection;
use App\Http\Resources\UsuarioResource;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new UsuarioCollection(User::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $usuario)
    {
        return new UsuarioResource($usuario);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(UsuarioRequest $request, User  $usuario)
    {
        $datos = $request->validated();

        if($request->has('password')) {
            $datos['password'] = bcrypt($datos['password']);
        } else {
            unset($datos['password']); // If password is not provided, do not update it
        }

        $usuario->update([
            'name' => $datos['name'],
            'email' => $datos['email'],
            'password' => $datos['password'] ?? $usuario->password, // Use existing password if not provided
            'is_admin' => $datos['is_admin'] ?? false,
        ]);
        return [
            "type" => "success",
            "user" => $usuario,
            "message" => "Usuario actualizado correctamente"
        ];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $usuario)
    {
        $usuario->delete();

        return [
            "type" => "success",
            "message" => "Usuario eliminado correctamente"
        ];
    }
}
