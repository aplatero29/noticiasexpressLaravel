<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\V1\UsuarioRequest;
use App\Http\Resources\V1\EntradaResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\V1\UsuarioResource;
use App\Models\Entrada;

use function PHPUnit\Framework\isNull;

class UsuarioController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return UsuarioResource::collection(User::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UsuarioRequest $request)
    {
        $request->validated();

        $user = Auth::user();

        $user = new User();
        //$user->entradas()->associate($)
        $user->nombre = $request->input('nombre');
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));
        $user->rol = $request->input('rol');

        $res = $user->save();

        if ($res) {
            return response()->json(['message' => 'Usuario creado correctamente'], 201);
        }

        return response()->json(['message' => 'Error al crear usuario'], 500);
    }

    private function upload($image)
    {
        $path_info = pathinfo($image->getClientOriginalName());
        $image_path = 'images/user';

        $rename = uniqid() . '.' . $path_info['extension'];
        $image->move(public_path() . "/$image_path", $rename);
        return "$image_path/$rename";
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(int $idUsuario)
    {
        $user = User::where('id', $idUsuario)->first();
        return new UsuarioResource($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function showByUser(int $idUsuario)
    {
        $user = User::where('id', $idUsuario)->first();
        $entradas = EntradaResource::collection(Entrada::where('user_id', $idUsuario)->orderBy('created_at')->get());
        return [
            'usuario' => $user,
            'entradas' => $entradas,
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        if (empty($id)) { //El $id se usará cuando lo vaya a actualizar un usuario con permisos de administrador
            $user = User::where('id', Auth::id())->first();
        } else {
            $user = User::where('id', $id)->first();
        }


        Validator::make($request->all(), [
            'nombre' => 'string|max:100',
            'password' => 'required|string|min:6',
            'newPassword' => 'required_if:anotherfield,value|string|min:6',
            'confirmNewPassword' => 'string|same:newPassword'
        ])->validate();

        //dd($request, $user, $user->id);

        if (Auth::id() !== $user->id) {
            return response()->json(['message' => 'No tienes suficientes permisos'], 403);
        }
        if (Hash::check($request->input('newPassword'), $user->password)) {
            return response()->json(['message' => 'No tienes suficientes permisos'], 403);
        }

        if (!empty($request->input('nombre'))) {
            $user->nombre = $request->input('nombre');
        }
        if (!empty($request->input('newPassword'))) {
            $user->password = bcrypt($request->input('newPassword'));
        }

        $res = $user->save();

        if ($res) {
            return response()->json(['message' => 'Usuario actualizado correctamente']);
        }

        return response()->json(['message' => 'Error al actualizar usuario'], 500);
    }

    public function updateByAdmin(Request $request, int $idUsuario)
    {
        if (empty($idUsuario)) { //El $id se usará cuando lo vaya a actualizar un usuario con permisos de administrador
            $user = User::where('id', Auth::id())->first();
        } else {
            $user = User::where('id', $idUsuario)->first();
        }

        Validator::make($request->all(), [
            'nombre' => 'required|string|max:100',
            'rol' => 'required|in:Admin,Autor,Usuario'
        ])->validate();

        //return response()->json(['message' => $idUsuario], 403);

        if (Auth::user()->rol !== 'Admin') {
            return response()->json(['message' => 'No tienes suficientes permisos'], 403);
        }

        if (!empty($request->input('nombre'))) {
            $user->nombre = $request->input('nombre');
        }
        if (!empty($request->input('rol'))) {
            $user->rol = $request->input('rol');
        }

        $res = $user->save();

        if ($res) {
            return response()->json(['message' => 'Usuario actualizado correctamente']);
        }

        return response()->json(['message' => 'Error al actualizar usuario'], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $idUsuario)
    {
        $user = User::where('id', $idUsuario)->first();

        if (($idUsuario !== Auth::id()) && (Auth::user()->rol !== 'Admin')) {
            return response()->json(['message' => 'No tienes permisos']);
        }
        $res = $user->delete();

        if ($res) {
            return response()->json(['message' => 'Usuario eliminado correctamente']);
        }

        return response()->json(['message' => 'Error al borrar usuario'], 500);
    }
}
