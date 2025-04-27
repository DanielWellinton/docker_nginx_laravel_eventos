<!-- resources/views/ingresso/show.blade.php -->
@extends('layouts.comum')

@section('title', 'Detalhes do Ingresso')

@section('content')

    <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
        
        <!-- Imagem do evento -->
        @if ($ingresso->evento->imagem)
            <div style="height: 300px; background: url('{{ asset("storage/" . $ingresso->evento->imagem) }}') no-repeat center center; background-size: cover;">
            </div>
        @else
            <div style="height: 300px; background: url('https://via.placeholder.com/1200x300') no-repeat center center; background-size: cover;">
            </div>
        @endif

        <div class="card-body p-4">
            <h2 class="fw-bold mb-3">{{ $ingresso->evento->nome }}</h2>
            <p class="text-muted mb-4">{{ $ingresso->evento->descricao }}</p>

            <div class="row">
                <!-- Local -->
                <div class="col-md-6 mb-3">
                    <h5><i class="fas fa-map-marker-alt text-primary"></i> Local</h5>
                    <p class="text-muted">{{ $ingresso->evento->local }}</p>
                </div>

                <!-- Data -->
                <div class="col-md-6 mb-3">
                    <h5><i class="fas fa-calendar-alt text-primary"></i> Data e Horário</h5>
                    <p class="text-muted">
                        <strong>{{ \Carbon\Carbon::parse($ingresso->evento->data_inicio)->translatedFormat('d \d\e F \à\s H:i') }}</strong> -
                        <strong>{{ \Carbon\Carbon::parse($ingresso->evento->data_fim)->translatedFormat('d \d\e F \à\s H:i') }}</strong>
                    </p>
                </div>

                <!-- Tipo de ingresso -->
                <div class="col-md-6 mb-3">
                    <h5><i class="fas fa-ticket-alt text-primary"></i> Tipo de Ingresso</h5>
                    <p class="text-muted">{{ $ingresso->tipo }}</p>
                </div>

                <!-- Preço -->
                <div class="col-md-6 mb-3">
                    <h5><i class="fas fa-dollar-sign text-primary"></i> Preço</h5>
                    <p class="text-muted">
                        @if($ingresso->unit_amount > 0)
                            R$ {{ number_format($ingresso->unit_amount, 2, ',', '.') }}
                        @else
                            <span class="badge bg-success">Gratuito</span>
                        @endif
                    </p>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('ingresso.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
                <a href="{{ route('evento.show', $ingresso->evento->id) }}" class="btn btn-primary">
                    <i class="fas fa-info-circle"></i> Ver Detalhes do Evento
                </a>
            </div>
        </div>
    </div>

@endsection
