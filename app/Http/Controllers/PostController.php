<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tt_t_Inscripcion as Inscripcion;
use App\Models\TT_T_Post as Post;

class PostController extends Controller
{

    public function createPost(Request $request)
    {
        try {
            $idTipoUser = $request->user()->rol->id;
            $request->validate([
                'id_inscripcion' => 'required|exists:tt_t_inscripcion,id',
                'mensaje' => 'required|string',
            ]);

            $tipoUser = $idTipoUser == '1' ? 'cliente' : 'entrenador';

            Post::create([
                'id_inscripcion' => $request->id_inscripcion,
                'mensaje' => $request->mensaje,
                'tipo_usuario' => $tipoUser,
            ]);

            return response()->json(201);
        }  catch (\Exception $e) {
            return response()->json($e, 500);
        }
    }


    public function showPostByInscription($id)
    {
        try {
            $post = Post::where('id_inscripcion', $id)->get();

            return response()->json($post, 200);
        } catch (\Exception $e) {
            return response()->json($e, 500);
        }
    }

}
