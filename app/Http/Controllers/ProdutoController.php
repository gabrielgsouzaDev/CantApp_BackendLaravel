<?php

namespace App\Http\Controllers;

use App\Services\ProdutoService;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    protected $service;

    public function __construct(ProdutoService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return response()->json($this->service->all());
    }

    public function show($id)
    {
        return response()->json($this->service->find($id));
    }

    public function store(Request $request)
    {
        return response()->json($this->service->create($request->all()), 201);
    }

    public function update(Request $request, $id)
    {
        return response()->json($this->service->update($id, $request->all()));
    }

    public function destroy($id)
    {
        return response()->json($this->service->delete($id));
    }
}
