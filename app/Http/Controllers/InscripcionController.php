<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tt_t_inscripcion as Inscripcion;
use Carbon\Carbon;

class InscripcionController extends Controller
{
    /**
         * Se almacena un rol para usuario
         *
         * @OA\Post(
         *     path="/api/generateInscription",
         *     tags={"Inscripciones"},
         *     summary="Genera una inscripción",
         *     @OA\RequestBody(
         *         required=true,
         *         @OA\JsonContent(
         *             @OA\Property(property="id_user_cliente", type="integer"),
         *             @OA\Property(property="id_user_entrenador", type="integer"),
         *             @OA\Property(property="fecha_inicio", type="string"),
         *             @OA\Property(property="peso_maximo", type="string"),
         *             @OA\Property(property="estado", type="boolean"),
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
        try {
            $request -> validate([
                'id_user_cliente' => 'required|exists:tt_t_usuario,id|es_cliente',
                'id_user_entrenador' => 'required|exists:tt_t_usuario,id|es_entrenador',
                'fecha_inicio' => 'required|date_format:Y-m-d',
                'peso_maximo' => 'required|regex:/^\d+(\.\d{1,2})?$/|gt:0',
                'estado' => 'required'
            ]);

            $inscripcion = Inscripcion::create([
                'id_user_cliente' => $request->id_user_cliente,
                'id_user_entrenador' => $request->id_user_entrenador,
                'fecha_inicio' => $request->fecha_inicio,
                'peso_maximo' => $request->peso_maximo,
                'estado' => $request->estado
            ]);

            return response()->json([
                'message' => 'Inscripción generada correctamente',
                'detail' => $inscripcion
            ], 201);
        }  catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error general',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
         * Se obtienen todos las inscripciones
         *
         * @OA\Get(
         *     path="/api/allInscriptions",
         *     tags={"Inscripciones"},
         *     summary="Se obtienen todos las inscripciones",
         *     @OA\Response(
         *         response=200,
         *         description="Retorna la informacion de todas las inscripciones"
         *     ),
         *     @OA\Response(
         *         response="default",
         *         description="Error"
         *     )
         * )
    */
    public function getAllInscriptions()
    {
        $inscripciones = Inscripcion::with(['cliente' => function ($query) {
            $query->select('id', 'name', 'firstSurname', 'secondSurname');
        }, 'entrenador' => function ($query) {
            $query->select('id', 'name', 'firstSurname', 'secondSurname');
        }])->get()->map(function ($inscripcion) {
            $inscripcion->estado = $inscripcion->estado === 1 ? 'Activo' : 'Inactivo';
            $inscripcion->fecha_inicio = new Carbon($inscripcion->fecha_inicio);
            $inscripcion->fecha_inicio = $inscripcion->fecha_inicio->format('d/m/Y');
            return $inscripcion;
        });

        return response()->json([
            'inscripciones' => $inscripciones
        ], 200);
    }
}
