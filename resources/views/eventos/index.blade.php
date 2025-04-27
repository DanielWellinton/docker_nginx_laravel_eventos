<!-- resources/views/eventos/index.blade.php -->
@extends('layouts.comum')

@section('title', 'Gerenciar Eventos')

@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3>Eventos Disponíveis</h3>
        <a href="{{ route('eventos.create') }}" class="btn btn-success">+ Criar Evento</a>
    </div>
    
    <!-- Formulário de Filtro -->
    <div class="card-body">
        <form method="GET" action="{{ route('eventos.index') }}" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="nome" class="form-control" placeholder="Buscar por nome..." value="{{ request('nome') }}">
            </div>
            <div class="col-md-3">
                <input type="date" name="data_inicio" class="form-control" value="{{ request('data_inicio') }}">
            </div>
            <div class="col-md-3">
                <input type="date" name="data_fim" class="form-control" value="{{ request('data_fim') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Filtrar</button>
                <a href="{{ route('eventos.index') }}" class="btn btn-secondary">Limpar</a>
            </div>
        </form>
    </div>

    <div class="card-body">
        @if ($eventos->isEmpty())
            <p class="text-muted">Nenhum evento encontrado.</p>
        @else
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Data e Hora</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($eventos as $evento)
                        <tr>
                            <td>{{ $evento->nome }}</td>
                            <td>{{ \Carbon\Carbon::parse($evento->data_inicio)->format('d/m/Y H:i') }} às {{ \Carbon\Carbon::parse($evento->data_fim)->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('eventos.show', $evento->id) }}" class="btn btn-info btn-sm">Ver</a>
                                <a href="{{ route('eventos.edit', $evento->id) }}" class="btn btn-warning btn-sm">Editar</a>
                                <form action="{{ route('eventos.destroy', $evento->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Paginação -->
            <div class="d-flex justify-content-center">
                {{ $eventos->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>

@endsection
