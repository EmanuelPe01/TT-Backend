<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tt_t_Rutina as Rutina;
use App\Models\tt_t_DetalleRutina as DetalleRutina;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class RutinaController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/createRutina",
     *     tags={"Rutinas"},
     *     summary="Crear una rutina",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id_inscripcion", "fecha_rutina", "rondas", "tiempo", "peso", "halterofilia", "ejercicios"},
     *             @OA\Property(property="id_inscripcion", type="integer", example="2"),
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
                'ejercicios.*.cantidad_ejercicio' => 'required|min:1',
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


    /**
     * @OA\Get(
     *     path="/api/showRutinas",
     *     summary="Obtener rutinas por inscripción, fecha inicio, fecha fin y halterofilia",
     *     tags={"Rutinas"},
     *     @OA\Parameter(
     *         name="id_inscripcion",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         ),
     *         description="ID de la inscripción"
     *     ),
     *     @OA\Parameter(
     *         name="fecha_inicio",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="date",
     *             example="2024-05-05"
     *         ),
     *         description="Fecha de inicio en formato YYYY-MM-DD"
     *     ),
     *     @OA\Parameter(
     *         name="fecha_fin",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="date",
     *             example="2024-05-05"
     *         ),
     *         description="Fecha de fin en formato YYYY-MM-DD"
     *     ),
     *     @OA\Parameter(
     *         name="halterofilia",
     *         in="query",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             enum={0, 1},
     *             example=1
     *         ),
     *         description="Indicador de halterofilia (0 para no, 1 para sí)"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Rutinas obtenidas con éxito"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al obtener las rutinas"
     *     )
     * )
     */

    public function showRutinas(Request $request)
    {
        try {
            $id_inscripcion = $request->query('id_inscripcion');
            $fecha_inicio = $request->query('fecha_inicio');
            $fecha_fin = $request->query('fecha_fin');
            $halterofilia = $request->query('halterofilia');
            $halterofilia = filter_var($halterofilia, FILTER_VALIDATE_BOOLEAN);

            $rutinas = Rutina::where('id_inscripcion', $id_inscripcion)
                ->whereBetween('fecha_rutina', [$fecha_inicio, $fecha_fin])
                ->where('halterofilia', $halterofilia)
                ->with('detalleRutina')
                ->get();


            foreach ($rutinas as $rutina) {
                $rutina->fecha_rutina = ucfirst(Carbon::parse($rutina->fecha_rutina)->translatedFormat('l j \\de F Y'));
            }

            return response()->json($rutinas, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    public function deleteRutina($id)
    {
        try {
            $rutina = Rutina::find($id);

            if (!$rutina) {
                return response()->json(['message' => $e->getMessage()], 404);
            }

            $rutina->delete();

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

    public function updateRutina(Request $request, int $id)
    {
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
                'ejercicios.*.cantidad_ejercicio' => 'required|min:1',
                'ejercicios.*.accion' => 'required'
            ]);

            $rutina = Rutina::findOrFail($id);
            $rutina->update($request->only(['fecha_rutina', 'rondas', 'tiempo', 'peso', 'halterofilia']));

            foreach ($request->detalle_rutinas as $detalle) {
                if ($detalle['accion'] === 'agregar') {
                    $nuevoDetalle = new DetalleRutina($detalle);
                    $rutina->detalleRutinas()->save($nuevoDetalle);
                } elseif ($detalle['accion'] === 'modificar') {
                    $detalleExistente = DetalleRutina::findOrFail($detalle['id']);
                    $detalleExistente->update(Arr::except($detalle, ['id', 'accion']));
                } elseif ($detalle['accion'] === 'eliminar') {
                    DetalleRutina::findOrFail($detalle['id'])->delete();
                }
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'recurso no encontrado',
                'detail' => $e
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error general',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
