<?php

namespace App\Http\Controllers;

use App\Models\Ingresso;
use App\Models\Price;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\StripeClient;

class PriceController extends Controller
{
    private $stripe; // ./stripe listen --forward-to http://localhost:80/stripe/webhook

    public function __construct()
    {
        $this->stripe = new StripeClient(env('STRIPE_SECRET'));
    }

    public function index($ingressoId)
    {
        $ingresso = Ingresso::findOrFail($ingressoId);
        $precos = $ingresso->precos()->orderBy('created_at', 'desc')->get();
    
        return view('partials.precos_tabela', compact('precos', 'ingresso'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'unit_amount' => 'required|numeric|min:1',
            'ingresso_id' => 'required|exists:ingressos,id',
        ]);

        $ingresso = Ingresso::findOrFail($request->ingresso_id);
        try {
            $active = (bool) $request->get('active_price');
            $unitAmount = $request->get('unit_amount');
            $priceStripe = $this->stripe->prices->create([
                'unit_amount' => $unitAmount,
                'currency' => env('CASHIER_CURRENCY'),
                'product' => $ingresso->product_id,
                'active' => $active,
            ]);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return response()->json(['error' => $e->getError()], $e->getHttpStatus());
        }
        
        $dados = array();
        $dados['price_id'] = $priceStripe->id;
        $dados['unit_amount'] = $unitAmount;
        $dados['ingresso_id'] = $ingresso->id;
        $dados['active'] = $active;
        $price = Price::create($dados);

        if ($active) {
            try {
                $this->desativarPrices($price->id, $ingresso->id);
                return response()->json(['success' => true], 200);
            } catch (\Stripe\Exception\ApiErrorException $e) {
                return response()->json(['error' => $e->getMessage()], $e->getHttpStatus() ?? 500);
            }  
        }
        return response()->json($price, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $price = Price::findOrFail($id);
        $ingresso = $price->ingresso;
    
        if (!$ingresso) {
            return response()->json(['error' => 'Ingresso não encontrado para este preço.'], 404);
        }
        try {
            $this->desativarPrices($id, $ingresso->id);
            $this->ativarPrice($price);

            return response()->json(['success' => true], 200);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return response()->json(['error' => $e->getMessage()], $e->getHttpStatus() ?? 500);
        }
    }
    
    protected function ativarPrice($price) {
        $this->stripe->prices->update($price->price_id, ['active' => true]);
        $price->active = true;
        $price->save();
    }

    protected function desativarPrices($idPrice, $idIngresso)
    {
        $pricesParaDesativar = Price::where('ingresso_id', $idIngresso)
            ->where('id', '!=', $idPrice)
            ->where('active', true)
            ->get();
        foreach ($pricesParaDesativar as $priceParaDesativar) {
            $this->stripe->prices->update($priceParaDesativar->price_id, ['active' => false]);
            $priceParaDesativar->active = false;
            $priceParaDesativar->save();
        }
    }
}
