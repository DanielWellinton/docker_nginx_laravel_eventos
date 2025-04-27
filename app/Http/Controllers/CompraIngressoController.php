<?php

namespace App\Http\Controllers;

use App\Models\Ingresso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompraIngressoController extends Controller
{
    public function index(Request $request) {
        $query = Ingresso::query();

        if ($request->filled('nome')) {
            $query->where('nome', 'like', '%' . $request->nome . '%');
        }

        if ($request->has('evento') && !empty($request->evento)) {
            $evento = $request->input('evento');
            $query->whereHas('evento', function($q) use ($evento) {
                $q->where('nome', 'ilike', '%' . $evento . '%');
            });
        }

        $compraingressos = $query->orderBy('evento_id', 'asc')->paginate(10);

        return view('compraingressos.index', compact('compraingressos'));
    }

    public function create(Request $request)
    {
        $idIngressos = $request->query('ingressos');
        $ingressos = DB::table('ingressos')
            ->whereIn('id', array_keys($idIngressos))
            ->get();
        foreach($ingressos as $ingresso) {
            $ingresso->quantity = $idIngressos[$ingresso->id];
        }

        return view('compraingressos.create', compact('ingressos'));
    }

    public function store(Request $request) {
        $dados = $request->validate([
            'evento_id' => 'required|exists:eventos,id',
            'unit_amount' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'nome' => 'required|string|min:1',
        ]);


        $ingresso = Ingresso::create($dados);

        return response()->json($ingresso, 201);
    }

    public function show($id) {
        $ingresso = Ingresso::with('evento')->findOrFail($id);
        return response()->json($ingresso);
    }

    public function edit($id)
    {
        $eventos = Evento::all();
        $ingresso = Ingresso::findOrFail($id);
        return view('compraingressos.edit', compact('ingresso', 'eventos'));
    }

    public function update(Request $request, $id) {
        $ingresso = Ingresso::findOrFail($id);

        $request->validate([
            'evento_id' => 'required|exists:eventos,id',
            'unit_amount' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'nome' => 'required|string|min:1',
        ]);

        $ingresso->update($request->all());
        return response()->json($ingresso);
    }

    public function destroy($id) {
        Ingresso::findOrFail($id)->delete();
        return redirect()->route('compraingressos.index')->with('success', 'Ingresso removido com sucesso!');
    }
}
