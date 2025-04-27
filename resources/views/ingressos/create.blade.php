@extends('layouts.comum')

@section('title', 'Criar Ingresso')

@section('content')
    <h1 class="mb-4">Adicionar Novo Ingresso</h1>

    <form action="{{ route('ingressos.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="evento_id" class="form-label">Evento</label>
            <select class="form-select" name="evento_id" required>
                <option value="">Selecione o Evento</option>
                @foreach($eventos as $evento)
                    <option value="{{ $evento->id }}">{{ $evento->nome }} ({{ $evento->id }})</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="name" class="form-label">Nome do Ingresso</label>
            <input type="text" class="form-control" name="name" required>
        </div>

        <div class="mb-3">
            <label for="quantity" class="form-label">Quantidade</label>
            <input type="number" class="form-control" name="quantity" min="1" required>
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="active" name="active">
            <label class="form-check-label" for="active">Ativo?</label>
        </div>

        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Salvar</button>
        <a href="{{ route('ingressos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
@endsection
