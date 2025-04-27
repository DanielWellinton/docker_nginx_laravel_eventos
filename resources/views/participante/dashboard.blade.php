@extends('layouts.comum')

@section('content')
<div class="container">
    <h1 class="mb-4">Painel do Participante</h1>
    <p class="mb-5">Bem-vindo, Participante! Explore os eventos disponíveis.</p>

    <div class="row">
        @foreach ($eventos as $evento)
        <div class="col-md-4 mb-4">
            <!-- Card do evento -->
            <a href="{{ route('eventos.show', $evento->id) }}" class="text-decoration-none">
                <div class="event-card shadow-sm border-light rounded-3 overflow-hidden" 
                    style="position: relative; transition: transform 0.3s ease, box-shadow 0.3s ease; background: #fff;">
                    
                    <!-- Imagem de fundo -->
                    <div class="event-image"
                        style="height: 165px; background: url('{{ $evento->imagem ? asset('storage/' . $evento->imagem) : 'https://via.placeholder.com/300x200' }}') no-repeat center center/cover;">
                    </div>

                    <!-- Informações do evento -->
                    <div class="p-3">
                        <!-- Nome do evento -->
                        <div class="event-title fw-bold fs-5 mb-2">{{ $evento->nome }}</div>

                        <!-- Data e local -->
                        <div class="event-details text-muted small">
                            <span class="me-2">
                                <i class="bi bi-calendar-event"></i>
                                {{ \Carbon\Carbon::parse($evento->data_inicio)->format('d/m/Y H:i') }}
                            </span>
                            <span>•</span>
                            <span class="ms-2">
                                <i class="bi bi-geo-alt"></i> {{ $evento->local }}
                            </span>
                        </div>

                        <!-- Descrição curta -->
                        <p class="mt-2 text-muted small">{{ Str::limit($evento->descricao, 80) }}</p>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Efeito de hover para os cards de evento
    document.querySelectorAll('.event-card').forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.transform = 'scale(1.1)';
            card.style.boxShadow = '0 10px 20px rgba(0, 0, 0, 0.15)';
        });

        card.addEventListener('mouseleave', () => {
            card.style.transform = 'scale(1)';
            card.style.boxShadow = 'none';
        });
    });
</script>
@endsection
