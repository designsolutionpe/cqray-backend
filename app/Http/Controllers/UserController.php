<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;
use App\Models\User;
use App\Models\Persona;

class UserController extends Controller
{
    //
    public function index()
    {
        return response()->json(User::with('persona')->get(), 200);
    }

    public function show($id)
    {
        $user = User::with('persona')->find($id);
        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }
        return response()->json($user, 200);
    }

    public function login(Request $request)
    {
        try {
            // Validar los datos de entrada
            $validatedData = $request->validate([
                'login' => 'required|string',       // Puede ser login o email
                'password' => 'required|string',    // Validar que la contraseña esté presente
            ]);
    
            // Intentar obtener al usuario por su login (puedes hacerlo por login o por email)
            $user = User::where('login', $validatedData['login'])
                        ->orWhere('email', $validatedData['login']) // Permitir login con email o login
                        ->first();
    
            // Verificar si el usuario existe y si la contraseña coincide
            if (!$user || !Hash::check($validatedData['password'], $user->password)) {
                // Si no existe el usuario o la contraseña no es correcta
                return response()->json([
                    'message' => 'Credenciales incorrectas'
                ], 401);  // Código de error 401 - Unauthorized
            }

            $token = $user->createToken('YourAppName')->plainTextToken;
    
            // Obtener solo algunos campos de la persona relacionada
            $userData = $user->load([
                'persona:id,tipo_documento,numero_documento,apellido,nombre,email,foto', // Cargar solo los campos específicos
            ]);
    
            // Concatenar el nombre completo
            $userData->persona->nombreCompleto = $userData->persona->apellido . ' ' . $userData->persona->nombre;
    
            // Aquí puedes devolver los datos del usuario y los datos de persona relacionados
            return response()->json([
                'message' => 'Login exitoso',
                'data' => $userData, // Devolver el usuario con la información de persona
                'token' => $token
            ], 200);  // Código de éxito 200 - OK
        } catch (\Exception $e) {
            // Captura errores generales
            return response()->json([
                'message' => 'Error al iniciar sesión',
                'error' => $e->getMessage()
            ], 500);  // Código de error 500 - Internal Server Error
        }
    }
    
    public function store(Request $request)
    {
        try {
            // Validación de los datos de la solicitud
            $validatedData = $request->validate([
                'login' => 'required|string|max:255|unique:users',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
                'rol' => 'required|in:Superadministrador,Administrador,Quiropractico,Paciente',
                'id_sede' => 'nullable|exists:sedes,id', // Verificar que el id_sede exista en la tabla sedes
                'id_persona' => 'nullable|exists:personas,id', // Verificar que el id_persona exista en la tabla personas
            ]);
    
            // Crear el usuario con los datos validados
            $user = User::create([
                'login' => $validatedData['login'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']), // Encriptar la contraseña
                'rol' => $validatedData['rol'],
                'id_sede' => $validatedData['id_sede'] ?? null, // Si no se pasa id_sede, se asigna null
                'id_persona' => $validatedData['id_persona'] ?? null, // Si no se pasa id_persona, se asigna null
            ]);
    
            return response()->json([
                'message' => 'Usuario creado con éxito',
                'data' => $user
            ], 201);
    
        } catch (QueryException $e) {
            // Captura errores de base de datos (por ejemplo, errores de integridad de clave foránea)
            return response()->json([
                'message' => 'Error al guardar el usuario',
                'error' => $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            // Captura errores generales
            return response()->json([
                'message' => 'Algo salió mal',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, User $user)
    {
        try {
            // Validación de los datos de la solicitud
            $validatedData = $request->validate([
                'login' => 'required|string|max:255|unique:users,login,' . $user->id, // Ignorar la validación para el login del usuario actual
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id, // Ignorar la validación para el email del usuario actual
                'password' => 'nullable|string|min:6', // La contraseña es opcional al actualizar
                'rol' => 'required|in:Superadministrador,Administrador,Quiropractico,Paciente',
                'id_sede' => 'nullable|exists:sedes,id', // Verificar que el id_sede exista en la tabla sedes
                'id_persona' => 'nullable|exists:personas,id', // Verificar que el id_persona exista en la tabla personas
            ]);
    
            // Si se proporciona una nueva contraseña, encriptarla
            if ($request->filled('password')) {
                $validatedData['password'] = Hash::make($validatedData['password']);
            } else {
                unset($validatedData['password']); // Si no se proporciona contraseña, no actualizarla
            }
    
            // Actualizar el usuario con los datos validados
            $user->update($validatedData);
    
            // Devolver la respuesta de éxito con el usuario actualizado
            return response()->json([
                'message' => 'Usuario actualizado con éxito',
                'data' => $user
            ], 200);
    
        } catch (QueryException $e) {
            // Captura errores de base de datos (por ejemplo, errores de integridad de clave foránea)
            return response()->json([
                'message' => 'Error al actualizar el usuario',
                'error' => $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            // Captura errores generales
            return response()->json([
                'message' => 'Algo salió mal',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateUserAndPersona(Request $request, User $user)
    {
        DB::beginTransaction();
        try {
            $validatedUser = Validator::make($request->all(), [
                'login' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('users', 'login')->ignore($user->id),
                ],
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    Rule::unique('users', 'email')->ignore($user->id),
                ],
                'password' => 'nullable|string|min:6',
                'rol' => 'required|in:Superadministrador,Administrador,Quiropractico,Paciente',
                'id_sede' => 'nullable|exists:sedes,id',
            ])->validate();
    
            // Si viene contraseña, encriptarla; si no viene, remover
            if ($request->filled('password')) {
                $validatedUser['password'] = Hash::make($validatedUser['password']);
            } else {
                unset($validatedUser['password']);
            }
    
            $user->update($validatedUser);
    
            $validatedPersona = Validator::make($request->all(), [
                'nombre' => 'required|string|max:255',
                'apellido' => 'required|string|max:255',
                'email' => [
                    'nullable',
                    'email',
                    'max:255',
                    Rule::unique('personas', 'email')->ignore($user->persona->id),
                ],
                'foto' => 'nullable|file|image|max:2048',
            ])->validate();
    
            // Eliminar la imagen anterior si existe
            if ($user->persona->foto) {
                if (Storage::exists($user->persona->foto)) {
                    Storage::delete($user->persona->foto);
                }
                $validatedPersona['foto'] = null;
            }
    
            // Subir y asignar la nueva foto (si existe)
            if ($request->hasFile('foto')) {
                $rutaImagen = $request->file('foto')->store('personas', 'public');
                $validatedPersona['foto'] = $rutaImagen;
            }
    
            $user->persona->update($validatedPersona);
    
            DB::commit();
    
            return response()->json([
                'message' => 'Usuario y persona actualizados con éxito',
                'data' => [
                    'user' => $user,
                    'persona' => $user->persona,
                ],
            ], 200);
    
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al actualizar el usuario/persona',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(User $user)
    {
        try {
            $user->delete();
            // Devolver respuesta de éxito
            return response()->json([
                'message' => 'Usuario eliminado con éxito'
            ], 200);
            
        } catch (QueryException $e) {
            // Captura errores de base de datos (por ejemplo, errores de integridad de clave foránea)
            return response()->json([
                'message' => 'Error al eliminar el usuario',
                'error' => $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            // Captura errores generales
            return response()->json([
                'message' => 'Algo salió mal',
                'error' => $e->getMessage()
            ], 500);
        }
    }


}
