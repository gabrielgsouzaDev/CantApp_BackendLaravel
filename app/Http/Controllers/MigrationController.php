<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MigrationService;

class MigrationController extends Controller
{
    protected $service;

    public function __construct(MigrationService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return response()->json($this->service->all());
    }

    public function store(Request $request)
    {
        $data = $request->all();
        return response()->json($this->service->create($data));
    }
}
