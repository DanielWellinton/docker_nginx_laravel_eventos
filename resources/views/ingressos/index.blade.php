@extends('layouts.comum')

@section('title', 'Gerenciar Ingressos')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3>Gerenciar Ingressos</h3>
            <a href="{{ route('ingressos.create') }}" class="btn btn-success">+ Criar Ingresso</a>
        </div>
        
        <!-- Formulário de Filtro -->
        <div class="card-body">
            <form method="GET" action="{{ route('ingressos.index') }}" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="name" class="form-control" placeholder="Buscar por nome..." value="{{ request('name') }}">
                </div>
                <div class="col-md-4">
                    <input type="text" name="evento" class="form-control" placeholder="Buscar por evento..." value="{{ request('evento') }}">
                </div>
                <div class="col-md-3 d-flex align-items-center">
                    <label for="active" class="form-label">Status: </label>
                    <select name="active" class="form-select">
                        <option value="">Todos</option>
                        <option value="1" {{ request('active') === '1' ? 'selected' : '' }}>Ativos</option>
                        <option value="0" {{ request('active') === '0' ? 'selected' : '' }}>Inativos</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                    <a href="{{ route('ingressos.index') }}" class="btn btn-secondary">Limpar</a>
                </div>
            </form>
        </div>

        <div class="card-body">
            @if ($ingressos->isEmpty())
                <p class="text-muted">Nenhum ingresso encontrado.</p>
            @else
                <table class="table table-bordered">
                    <thead>
                        <tr>
                        <th>ID</th>
                        <th>Evento</th>
                        <th>Nome do Ingresso</th>
                        <th>Preço</th>
                        <th>Quantidade</th>
                        <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ingressos as $ingresso)
                            <tr>
                                <td>{{ $ingresso->id }}</td>
                                <td>{{ $ingresso->evento->nome }}</td>
                                <td>{{ $ingresso->name }}</td>
                                <td>
                                    @if ($ingresso->priceAtivo)
                                        R$ {{ number_format($ingresso->priceAtivo->unit_amount / 100, 2, ',', '.') }}
                                    @else
                                        <span class="text-muted">Sem preço ativo</span>
                                    @endif
                                </td>

                                <td>{{ $ingresso->quantity }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <a href="{{ route('ingressos.edit', $ingresso->id) }}" class="btn btn-warning btn-sm">Editar</a>
                                        
                                        @if ($ingresso->precos()->exists())
                                            <span class="text-muted" title="Não é possível excluir, pois existem preços vinculados." style="font-size: 1.4rem; cursor: help;">
                                                <i class="bi bi-info-circle-fill"></i>
                                            </span>
                                        @else
                                            <form action="{{ route('ingressos.destroy', $ingresso->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Paginação -->
                <div class="d-flex justify-content-center">
                    {{ $ingressos->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
