<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Categoria;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\V1\CategoriaRequest;
use App\Http\Resources\V1\CategoriaResource;
use App\Models\Entrada;
use Illuminate\Support\Facades\Validator;

class CategoriaController extends Controller
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
        return CategoriaResource::collection(Categoria::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoriaRequest $request)
    {
        $request->validated();

        $categoria = new Categoria();
        if (Categoria::where('nombre', $request->input('nombre'))->exists()) {
            return response()->json(['message' => 'Error: No puede haber dos categorias con el mismo nombre'], 400);
        }
        $categoria->nombre = $request->input('nombre');

        $res = $categoria->save();

        if ($res) {
            return response()->json(['message' => 'Categoria creada satisfactoriamente'], 200);
        }

        return response()->json(['message' => 'Error al crear categoria'], 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function show(int $categoriaId)
    {
        $categoria = Categoria::where('id', $categoriaId)->first();

        $entradas = Entrada::where('categoria_id', $categoriaId)->orderBy('created_at')->get();
        return [
            'categoria' => $categoria,
            'entradas' => $entradas,
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Categoria $categoria)
    {
        Validator::make($request->all(), [
            'nombre' => 'max:100',
        ])->validate();

        if (!empty($request->input('nombre'))) {
            $categoria->nombre = $request->input('nombre');
        }

        $res = $categoria->save();

        if ($res) {
            return response()->json(['message' => 'Categoria update succesfully']);
        }

        return response()->json(['message' => 'Error to update categoria'], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function destroy(Categoria $categoria)
    {
        $res = $categoria->delete();

        if ($res) {
            return response()->json(['message' => 'categoria deleted succesfully']);
        }

        return response()->json(['message' => 'Error to delete categoria'], 500);
    }
}
