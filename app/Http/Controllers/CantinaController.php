<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CantinaService;
use App\Models\Cantina; // CRÍTICO R51: Import da Model para o método index

class CantinaController extends Controller
{
    protected $service;

    public function __construct(CantinaService $service)
    {
        // NOTA: O middleware de autenticação deve ser definido nas rotas,
        // mas você pode adicioná-lo aqui se quiser proteger todo o Controller.
        // Ex: $this->middleware('auth:sanctum')->except(['index', 'show']);
        
        $this->service = $service;
    }

    // Listar todas as cantinas
    // app/Http/Controllers/CantinaController.php

    public function index()
    {
    try {
        $cantinas = \App\Models\Cantina::where('status', 'ativa')->get(); 
        return response()->json(['data' => $cantinas]);
    } catch (\Exception $e) {
        // ISSO VAI EXPOR O ERRO EXATO. EX: Connection refused.
        return response()->json([
            'error' => 'Falha Crítica no DB',
            'exception_message' => $e->getMessage(), // <--- ESTA É A MENSAGEM CHAVE!
            'code' => $e->getCode(),
        ], 500);
    }
    }

    // Mostrar uma cantina específica
    public function show($id)
    {
        $cantina = $this->service->find($id);

        if (!$cantina) {
            return response()->json(['error' => 'Cantina não encontrada'], 404);
        }

        return response()->json(['data' => $cantina]);
    }

    // Criar cantina
    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required|string',
            'id_escola' => 'required|integer',
            'hr_abertura' => 'nullable|date_format:H:i:s',
            'hr_fechamento' => 'nullable|date_format:H:i:s'
        ]);

        $cantina = $this->service->create($data);

        return response()->json(['data' => $cantina], 201);
    }

    // Atualizar cantina
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $cantina = $this->service->update($id, $data);

        if (!$cantina) {
            return response()->json(['error' => 'Cantina não encontrada'], 404);
        }

        return response()->json(['data' => $cantina]);
    }

    // Deletar cantina
    public function destroy($id)
    {
        $deleted = $this->service->delete($id);

        if (!$deleted) {
            return response()->json(['error' => 'Cantina não encontrada'], 404);
        }

        return response()->json(['data' => ['deleted' => true]]);
    }

    // ✅ Buscar cantinas por escola (rota: GET api/cantinas/escola/{id_escola})
    public function getBySchool($id_escola)
    {
        $cantinas = $this->service->getBySchool($id_escola);

        return response()->json(['data' => $cantinas]);
    }
}