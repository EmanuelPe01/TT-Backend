<?php

namespace App\Http\Controllers\Auth;

use App\Models\tt_t_usuario as User;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\RecoveryPasswordMail;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="API de proyecto TT",
 *      description="Se implementan todos los métodos HTTP soportados por el Backend"
 * )
*/

class UserController extends Controller
{
     /**
     * Se crea un usuario
     *
     * @OA\Post(
     *     path="/api/createUser",
     *     tags={"Users"},
     *     summary="Creación de un usuario",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="id_rol", type="integer"),
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="firstSurname", type="string"),
     *             @OA\Property(property="secondSurname", type="string"),
     *             @OA\Property(property="telephone", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="password", type="string"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Se almacena un usuario."
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="Ha ocurrido un error."
     *     )
     * )
     */
    
    public function store(Request $request)
    {
        try {
            $request->validate([
                'id_rol' => 'required|exists:TT_T_Rol,id',
                'name' => 'required',
                'firstSurname' => 'required',
                'secondSurname' => 'required',
                'telephone' => 'unique:TT_T_Usuario',
                'email' => 'required|email|unique:TT_T_Usuario',
                'password' => 'required'
            ]);
        
            $user = User::create([
                'id_rol' => $request->id_rol,
                'name' => $request->name,
                'firstSurname' => $request->firstSurname,
                'secondSurname' => $request->secondSurname,
                'telephone' => $request->telephone,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);

            $user->rol;

            return response()->json([
                'message' => 'Usuario registrado correctamente',
                'usuario' => $user,
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
     * El usuario inicia sesión
     *
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Users"},
     *     summary="Usuario inicia sesión",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="password", type="string"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Retorna un token para el usuario."
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Credenciales inválidas."
     *     ),
     *    @OA\Response(
     *         response=422,
     *         description="El usuario no existe."
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error en el servidor."
     *     )
     * )
     */
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|exists:tt_t_usuario,email',
                'password' => 'required',
            ]);

            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $user->load('rol');
                $token = $user->createToken('token')->plainTextToken;
                
                return response()->json(['token' => $token, 'user' => $user], 200);
            }

            return response()->json(['message' => 'Credenciales inválidas'], 401);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error general', 'detail' => $e->getMessage()], 500);
        }
    }

    /**
     * Se obtien una lista de todos los usuarios registrados
     *
     * @OA\Get(
     *     path="/api/allUsers",
     *     tags={"Users"},
     *     summary="Se obtienen todos los usuarios",
     *     @OA\Response(
     *         response=200,
     *         description="Retorna la informacion de todos los usuarios"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error en la base de datos"
     *     ),
     *     @OA\Response(
     *         response=418,
     *         description="Error general"
     *     )
     * )
    */
    
    public function getAllUsers()
    {
        try {
            $users = User::all();
            return response()->json([
                'users' => $users
            ], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => 'Error en la base de datos',
                'error' => $e->getMessage()
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error general',
                'error' => $e->getMessage()
            ], 418);
        }

    }

    /**
     * Cierre de sesión de usuario
     *
     * @OA\Post(
     *     path="/api/logout",
     *     tags={"Users"},
     *     summary="Cerrar sesión",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Cierre de sesión exitoso."
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="Ha ocurrido un error."
     *     )
     * )
     *
     * @OA\SecurityScheme(
     *     type="http",
     *     securityScheme="bearerAuth",
     *     scheme="bearer",
     *     bearerFormat="JWT"
     * )
     */
    
    public function logout(Request $request)
    {
        $user = $request->user();
    
        if ($user) {
            $user->tokens()->delete();
            return response()->json(['message' => 'Cierre de sesión exitoso'], 200);
        }

        return response()->json(['message' => 'No se pudo encontrar el usuario autenticado'], 500);
    }

    public function destroy($id)
    {
        //
    }
    
    /**
         * Se verifica si el token esta autorizado o no
         *
         * @OA\Get(
         *     path="/api/check-status",
         *     tags={"Users"},
         *     summary="Verificacion de token",
         *     security={{"bearerAuth": {}}},
         *     @OA\Response(
         *         response=200,
         *         description="Retorna el usuario y el token"
         *     ),
         *     @OA\Response(
         *         response=401,
         *         description="No autorizado"
         *     )
         * )
         */
    public function checkStatus(Request $request)
    {
        $user = $request->user();
        if($user){
            return response()->json(['token'=>str_replace('Bearer ', '', $request->header('authorization')), 'user'=>$user],200);
        }
    }

    /**
     * Se envía correo para recuperar contraseña
     *
     * @OA\Post(
     *     path="/api/recoveryPassword",
     *     tags={"Users"},
     *     summary="Se envía correo con instrucciones",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="email", type="string")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Se envió el correo correctamente"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Datos inválidos"
     *     )
     * )
     */

    public function recoveryPassword(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
            ]);

            $email = $request->email;
            $user = User::where('email', $email)->first();

            if($user) {
                $token = Str::random(60);
                $user->update(['recuperar_token' => $token]);
                Mail::to($user->email)->send(new RecoveryPasswordMail($user));
                return response()->json([
                    'message' => 'Se ha enviado un correo electrónico con las instrucciones para recuperar tu contraseña.'
                ], 200);
            }
            
            return response()->json([
                'message' => 'El usuario no existe'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error general',
                'error' => $e->getMessage()
            ], 418);
        }

    }
}