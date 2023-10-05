<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
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

    public function login(Request $request) {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);
        
            $credentials = $request->only('email', 'password');
        
            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                //El plugin PHP Intelephense marca como error la funcion createToken, pero hay que hacer caso omiso
                $token = $user->createToken('token')->plainTextToken;
        
                return response()->json(['token' => $token ,'user'=>$user], 200);
            }

            return response()->json(['message' => 'Credenciales inválidas'], 401);
        } catch (\Illuminate\Database\QueryException $e){
            return response()->json([
                'message' => 'El usuario no está registrado',
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
