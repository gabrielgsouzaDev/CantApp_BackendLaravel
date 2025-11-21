<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CacheService;

class CacheController extends Controller
{
    protected $service;

    public function __construct(CacheService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return response()->json($this->service->all());
    }

    public function show($key)
    {
        return response()->json($this->service->find($key));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'key' => 'required|string',
            'value' => 'required',
            'expiration' => 'required|integer'
        ]);

        return response()->json($this->service->create($data));
    }

    public function update(Request $request, $key)
    {
        $data = $request->validate([
            'value' => 'sometimes|required',
            'expiration' => 'sometimes|required|integer'
        ]);

        return response()->json($this->service->update($key, $data));
    }

    public function destroy($key)
    {
        return response()->json(['deleted' => $this->service->delete($key)]);
    }
}
