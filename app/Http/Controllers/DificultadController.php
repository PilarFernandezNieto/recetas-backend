<?php

namespace App\Http\Controllers;

use App\Models\Dificultad;
use Illuminate\Http\Request;
use App\Http\Resources\DificultadCollection;

class DificultadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new DificultadCollection(Dificultad::all());
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
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
