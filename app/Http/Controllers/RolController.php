<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use Illuminate\Http\Request;

class RolController extends Controller
{

    public function store(Request $request)
    {
        try 
        {
            $request->validate([
                'rol_name' => 'required|unique:TT_T_Rol,rol_name'
            ]);
    
            $role = Rol::create([
                'rol_name' => $request->rol_name,
            ]);
    
            return response()->json([
                'message' => 'Rol creado con éxito', 
                'role' => $role
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

    public function getAllRoles()
    {
        $roles = Rol::all();
        return response()->json([
            'roles' => $roles
        ], 200);
    }

    public function update(Request $request, Rol $rol)
    {
        //
    }

    public function destroy(Rol $rol)
    {
        //
    }
}
