@extends('layouts.comum')

@section('content')
<div class="container">
    <h1 class="mb-4">Adicionar Participante</h1>

    <form action="{{ route('participantes.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" class="form-control" id="nome" name="nome" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>

        <div class="mb-3">
            <label for="evento_id" class="form-label">Evento</label>
            <select class="form-control" id="evento_id" name="evento_id" required>
                @foreach($eventos as $evento)
                    <option value="{{ $evento->id }}">{{ $evento->nome }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">Salvar</button>
        <a href="{{ route('participantes.index') }}" class="btn btn-secondary">Voltar</a>
    </form>
</div>
@endsection