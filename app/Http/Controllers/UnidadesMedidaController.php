<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tt_t_unidadesMedida as UnidadesMedida;

class UnidadesMedidaController extends Controller
{
    /**
         * Se crea una unidad de medida
         *
         * @OA\Post(
         *     path="/api/createUnidadMedida",
         *     tags={"Unidades de medida"},
         *     summary="Creación de una unidad de medida",
         *     @OA\RequestBody(
         *         required=true,
         *         @OA\JsonContent(
         *             @OA\Property(property="unidad_medida", type="string"),
         *         )
         *     ),
         *     @OA\Response(
         *         response=201,
         *         description="Se almacena una unidad de medida"
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
                'unidad_medida' => 'required|unique:TT_T_UnidadesMedida,unidad_medida'
            ]);
    
            $um = UnidadesMedida::create([
                'unidad_medida' => $request->unidad_medida,
            ]);
    
            return response()->json([
                'message' => 'Unidad de medida creada con éxito',
                'unidad de medida' => $um
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
         * Se consultan todas las unidades de medida
         *
         * @OA\Get(
         *     path="/api/allUnidadesMedida",
         *     tags={"Unidades de medida"},
         *     summary="Se obtienen todas las unidades de medida",
         *     @OA\Response(
         *         response=200,
         *         description="Retorna la informacion de todas las unidades de medida"
         *     ),
         *     @OA\Response(
         *         response=500,
         *         description="Error general"
         *     )
         * )
    */
    public function getAll() 
    {
        try {
            $um = UnidadesMedida::all();
            
            return response()->json($um, 200);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => 'Error en la base de datos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
         * Se actualiza una unidad de medida
         *
         * @OA\Put(
         *     path="/api/updateUnidadMedida/{id_unidadMedida}",
         *     tags={"Unidades de medida"},
         *     summary="Actualiza una unidad de medida",
         *  *     @OA\Parameter(
         *         name="id_unidadMedida",
         *         in="path",
         *         description="Id de la Unidad de medida",
         *         required=true,
         *         @OA\Schema(
         *             type="string"
         *         )
         *     ),
         *     @OA\RequestBody(
         *         required=true,
         *         @OA\JsonContent(
         *             @OA\Property(property="unidad_medida", type="string"),
         *         )
         *     ),
         *     @OA\Response(
         *         response=200,
         *         description="Se almacena la unidad de media"
         *     ),
         *      @OA\Response(
         *         response=500,
         *         description="Error en la base de datos"
         *     )
         * )
    */
    public function update(Request $request, $id)
    {
        try {
            $um = UnidadesMedida::find($id);

            if($um) {
                $request->validate([
                    'unidad_medida' => 'required|unique:TT_T_UnidadesMedida,unidad_medida'
                ]);

                $um->unidad_medida = $request->unidad_medida;
                $um->save();

                return response()->json([
                    'message' => 'Registro actualizado'
                ], 200);
            } else {
                return response()->json(404);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => 'Error en la base de datos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Elimina una unidad de medida
     *
     * @OA\delete(
     *     path="/api/deleteUnidadMedida/{id_unidadMedida}",
     *     tags={"Unidades de medida"},
     *     summary="Elimina una unidad de medida",
     *     @OA\Parameter(
     *         name="id_unidadMedida",
     *         in="path",
     *         description="Id de la unidad de medida",
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
    public function destroy($id)
    {
        try {
            $um = UnidadesMedida::find($id);

            if($um) {
                $um->delete();

                return response()->json([
                    'message' => 'Registro eliminado'
                ], 200);
            } else {
                return response()->json(404);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => 'Error en la base de datos',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
