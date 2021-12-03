<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Entrada;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\V1\EntradaRequest;
use App\Http\Resources\V1\EntradaResource;
use App\Models\Categoria;
use Illuminate\Support\Facades\Validator;

class EntradaController extends Controller
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
        return EntradaResource::collection(Entrada::latest()->paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\V1\EntradaRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(EntradaRequest $request)
    {
        $request->validated();

        $user = Auth::user();
        
        $entrada = new Entrada();

        $entrada->user()->associate($user);
        

        $url_image = $this->upload($request->file('imagen'));
        $entrada->imagen = $url_image;
        $entrada->titulo = $request->input('titulo');
        $entrada->descripcion = $request->input('descripcion');
        $entrada->categoria_id = $request->input('categoria_id');

        $categoria = new Categoria();
        $entrada->categoria()->associate($categoria);
        

        $res = $entrada->save();

        if ($res) {
            return response()->json(['message' => 'Entry create succesfully'], 201);
        }
        return response()->json(['message' => 'Error to create entry'], 500);
    }

    private function upload($imagen)
    {
        $path_info = pathinfo($imagen->getClientOriginalName());
        $entrada_path = 'images/entrada';

        $rename = uniqid() . '.' . $path_info['extension'];
        $imagen->move(public_path() . "/$entrada_path", $rename);
        return "$entrada_path/$rename";
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Entrada  $entrada
     * @return \Illuminate\Http\Response
     */
    public function show(Entrada $entrada)
    {
        return new EntradaResource($entrada);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Entrada  $entrada
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Entrada $entrada)
    {
        Validator::make($request->all(), [
            'titulo' => 'max:191',
            'imagen' => 'image|max:1024',
            'descripcion' => 'max:2000',
        ])->validate();

        if (Auth::id() !== $entrada->user->id) {
            return response()->json(['message' => 'You don\'t have permissions'], 403);
        }

        if (!empty($request->input('titulo'))) {
            $entrada->titulo = $request->input('titulo');
        }
        if (!empty($request->input('descripcion'))) {
            $entrada->descripcion = $request->input('descripcion');
        }
        if (!empty($request->file('imagen'))) {
            $url_image = $this->upload($request->file('imagen'));
            $entrada->imagen = $url_image;
        }
        if (!empty($request->input('categoria'))) {
            $entrada->categoria = $request->input('categoria');
        }

        $res = $entrada->save();

        if ($res) {
            return response()->json(['message' => 'Entry update succesfully']);
        }

        return response()->json(['message' => 'Error to update entry'], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Entrada  $entrada
     * @return \Illuminate\Http\Response
     */
    public function destroy(Entrada $entrada)
    {
        $res = $entrada->delete();

        if ($res) {
            return response()->json(['message' => 'Entry deleted succesfully']);
        }

        return response()->json(['message' => 'Error to delete entry'], 500);
    }
}
