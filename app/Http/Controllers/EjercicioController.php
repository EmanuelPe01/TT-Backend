<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\tt_t_TipoEjercicio as tipoEjercicio;
use App\Models\tt_t_DetalleEjercicio as detalleEjercicio;

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
         * Consulta todos los tipos de ejercicios
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
                return response()->json(404);
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

    /**
         * Se crea un ejercicio
         * 
         * La demo del ejercicio debe ser un vídeo de Youtube, se valida antes de hacer el registro
         * @OA\Post(
         *     path="/api/createEjercicio",
         *     tags={"Ejercicios"},
         *     summary="Creación de un ejercicio",
         *     @OA\RequestBody(
         *         required=true,
         *         @OA\JsonContent(
         *             @OA\Property(property="id_tipo_ejercicio", type="integer"),
         *             @OA\Property(property="nombre_ejercicio", type="string"),
         *             @OA\Property(property="unidad_medida", type="string"),
         *             @OA\Property(property="demo_ejercicio", type="string"),
         *         )
         *     ),
         *     @OA\Response(
         *         response=200,
         *         description="Se almacena un tipo de ejercicio"
         *     ),
         *      @OA\Response(
         *         response=404,
         *         description="Error de validación."
         *     ),
         *      @OA\Response(
         *         response=500,
         *         description="Error en el servidor"
         *     )
         * )
    */
    public function storeEjercicio(Request $request){
        $pattern = '/^(https?:\/\/)?(www\.)?(youtube\.com\/watch\?v=|youtu\.be\/)([\w-]{11})(?:\?[^\s]*)?$/';
        try {
            $request -> validate([
                'id_tipo_ejercicio' => 'required|exists:tt_t_tipoejercicio,id',
                'nombre_ejercicio' => 'required|unique:tt_t_detalleEjercicio,nombre_ejercicio',
                'unidad_medida' => 'required',
                'demo_ejercicio' => 'required|youtube_url'
            ]);

            if (preg_match($pattern, $request->demo_ejercicio, $matches)){
                $videoId = $matches[4];
                $ejercicio = detalleEjercicio::create([
                    'id_tipo_ejercicio' => $request->id_tipo_ejercicio,
                    'nombre_ejercicio' => $request->nombre_ejercicio,
                    'unidad_medida' => $request->unidad_medida,
                    'demo_ejercicio' => 'https://www.youtube.com/embed/'.$videoId
                ]);
    
                return response()->json([
                    'message' => 'Ejercicio almacenado correctamente',
                    'detail' => $ejercicio
                ], 201);
            }              

            return response()->json([
                'message' => 'No se pudo procesar al solicitud',
            ], 500);
        
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error general',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
         * Se consultan todos los ejercicios
         *
         * @OA\Get(
         *     path="/api/getAllEjercicios",
         *     tags={"Ejercicios"},
         *     summary="Consulta los ejercicios",
         *     @OA\Response(
         *         response=200,
         *         description="Retorna una lista con los ejercicios"
         *     ),
         *      @OA\Response(
         *         response=500,
         *         description="Error en la base de datos"
         *     )
         * )
    */
    public function getAllEjercicios() {
        try{
            $ejercicios = detalleEjercicio::with('tipoEjercicio')->get();

            return response()->json($ejercicios, 200);
        } catch (\Exception $e) {
            return response()->json($e, 500);
        }
    }

    /**
         * Se consulta la información básica de los ejercicios 
         *
         * @OA\Get(
         *     path="/api/getInfoBasicEjercicios",
         *     tags={"Ejercicios"},
         *     summary="Consulta los ejercicios",
         *     @OA\Response(
         *         response=200,
         *         description="Retorna una lista con la información básica de los ejercicios"
         *     ),
         *      @OA\Response(
         *         response=500,
         *         description="Error general"
         *     )
         * )
    */
    public function getInfoBasicEjercicios() {
        try{
            // Obtiene todos los ejercicios y selecciona solo el ID y el nombre
            $ejercicios = detalleEjercicio::select('id', 'nombre_ejercicio', 'id_tipo_ejercicio', 'unidad_medida')->get();
            // Retorna la respuesta JSON con los ejercicios seleccionados
            return response()->json($ejercicios, 200);
        } catch (\Exception $e) {
            return response()->json($e, 500);
        }
    }

     /**
         * Se actualiza la información de un ejercicio
         *
         * @OA\Put(
         *     path="/api/updateEjercicio/{id_ejercicio}",
         *     tags={"Ejercicios"},
         *     summary="Actualiza un ejercicio",
         *  *     @OA\Parameter(
         *         name="id_ejercicio",
         *         in="path",
         *         description="Id del ejercicio",
         *         required=true,
         *         @OA\Schema(
         *             type="string"
         *         )
         *     ),
         *     @OA\RequestBody(
         *         required=true,
         *         @OA\JsonContent(
         *             @OA\Property(property="id_tipo_ejercicio", type="integer"),
         *             @OA\Property(property="nombre_ejercicio", type="string"),
         *             @OA\Property(property="unidad_medida", type="string"),
         *             @OA\Property(property="demo_ejercicio", type="string"),
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
    public function updateEjercicio(Request $request, $id) {
        $pattern = '/^(https?:\/\/)?(www\.)?(youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([\w-]{11})(?:\?[^\s]*)?$/';
        try {
            $ejercicio = detalleEjercicio::find($id);
            $request -> validate([
                'id_tipo_ejercicio' => 'required|exists:tt_t_tipoejercicio,id',
                'nombre_ejercicio' => [Rule::unique('tt_t_detalleEjercicio', 'nombre_ejercicio')->ignore($ejercicio, 'id'),],
                'unidad_medida' => 'required',
                'demo_ejercicio' => 'required|youtube_url'
            ]);

            if($ejercicio){
                if (preg_match($pattern, $request->demo_ejercicio, $matches)){
                    $videoId = 'https://www.youtube.com/embed/'.$matches[4];
    
                    $ejercicio->id_tipo_ejercicio = $request->id_tipo_ejercicio;
                    $ejercicio->nombre_ejercicio = $request->nombre_ejercicio;
                    $ejercicio->unidad_medida = $request->unidad_medida;
                    $ejercicio->demo_ejercicio = $videoId;
                    
                    $ejercicio->save();
                    return response()->json([
                        'message' => 'Actualización exitosa',
                        'detail' => $ejercicio
                    ], 201);
                }              
    
                return response()->json([
                    'message' => 'No se pudo procesar al solicitud',
                ], 500);
            } else {
                return response()->json(404);
            }
        
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error general',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Elimina un ejercicio
     *
     * @OA\delete(
     *     path="/api/deleteEjercicio/{id_ejercicio}",
     *     tags={"Ejercicios"},
     *     summary="Elimina un ejercicio",
     *     @OA\Parameter(
     *         name="id_ejercicio",
     *         in="path",
     *         description="Id del ejercicio",
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
    public function deleteEjercicio($id) {
        try {
            $ejercicio = detalleEjercicio::find($id);

            if(!$ejercicio){
                return response()->json(404);
            }

            $ejercicio->delete();
            
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
