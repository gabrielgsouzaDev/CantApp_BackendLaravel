<?php

namespace App\Http\Controllers;

use App\Services\TransacaoService;
use Illuminate\Http\Request;

class TransacaoController extends Controller
{
    protected $service;

    public function __construct(TransacaoService $service)
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
}
