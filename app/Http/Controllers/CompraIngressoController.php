<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Ingresso;
use Illuminate\Http\Request;
use Stripe\StripeClient;

class CompraIngressoController extends Controller
{
    private $stripe; // ./stripe listen --forward-to http://localhost:80/stripe/webhook

    public function __construct()
    {
        $this->stripe = new StripeClient(env('STRIPE_SECRET'));
    }

    public function index(Request $request) {
        $query = Ingresso::query();

        if ($request->filled('nome')) {
            $query->where('nome', 'like', '%' . $request->nome . '%');
        }

        if ($request->has('evento') && !empty($request->evento)) {
            $evento = $request->input('evento');
            $query->whereHas('evento', function($q) use ($evento) {
                $q->where('nome', 'ilike', '%' . $evento . '%');
            });
        }

        $compraingressos = $query->orderBy('evento_id', 'asc')->paginate(10);

        return view('compraingressos.index', compact('compraingressos'));
    }

    public function create(Request $request)
    {
        // Recupera o session_id da URL
        $sessionId = $request->query('session_id');
        
        if (!$sessionId) {
            return redirect()->route('compraingressos.index');
        }
    
        // Recupera a sessão do Stripe usando o session_id
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $checkout_session = \Stripe\Checkout\Session::retrieve($sessionId);
    
        return view('compraingressos.create', [
            'checkoutSession' => $checkout_session
        ]);
    }

    public function store(Request $request)
    {
        // Validação dos dados
        $dados = $request->validate([
            'ingressos' => 'required|array',
            'ingressos.*' => 'required|integer|min:0',
        ]);

        // Calcula o total e prepara os itens do checkout
        $total = 0;
        $line_items = [];
        $metadataIngressos = [];
        foreach ($dados['ingressos'] as $ingressoId => $quantidade) {
            if ($quantidade < 1) {
                continue; // pula se a quantidade for zero ou negativa
            }
            $ingresso = Ingresso::findOrFail($ingressoId);
            $metadataIngressos[$ingresso->name] = $quantidade;
            $preco = $ingresso->priceAtivo->unit_amount / 100; // Preço em reais
            $total += $preco * $quantidade;

            // Adiciona cada ingresso como um item no checkout
            $line_items[] = [
                'price_data' => [
                    'currency' => 'brl',
                    'product_data' => [
                        'name' => $ingresso->name,
                        'description' => $ingresso->description ?? 'Ingressos para evento',
                    ],
                    'unit_amount' => (int)($preco * 100), // Converte para centavos
                ],
                'quantity' => $quantidade,
            ];
        }

        // Cria a sessão de checkout no Stripe
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $checkout_session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $line_items,
                'mode' => 'payment',
                'success_url' => route('compraingressos.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('compraingressos.cancel'),
                'metadata' => [
                    'evento_id' => $ingresso->evento_id,
                    'ingressos' => json_encode($metadataIngressos),
                ],
            ]);
            
            // Redireciona para a página de pagamento com o session_id
            return redirect()->route('compraingressos.create', ['session_id' => $checkout_session->id]);

        } catch (\Stripe\Exception\ApiErrorException $e) {
            // Caso ocorra algum erro, você pode adicionar uma mensagem de erro
            return back()->withErrors(['error' => 'Erro ao criar sessão de checkout: ' . $e->getMessage()]);
        }
    }

    public function success(Request $request)
    {
        // Recupera o session_id da URL
        $sessionId = $request->query('session_id');
    
        // Verifica se o session_id existe
        if (!$sessionId) {
            return redirect()->route('compraingressos.index')->withErrors(['error' => 'Sessão inválida.']);
        }
    
        // Recupera a sessão do Stripe usando o session_id
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        try {
            $checkoutSession = \Stripe\Checkout\Session::retrieve($sessionId);
    
            // Verifica se a sessão foi recuperada corretamente
            if (!$checkoutSession) {
                return redirect()->route('compraingressos.index')->withErrors(['error' => 'Sessão de pagamento não encontrada.']);
            }
    
            // Recupera os ingressos selecionados e o evento
            $ingressos = json_decode($checkoutSession->metadata->ingressos, true) ?? [];
            $eventoId = $checkoutSession->metadata->evento_id;
            $evento = Evento::find($eventoId);
    
            // Carrega os ingressos do banco de dados com seus preços
            $ingressosDetalhados = [];
            foreach ($ingressos as $nome => $quantidade) {
                $ingresso = Ingresso::where('name', $nome)->first();
    
                // Carrega o preço ativo do ingresso
                if ($ingresso) {
                    $ingresso->preco_ativo = $ingresso->priceAtivo;
                    $ingressosDetalhados[$nome] = [
                        'quantidade' => $quantidade,
                        'preco' => $ingresso->preco_ativo ? $ingresso->preco_ativo->unit_amount / 100 : 0,
                        'ingresso' => $ingresso
                    ];
                }
            }
    
            // Passa os dados para a view
            return view('compraingressos.success', [
                'checkoutSession' => $checkoutSession,
                'ingressosDetalhados' => $ingressosDetalhados,
                'evento' => $evento,
            ]);
    
        } catch (\Stripe\Exception\ApiErrorException $e) {
            // Caso ocorra um erro na API do Stripe, exibe a mensagem de erro
            return redirect()->route('compraingressos.index')->withErrors(['error' => 'Erro ao recuperar sessão de pagamento: ' . $e->getMessage()]);
        }
    }
    
    
       

    public function cancel()
    {
        return view('compraingressos.cancel');
    }

}
