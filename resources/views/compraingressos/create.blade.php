@extends('layouts.comum')

@section('title', 'Comprar Ingressos')

@section('content')

<div class="card">
    <div class="card-header">
        <h3>Comprar Ingressos</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('compraingressos.store') }}" 
              method="POST" enctype="multipart/form-data">
            @csrf

            @if ($ingressos->count() > 0)
                <h3 class="mt-4">Ingressos Disponíveis</h3>

                <div class="list-group">
                    <form action="{{ route('compraingressos.create') }}" method="POST">
                        @csrf

                        @foreach ($ingressos as $ingresso)
                            <div class="list-group-item d-flex justify-content-between align-items-center p-3">
                                <div>
                                    <h5 class="mb-1">{{ $ingresso->name }}</h5>
                                    <p class="mb-1">Preço: R$ {{ number_format($ingresso->unit_amount, 2, ',', '.') }}</p>
                                    <small class="text-muted"><span class="badge bg-success">Disponível</span></small>
                                </div>

                                <div class="d-flex align-items-center">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" id="btn_menos_{{ $ingresso->id }}" onclick="alterarQuantidade('{{ $ingresso->id }}', -1)">-</button>
                                    <input type="text" id="quantidade_{{ $ingresso->id }}" class="form-control mx-2 text-center" value="{{ $ingresso->quantity }}" style="width: 50px;" readonly>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="alterarQuantidade('{{ $ingresso->id }}', 1)">+</button>
                                    <input type="hidden" name="ingressos[{{ $ingresso->id }}]" id="hidden_quantidade_{{ $ingresso->id }}" value="0">
                                </div>
                            </div>
                        @endforeach

                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-primary">Comprar Ingressos</button>
                        </div>
                    </form>
                </div>

                <script>
                    let capacidadeMaxima = 500;

                    function alterarQuantidade(id, incremento) {
                        let input = document.getElementById(`quantidade_${id}`);
                        let btnMenos = document.getElementById(`btn_menos_${input.id.split("_")[1]}`);
                        let valorAtual = parseInt(input.value);
                        let novoValor = valorAtual + incremento;
                        if (novoValor < 0) novoValor = 0;
                        if (novoValor > capacidadeMaxima) return;
                        input.value = novoValor;
                        document.getElementById(`hidden_quantidade_${id}`).value = novoValor;
                        btnMenos.disabled = input.value == 0;
                    }
                    document.querySelectorAll("input[id^='quantidade_']").forEach(input => {
                        let btnMenos = document.getElementById(`btn_menos_${input.id.split("_")[1]}`);
                        btnMenos.disabled = input.value == 0;
                    });
                </script>
            @endif

            <a href="{{ route('compraingressos.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>

@endsection
