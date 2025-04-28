@extends('layouts.comum')

@section('head')
    <script src="https://js.stripe.com/v3/"></script>
@endsection

@section('content')
<div class="container mt-5">
    <h1 class="text-2xl font-bold mb-6 text-center">Finalizar Compra</h1>

    <div class="bg-white shadow-md rounded p-6 max-w-xl mx-auto">
        <h2 class="text-xl font-semibold mb-4">Resumo da Compra</h2>

        <ul class="divide-y divide-gray-200 mb-6">
        @php
            $total = 0;
            $ingressos = json_decode($checkoutSession->metadata->ingressos, true) ?? [];
        @endphp

        @foreach ($ingressos as $nome => $quantidade)
            @php
                $ingresso = App\Models\Ingresso::where('name', $nome)->first();
                $preco = $ingresso ? $ingresso->priceAtivo->unit_amount / 100 : 0; // Preço em reais
                $total += $preco * $quantidade;
            @endphp
            <li class="py-2 flex justify-between">
                <span>{{ $nome }}</span>
                <span>x{{ $quantidade }}</span>
                <span>R$ {{ number_format($preco * $quantidade, 2, ',', '.') }}</span>
            </li>
        @endforeach

        </ul>

        <div class="flex justify-between items-center mb-6">
            <span class="font-semibold">Total:</span>
            <span class="text-xl font-bold text-indigo-600">R$ {{ number_format($total, 2, ',', '.') }}</span>
        </div>

        <div class="text-center">
            <button id="checkout-button" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg transition">
                Pagar agora
            </button>
        </div>

        <!-- Loader -->
        <div id="loading" class="hidden text-center mt-6">
            <p>Redirecionando para o pagamento...</p>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const stripe = Stripe('{{ env('STRIPE_KEY') }}');
        const checkoutButton = document.getElementById('checkout-button');
        const loading = document.getElementById('loading');

        checkoutButton.addEventListener('click', function () {
            // Mostrar o loader
            loading.classList.remove('hidden');
            checkoutButton.classList.add('hidden');

            stripe.redirectToCheckout({
                sessionId: '{{ $checkoutSession->id }}'
            }).then(function (result) {
                if (result.error) {
                    alert(result.error.message);
                    loading.classList.add('hidden');
                    checkoutButton.classList.remove('hidden');
                }
            });
        });
    });
</script>
@endsection
