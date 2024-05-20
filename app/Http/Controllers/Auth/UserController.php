<?php

namespace App\Http\Controllers\Auth;

use App\Models\tt_t_usuario as User;
use App\Models\tt_t_rol as Rol;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\RecoveryPasswordMail;
use App\Mail\NewUserMail;
use Carbon\Carbon;

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
     * El formato de la fecha es YYYY-MM-DD
     * Modo 1 para Registro por parte del cliente
     * Modo 2 para Registro desde gestión de usuarios
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
     *             @OA\Property(property="fecha_nacimiento", type="string"),
     *             @OA\Property(property="password", type="string"),
     *             @OA\Property(property="modo", type="integer"),
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
                'fecha_nacimiento' => 'required|date_format:Y-m-d',
                'modo' => 'required'
            ]);

            $modo = $request->modo;
            $pass = '';
            $sendEmail = false;

            //Modo 1: El cliente se registra
            //Modo 2: El administrador registra al usuario
            if($modo == 1) {
                $request->validate([
                    'password' => 'required',
                ]);
                $pass = $request->password;
            } else if($modo == 2) {
                $pass = str::random(8);
                $sendEmail = true;
            }

            
            $user = User::create([
                'id_rol' => $request->id_rol,
                'name' => $request->name,
                'firstSurname' => $request->firstSurname,
                'secondSurname' => $request->secondSurname,
                'telephone' => $request->telephone,
                'email' => $request->email,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'password' => bcrypt($pass)
            ]);

            $role = $user->rol->rol_name;

            if($sendEmail) {
                Mail::to($user->email)->send(new NewUserMail($user, $role, $pass));
            }

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
            return response()->json(['message' => $e->getMessage()], 404);
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
    
    public function getAllUsers(Request $request)
    {
        try {
            $currentUser = $request->user();
            $users = User::with('rol')->where('id', '!=', $currentUser->id)->get()->map(function ($user) {
                $user->fecha_nacimiento = new Carbon($user->fecha_nacimiento);
                $user->fecha_nacimiento = $user->fecha_nacimiento->format('d/m/Y');
                return $user;
            });
            return response()->json($users, 200);
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
        $user->rol;
        if($user){
            return response()->json([
                'token'=>str_replace('Bearer ', '', $request->header('authorization')), 
                'user'=>$user],
            200);
        }
    }

     /**
     * Envía un correo electrónico para cambiar la contraseña
     *
     * @OA\post(
     *     path="/api/sendEmailToRestorePassword",
     *     tags={"Users"},
     *     summary="Se genera un token de un uso para cambiar la contraseña del usuario",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Retorna el usuario y el token"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="El usuario no existe"
     *     ),
     *     @OA\Response(
     *         response=418,
     *         description="Error general"
     *     )
     * )
     */

    public function sendEmailToRestorePassword(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
            ]);

            $email = $request->email;
            $user = User::where('email', $email)->first();

            if($user) {
                $token = Str::random(20);
                $user->update(['recuperar_token' => $token]);
                Mail::to($user->email)->send(new RecoveryPasswordMail($user));
                return response()->json([
                    'message' => 'Se ha enviado un correo electrónico con instrucciones para recuperar tu contraseña.',
                    'token' => $token
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

     /**
     * Este endpoint valida un token de recuperación y devuelve una respuesta basada en la validez del token.
     * @OA\Get(
     *     path="/api/validateRecoveryToken/{token}",
     *     tags={"Users"},
     *     summary="Validar token de recuperación",
     *     description="Valida un token de recuperación de contraseña",
     *     operationId="validateRecoveryToken",
     *     @OA\Parameter(
     *         name="token",
     *         in="path",
     *         description="Token de recuperación",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Token válido"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Token inválido"
     *     ),
     *     @OA\Response(
     *         response=418,
     *         description="Error general"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error en la base de datos"
     *     )
     * )
     */

    public function validateRecoveryToken(string $token) {
        try {
            $user = User::where('recuperar_token', $token)->first();
            
            if($user) {
                return response()->json(['message' => 'Token válido'], 200);
            }
            return response()->json(['message' => 'Token inválido'], 404);
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
     * Almacena la contraseña siempre
     *
     * @OA\post(
     *     path="/api/restorePassword/{token}",
     *     tags={"Users"},
     *     summary="Actualiza la contraseña del usuario siempre y cuando exista el token generado",
     *     @OA\Parameter(
     *         name="token",
     *         in="path",
     *         description="Token de recuperación",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),     
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="password", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Retorna el usuario y el token"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Token inválido"
     *     ),
     *     @OA\Response(
     *         response=418,
     *         description="Error general"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error en la base de datos"
     *     )
     * )
     */
    public function restorePassword (Request $request, string $token) {
        try {
            $password = $request -> password;
            $user = User::where('recuperar_token', $token)->first();
            
            if($user) {
                $user->update(['password' => bcrypt($password)]);
                $user->update(['recuperar_token' => ""]);
                return response()->json(['message' => 'Contraseña almacenada correctamente'], 201);
            }
            return response()->json(['message' => 'Token inválido'], 404);
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
     * Se verifica si el usuario tiene inscripción
     *
     * @OA\Get(
     *     path="/api/getDetailInscription",
     *     tags={"Users"},
     *     summary="Verificacion de inscripción",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Retorna el detalle de la inscripción"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No encontrado"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error general"
     *     )
     * )
     */
    public function getDetailInscription (Request $request) {
        try{
            $inscripcion = $request->user()->inscripcion;
            $rol_id = $request->user()->rol;
            if($inscripcion) {
                return response()->json([
                    'detalle' => $inscripcion,
                    'rol' => $rol_id

                ], 200);
            } elseif ($rol_id->id > 1) {
                return response()->json([
                    'detalle' => 'No es cliente',
                    'rol' => $rol_id
                ], 200);
            }

            return response()->json([
                'message' => 'No cuenta con inscrpcion'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error en el servidor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Elimina un usuario
     *
     * @OA\delete(
     *     path="/api/deleteUsuario/{id_usuario}",
     *     tags={"Users"},
     *     summary="Elimina un usuario",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id_usuario",
     *         in="path",
     *         description="Id del usuario",
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
    public function deleteUsuario(Request $request, int $id) {
        try {
            $currentUser = $request->user();
            if($currentUser->rol->id == 3) {
                $user = User::find($id);

                if($user) {
                    $user->delete();
                    return response()->json(200);
                }
                return response()->json(404);
            }

            return response()->json(403);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error en el servidor',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}