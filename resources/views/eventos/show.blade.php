<!-- resources/views/eventos/show.blade.php -->
<?php
use Carbon\Carbon;

// Defina o local para português
Carbon::setLocale('pt_BR');
?>
@extends('layouts.comum')

@section('title', $evento->nome)

@section('head')
    <!-- <script
        src="https://maps.googleapis.com/maps/api/js?key={{ getenv('MAPS_KEY') }}&libraries=maps,marker&v=beta"
        defer
    ></script> -->
@endsection

@section('content')

    <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
        
        <!-- Imagem do evento no topo -->
        @if ($evento->imagem)
            <div style="height: 300px; background: url('{{ asset("storage/" . $evento->imagem) }}') no-repeat center center; background-size: cover;">
            </div>
        @else
            <div style="height: 300px; background: url('https://via.placeholder.com/1200x300') no-repeat center center; background-size: cover;">
            </div>
        @endif

        <div class="card-body p-4">
            <h2 class="fw-bold mb-3">{{ $evento->nome }}</h2>
            <p class="text-muted mb-4">{{ $evento->descricao }}</p>

            <div class="row">
                <!-- Local -->
                <div class="col-md-6 mb-3">
                    <h5><i class="fas fa-map-marker-alt text-primary"></i> Local</h5>
                    <p class="text-muted">{{ $evento->local }}</p>
                </div>

                <!-- Data -->
                <div class="col-md-4 mb-3">
                    <h5><i class="fas fa-calendar-alt text-primary"></i> Data e Horário</h5>
                    <p class="text-muted">
                        <strong>{{ \Carbon\Carbon::parse($evento->data_inicio)->translatedFormat('d \d\e F \à\s H:i') }}</strong> -
                        <strong>{{ \Carbon\Carbon::parse($evento->data_fim)->translatedFormat('d \d\e F \à\s H:i') }}</strong>
                    </p>
                </div>

                <!-- Capacidade -->
                <div class="col-md-2 mb-3">
                    <h5><i class="fas fa-users text-primary"></i> Capacidade</h5>
                    <p class="text-muted">{{ $evento->capacidade }} pessoas</p>
                </div>
            </div>

            <!-- <gmp-map center="{{ $evento->latitude }},{{ $evento->longitude }}" zoom="10" map-id="DEMO_MAP_ID" style="height: 400px">
                <gmp-advanced-marker
                    position="{{ $evento->latitude }},{{ $evento->longitude }}"
                    title="Mountain View, CA"
                ></gmp-advanced-marker>
            </gmp-map> -->

            @if ($evento->ingressos->count() > 0)
                <h3 class="mt-4">Ingressos Disponíveis</h3>

                <div class="list-group">
                    <form id="formCompra" action="{{ route('compraingressos.store') }}" method="POST">
                        @csrf

                        @foreach ($evento->ingressos as $ingresso)
                            <div class="list-group-item d-flex justify-content-between align-items-center p-3">
                                <div>
                                    <h5 class="mb-1">{{ $ingresso->name }}</h5>
                                    <p class="mb-1">Preço: R$ {{ number_format($ingresso->priceAtivo->unit_amount / 100, 2, ',', '.') }}</p>
                                    <small class="text-muted"><span class="badge bg-success">Disponível</span></small>
                                </div>

                                <div class="d-flex align-items-center">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" id="btn_menos_{{ $ingresso->id }}" onclick="alterarQuantidade('{{ $ingresso->id }}', -1)">-</button>
                                    <input type="text" id="quantidade_{{ $ingresso->id }}" class="form-control mx-2 text-center" value="0" style="width: 50px;" readonly>
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
                    let capacidadeMaxima = {{ $evento->capacidade }};

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

                    document.getElementById('formCompra').addEventListener('submit', async function(event) {
    event.preventDefault(); // Não envia o form normalmente

    const form = event.target;
    const formData = new FormData(form);

    const response = await fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        },
        body: formData
    });

    if (response.redirected) {
        window.location.href = response.url; // Redireciona manualmente
    } else {
        const data = await response.json();
        alert(data.message || 'Erro inesperado');
    }
});
                </script>
            @endif
        </div>
    </div>
@endsection
