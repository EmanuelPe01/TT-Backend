<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tt_t_Rutina as Rutina;
use App\Models\tt_t_DetalleRutina as DetalleRutina;
use Illuminate\Support\Facades\DB;

class RutinaController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/rutinas",
     *     tags={"Rutinas"},
     *     summary="Crear una rutina",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"fecha_rutina", "rondas", "tiempo", "peso", "halterofilia", "ejercicios"},
     *             @OA\Property(property="fecha_rutina", type="string", format="date", example="2024-10-10"),
     *             @OA\Property(property="rondas", type="integer", example=5),
     *             @OA\Property(property="tiempo", type="integer", example=10),
     *             @OA\Property(property="peso", type="number", format="float", example=45),
     *             @OA\Property(property="halterofilia", type="integer", enum={0, 1}, example=0),
     *             @OA\Property(
     *                 property="ejercicios",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     required={"id_ejercicio", "cantidad_ejercicio"},
     *                     @OA\Property(property="id_ejercicio", type="integer", example=5),
     *                     @OA\Property(property="cantidad_ejercicio", type="integer", example=10),
     *                 ),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Rutina creada exitosamente",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Datos de entrada inválidos",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error general",
     *     ),
     * )
     */


    public function storeWood(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'id_inscripcion' => 'required|inscripcion_activa',
                'fecha_rutina' => 'required|date',
                'rondas' => 'required|integer',
                'tiempo' => 'required|integer',
                'peso' => 'numeric',
                'halterofilia' => 'required|boolean',
                'ejercicios' => 'required|array',
                'ejercicios.*.id_ejercicio' => 'required|integer|exists:tt_t_detalleEjercicio,id',
                'ejercicios.*.cantidad_ejercicio' => 'required|integer|min:1',
            ]);

            $rutina = Rutina::create([
                'id_inscripcion' => $request->id_inscripcion,
                'fecha_rutina' => $request->fecha_rutina,
                'rondas' => $request->rondas,
                'tiempo' => $request->tiempo,
                'peso' => $request->peso,
                'halterofilia' => $request->halterofilia,
            ]);

            foreach ($request->ejercicios as $ejercicio) {
                DetalleRutina::create([
                    'id_rutina' => $rutina->id, // Asigna el id de la rutina recién creada
                    'id_ejercicio' => $ejercicio['id_ejercicio'],
                    'cantidad_ejercicio' => $ejercicio['cantidad_ejercicio'],
                ]);
            }
            DB::commit();
            return response()->json([
                'message' => 'Rutina creada correctamente',
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 400);
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Error general',
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
