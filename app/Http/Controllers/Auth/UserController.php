<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    
    
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'firstSurname' => 'required',
                'secondSurname' => 'required',
                'telephone' => 'required|unique:users',
                'email' => 'required|email|unique:users',
                'password' => 'required'
            ]);
        
            $user = User::create([
                'name' => $request->name,
                'firstSurname' => $request->firstSurname,
                'secondSurname' => $request->secondSurname,
                'telephone' => $request->telephone,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);
        
            return response()->json([
                'message' => 'Usuario registrado correctamente',
                'usuario' => $user
            ], 201);
        
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 400);
        
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => 'Error en la base de datos',
                'error' => $e->getMessage()
            ], 500);
        }
        
    }

    
    public function show($id)
    {
        //
    }

    
    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
