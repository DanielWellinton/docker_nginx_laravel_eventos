@extends('layouts.comum')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3>Lista de Participantes</h3>
        <a href="{{ route('participantes.create') }}" class="btn btn-success">+ Novo Participante</a>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('participantes.index') }}" class="row g-3">
            <div class="col-md-3">
                <input type="text" name="nome" class="form-control" placeholder="Nome do participante" value="{{ request('nome') }}">
            </div>
            <div class="col-md-3">
                <input type="text" name="email" class="form-control" placeholder="Email" value="{{ request('email') }}">
            </div>
            <div class="col-md-3">
                <select name="evento_id" class="form-control" >
                    <option value="">Todos os eventos</option>
                    @foreach ($eventos as $evento)
                        <option value="{{ $evento->id }}" {{ request('evento_id') == $evento->id ? 'selected' : '' }}>
                            {{ $evento->nome }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">Filtrar</button>
                <a href="{{ route('participantes.index') }}" class="btn btn-secondary">Limpar</a>
            </div>
        </form>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Telefone</th>
                    <th>Evento</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($participantes as $participante)
                    <tr>
                        <td>{{ $participante->nome }}</td>
                        <td>{{ $participante->email }}</td>
                        <td>{{ $participante->telefone }}</td>
                        <td>{{ $participante->evento->nome }}</td>
                        <td>
                            <a href="{{ route('participantes.edit', $participante->id) }}" class="btn btn-warning">Editar</a>
                            <form action="{{ route('participantes.destroy', $participante->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger" onclick="return confirm('Tem certeza?')">Excluir</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-center">
            {{ $participantes->links() }}
        </div>
    </div>
</div>
@endsection