<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\Request;

class ParticipanteController extends Controller
{
    public function index()
    {
        return view('participante.index');
    }

    public function dashboard()
    {
        $eventos = Evento::where('data_inicio', '>=', now()) // Filtra eventos futuros
        ->orderBy('data_inicio', 'asc') // Ordena por data de início
        ->get();

        return view('participante.dashboard', compact('eventos'));
    }
}
