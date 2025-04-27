@extends('layouts.comum')

@section('content')
<div class="container">
    <h1 class="mb-4">Editar Participante</h1>

    <form action="{{ route('participantes.update', $participante->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" class="form-control" id="nome" name="nome" value="{{ $participante->nome }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ $participante->email }}" required>
        </div>

        <div class="mb-3">
            <label for="evento_id" class="form-label">Evento</label>
            <select class="form-control" id="evento_id" name="evento_id" required>
                @foreach($eventos as $evento)
                    <option value="{{ $evento->id }}" {{ $participante->evento_id == $evento->id ? 'selected' : '' }}>
                        {{ $evento->nome }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">Atualizar</button>
        <a href="{{ route('participantes.index') }}" class="btn btn-secondary">Voltar</a>
    </form>
</div>
@endsection