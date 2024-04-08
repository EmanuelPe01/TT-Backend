<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tt_t_TipoEjercicio as tipoEjercicio;

class EjercicioController extends Controller
{
    /**
         * Se crea un tipo de ejercicio
         *
         * @OA\Post(
         *     path="/api/createTipoEjercicio",
         *     tags={"Ejercicios"},
         *     summary="Creación de un tipo de ejercicio",
         *     @OA\RequestBody(
         *         required=true,
         *         @OA\JsonContent(
         *             @OA\Property(property="nombre_tipo", type="string"),
         *         )
         *     ),
         *     @OA\Response(
         *         response=200,
         *         description="Se almacena un tipo de ejercicio"
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
    public function storeTipoEjercicio(Request $request)
    {
        try {
            $request->validate([
                'nombre_tipo' => 'required|unique:tt_t_tipoEjercicio,nombre_tipo'
            ]);
            $tipoEjercicio = tipoEjercicio::create([
                'nombre_tipo' => $request->nombre_tipo
            ]);

            return response()->json([
                'message' => 'Tipo de ejercicio registrado correctamente',
                'Tipo de ejercicio' => $tipoEjercicio
            ],200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 400);
        
        } catch (\Exception $e) {
            return response()->json($e, 500);
        }
    }

    /**
         * Se crea un tipo de ejercicio
         *
         * @OA\Get(
         *     path="/api/getAllTipoEjercicio",
         *     tags={"Ejercicios"},
         *     summary="Consulta los tipos de ejercicio",
         *     @OA\Response(
         *         response=200,
         *         description="Retorna una lista con los tipos de ejercicios"
         *     ),
         *      @OA\Response(
         *         response=500,
         *         description="Error en la base de datos"
         *     )
         * )
    */
    public function getAllTipoEjercicio() {
        try{
            $tiposEjercicio = tipoEjercicio::All();

            return response()->json($tiposEjercicio, 200);
        } catch (\Exception $e) {
            return response()->json($e, 500);
        }
    }

    /**
         * Se actualiza un tipo de ejercicio
         *
         * @OA\Put(
         *     path="/api/updateTypeTrining/{id_tipoEjercicio}",
         *     tags={"Ejercicios"},
         *     summary="Actualiza una inscripción",
         *  *     @OA\Parameter(
         *         name="id_tipoEjercicio",
         *         in="path",
         *         description="Id del tipo de ejercicio",
         *         required=true,
         *         @OA\Schema(
         *             type="string"
         *         )
         *     ),
         *     @OA\RequestBody(
         *         required=true,
         *         @OA\JsonContent(
         *             @OA\Property(property="nombre_tipo", type="string"),
         *         )
         *     ),
         *     @OA\Response(
         *         response=200,
         *         description="Se almacena un tipo de ejercicio"
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
    public function updateTipoEjercicio(Request $request, $id)
    {
        try{
            $tipoEjercicio = tipoEjercicio::find($id);
            if($tipoEjercicio) {
                $request->validate([
                    'nombre_tipo' => 'required|unique:tt_t_tipoEjercicio,nombre_tipo'
                ]);
                $tipoEjercicio->nombre_tipo = $request->nombre_tipo;

                $tipoEjercicio->save();
                return response()->json([
                    'message' => 'Actualización exitosa'
                ], 200);
            }
        }  catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 400);
        
        } catch (\Exception $e) {
            return response()->json($e, 500);
        }
    }

    /**
     * Elimina un tipo de ejercicio
     *
     * @OA\delete(
     *     path="/api/deleteTypeTrining/{id_tipoEjercicio}",
     *     tags={"Ejercicios"},
     *     summary="Elimina un tipo de ejercicio",
     *     @OA\Parameter(
     *         name="id_tipoEjercicio",
     *         in="path",
     *         description="Id del tipo de ejercicio",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Registro no encontrado"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error el servidor"
     *     )
     * )
     */    
    public function deleteTipoEjericio(int $id)
    {
        try {
            $tipoEjercicio = tipoEjercicio::find($id);

            if(!$tipoEjercicio){
                return response()->json(['message' => $e->getMessage()], 404);
            }

            $tipoEjercicio->delete();
            
            return response()->json([
                'message' => 'Registro eliminado'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error general',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
