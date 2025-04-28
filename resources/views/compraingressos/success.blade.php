@extends('layouts.comum')

@section('content')
<div class="container mx-auto p-6">
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if(isset($checkoutSession) && $checkoutSession)
        <h1 class="text-3xl font-bold text-center text-green-600 mb-4">Compra Concluída com Sucesso!</h1>

        <p class="text-center mb-6 text-xl">
            Parabéns! Seu pagamento foi confirmado, e seus ingressos foram adquiridos com sucesso.
        </p>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-semibold mb-4">Detalhes da Compra</h2>

            @php
                $total = 0;
            @endphp

            <ul class="list-none">
                @foreach ($ingressosDetalhados as $nome => $detalhes)
                    <li class="py-2 flex justify-between">
                        <span>{{ $nome }}</span>
                        <span>x{{ $detalhes['quantidade'] }}</span>
                        <span>R$ {{ number_format($detalhes['preco'] * $detalhes['quantidade'], 2, ',', '.') }}</span>
                    </li>
                    @php
                        $total += $detalhes['preco'] * $detalhes['quantidade'];
                    @endphp
                @endforeach
            </ul>

            <div class="mt-6 border-t pt-4">
                <h3 class="text-xl font-semibold">Total:</h3>
                <p class="text-lg">R$ {{ number_format($total, 2, ',', '.') }}</p>
            </div>

            @if ($evento)
                <div class="mt-6 border-t pt-4">
                    <h3 class="text-xl font-semibold mb-4">Informações do Evento</h3>
                    <p><strong>Evento:</strong> {{ $evento->nome }}</p>
                    <p><strong>Data:</strong> {{ \Carbon\Carbon::parse($evento->data)->format('d/m/Y H:i') }}</p>
                    <p><strong>Local:</strong> {{ $evento->local }}</p>
                </div>
            @else
                <p>Informações do evento não disponíveis.</p>
            @endif

            <div class="mt-6 border-t pt-4">
                <h3 class="text-xl font-semibold mb-4">Seu QR Code</h3>
                @foreach ($ingressosDetalhados as $nome => $detalhes)
                    @php
                        $qrcodeData = 'evento:' . $evento->id . '-ingresso:' . $detalhes['ingresso']->id;
                    @endphp
                    <div class="flex items-center mb-4">
                        <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?data={{ urlencode($qrcodeData) }}&size=150x150" alt="QR Code" />
                        </div>
                        <div class="ml-4">
                            <p><strong>{{ $nome }} ({{ $detalhes['quantidade'] }}x)</strong></p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6 text-center">
                <a href="{{ route('compraingressos.index') }}" class="text-blue-600 hover:text-blue-800">Voltar à lista de ingressos</a>
                <br>
            </div>
        </div>
    @else
        <div class="text-center">
            <p class="text-xl">A sessão de pagamento não foi encontrada. Tente novamente.</p>
        </div>
    @endif
</div>
@endsection
