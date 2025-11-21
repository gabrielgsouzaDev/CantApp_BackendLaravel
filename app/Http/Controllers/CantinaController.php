<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CantinaService;

class CantinaController extends Controller
{
    protected $service;

    public function __construct(CantinaService $service)
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
        $data = $request->validate([
            'nome' => 'required|string',
            'id_escola' => 'required|integer',
            'hr_abertura' => 'nullable|date_format:H:i:s',
            'hr_fechamento' => 'nullable|date_format:H:i:s'
        ]);

        return response()->json($this->service->create($data));
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        return response()->json($this->service->update($id, $data));
    }

    public function destroy($id)
    {
        return response()->json(['deleted' => $this->service->delete($id)]);
    }
}
