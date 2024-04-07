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
    public function store(Request $request)
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
