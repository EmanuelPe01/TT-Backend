<?php

namespace App\Http\Controllers;

use App\Models\tt_t_rol as Rol;
use Illuminate\Http\Request;

class RollController extends Controller
{
    /**
         * Se almacena un rol para usuario
         *
         * @OA\Post(
         *     path="/api/createRole",
         *     tags={"Roles"},
         *     summary="Creación de un rol",
         *     @OA\RequestBody(
         *         required=true,
         *         @OA\JsonContent(
         *             @OA\Property(property="rol_name", type="string"),
         *         )
         *     ),
         *     @OA\Response(
         *         response=200,
         *         description="Se almacena un role."
         *     ),
         *      @OA\Response(
         *         response=400,
         *         description="Duplicidad de valores."
         *     ),
         *      @OA\Response(
         *         response=500,
         *         description="Error en la base de datos"
         *     )
         * )
    */

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

     /**
         * Se obtienen todos los roles disponibles
         *
         * @OA\Get(
         *     path="/api/allRoles",
         *     tags={"Roles"},
         *     summary="Se obtienen todos los roles",
         *     @OA\Response(
         *         response=200,
         *         description="Retorna la informacion de todos los roles"
         *     ),
         *     @OA\Response(
         *         response="default",
         *         description="Error"
         *     )
         * )
    */
    public function getAllRoles()
    {
        $roles = Rol::with('usuarios')->get();
        return response()->json([
            'roles' => $roles
        ], 200);
    }

}
