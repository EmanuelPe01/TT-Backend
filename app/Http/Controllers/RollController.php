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

    /**
     * Almacena la contraseña siempre
     *
     * @OA\get(
     *     path="/api/usersByRole/{rol_id}",
     *     tags={"Roles"},
     *     summary="Retorna todos los usuarios según el rol",
     *     @OA\Parameter(
     *         name="rol_id",
     *         in="path",
     *         description="Id del rol",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Retorna todos los usuarios correspondientes al rol"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Token inválido"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error el servidor"
     *     )
     * )
     */
    public function getUsersByRole(int $rol_id) {
        try {
            $rol = Rol::find($rol_id);

            if (!$rol) {
                return response()->json(['message' => 'El rol no existe'], 404);
            }
            $usuarios = $rol->usuarios()
            ->select('id', 'name', 'firstSurname', 'secondSurname')
            ->whereNotExists(function ($query) {
                $query->select('id')
                    ->from('tt_t_inscripcion')
                    ->whereColumn('tt_t_inscripcion.id_user_cliente', 'tt_t_usuario.id');
            })->get();
            
            return response()->json($usuarios, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error en el servidor',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
