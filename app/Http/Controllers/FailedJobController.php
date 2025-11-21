<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FailedJobService;

class FailedJobController extends Controller
{
    protected $service;

    public function __construct(FailedJobService $service)
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
        $data = $request->all();
        return response()->json($this->service->create($data));
    }

    public function destroy($id)
    {
        return response()->json(['deleted' => $this->service->delete($id)]);
    }
}
