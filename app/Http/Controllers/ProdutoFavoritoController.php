<?php

namespace App\Http\Controllers;

use App\Models\ProdutoFavorito;
use Illuminate\Http\Request;

class ProdutoFavoritoController extends Controller
{
    public function index($id_user)
    {
        $favoritos = ProdutoFavorito::where('id_user', $id_user)
            ->with('produto')
            ->get();

        return response()->json(['data' => $favoritos]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_user' => 'required|integer',
            'id_produto' => 'required|integer'
        ]);

        $fav = ProdutoFavorito::firstOrCreate($data);

        return response()->json(['data' => $fav], 201);
    }

    public function destroy($id_user, $id_produto)
    {
        ProdutoFavorito::where('id_user', $id_user)
            ->where('id_produto', $id_produto)
            ->delete();

        return response()->json(['data' => ['deleted' => true]]);
    }
}
