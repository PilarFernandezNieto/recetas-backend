<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new UserCollection(User::all());
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
    public function show(User $user)
    {
        return new UserResource($user);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User  $user)
    {
        $datos = $request->validated();

        if($request->has('password')) {
            $datos['password'] = bcrypt($datos['password']);
        } else {
            unset($datos['password']); // If password is not provided, do not update it
        }

        $user->update([
            'name' => $datos['name'],
            'email' => $datos['email'],
            'password' => $datos['password'] ?? $user->password, // Use existing password if not provided
            'is_admin' => $datos['is_admin'] ?? false,
        ]);
        return [
            "type" => "success",
            "user" => $user,
            "message" => "Usuario actualizado correctamente"
        ];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return [
            "type" => "success",
            "message" => "Usuario eliminado correctamente"
        ];
    }
}
