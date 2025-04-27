<!-- resources/views/eventos/show.blade.php -->
<?php
use Carbon\Carbon;

// Defina o local para português
Carbon::setLocale('pt_BR');
?>
@extends('layouts.comum')

@section('title', $evento->nome)

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
                <div class="col-md-6 mb-3">
                    <h5><i class="fas fa-calendar-alt text-primary"></i> Data e Horário</h5>
                    <p class="text-muted">
                        <strong>{{ \Carbon\Carbon::parse($evento->data_inicio)->translatedFormat('d \d\e F \à\s H:i') }}</strong> -
                        <strong>{{ \Carbon\Carbon::parse($evento->data_fim)->translatedFormat('d \d\e F \à\s H:i') }}</strong>
                    </p>
                </div>

                <!-- Capacidade -->
                <div class="col-md-6 mb-3">
                    <h5><i class="fas fa-users text-primary"></i> Capacidade</h5>
                    <p class="text-muted">{{ $evento->capacidade }} pessoas</p>
                </div>
            </div>

            @if ($evento->ingressos->count() > 0)
                <h3 class="mt-4">Ingressos Disponíveis</h3>
                <div id="carouselIngressos" class="animated-border-lights carousel slide" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        @foreach ($evento->ingressos as $index => $ingresso)
                            <button type="button" data-bs-target="#carouselIngressos" data-bs-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}" aria-label="Slide {{ $index + 1 }}"></button>
                        @endforeach
                    </div>

                    <div class="carousel-inner">
                        @foreach ($evento->ingressos as $index => $ingresso)
                            <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                <div class="card text-center">
                                    <div class="card-body" style="background-color: #343a40; padding-bottom: 40px;">
                                        <h5 class="card-title">{{ $ingresso->nome }}</h5>
                                        <p class="card-text">Preço: R$ {{ number_format($ingresso->unit_amount, 2, ',', '.') }}</p>
                                        <p class="card-text">
                                            <span class="badge bg-{{ $ingresso->status == 'disponível' ? 'success' : 'secondary' }}">
                                                {{ ucfirst($ingresso->status) }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselIngressos" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Anterior</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselIngressos" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Próximo</span>
                    </button>
                </div>
            @endif
        </div>
    </div>

@endsection
