<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventoController extends Controller
{
    public function index(Request $request)
    {
        $query = Evento::query();

        // Filtrar por nome
        if ($request->filled('nome')) {
            $query->where('nome', 'like', '%' . $request->nome . '%');
        }

        // Filtrar por data de início
        if ($request->filled('data_inicio')) {
            $query->whereDate('data_inicio', '>=', $request->data_inicio);
        }

        // Filtrar por data de fim (apenas a data, ignorando horário)
        if ($request->filled('data_fim')) {
            $query->whereDate('data_fim', '<=', $request->data_fim);
        }

        // Paginação (10 eventos por página)
        $eventos = $query->orderBy('data_inicio', 'asc')->paginate(10);
        return view('eventos.index', compact('eventos'));
    }

    public function create()
    {
        return view('eventos.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'required|string|max:500',
            'local' => 'required|string|max:255',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date',
            'capacidade' => 'required|integer|min:1',
            'imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);
    
        $evento = new Evento();
        $evento->fill($validated);
    
        // Upload da imagem se fornecida
        if ($request->hasFile('imagem')) {
            $imagemPath = $request->file('imagem')->store('eventos', 'public');  // Salva em storage/app/public/eventos
            $evento->imagem = $imagemPath;
        }
    
        $evento->save();
    
        return redirect()->route('eventos.index')->with('success', 'Evento criado com sucesso!');
    }

    public function show($id)
    {
        $evento = Evento::findOrFail($id);
        return view('eventos.show', compact('evento'));
    }

    public function edit($id)
    {
        $evento = Evento::findOrFail($id);
        return view('eventos.form', compact('evento'));
    }

    public function update(Request $request, $id)
    {
        $evento = Evento::findOrFail($id);

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'required|string|max:500',
            'local' => 'required|string|max:255',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date',
            'capacidade' => 'required|integer|min:1',
            'imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);
    
        $evento->fill($validated);
    
        // Upload da nova imagem (se fornecida)
        if ($request->hasFile('imagem')) {
            // Remover imagem antiga (se houver)
            if ($evento->imagem) {
                Storage::disk('public')->delete($evento->imagem);
            }
            
            // Salvar a nova imagem
            $imagemPath = $request->file('imagem')->store('eventos', 'public');
            $evento->imagem = $imagemPath;
        }
    
        $evento->save();
    
        return redirect()->route('eventos.index')->with('success', 'Evento atualizado com sucesso!');
    }

    public function destroy($id)
    {
        Evento::findOrFail($id)->delete();
        return redirect()->route('eventos.index')->with('success', 'Evento excluído com sucesso!');
    }
}
