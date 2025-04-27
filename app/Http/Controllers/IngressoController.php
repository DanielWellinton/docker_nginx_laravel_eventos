<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Ingresso;
use App\Models\Price;
use Illuminate\Http\Request;
use Stripe\StripeClient;

class IngressoController extends Controller
{
    private $stripe; // ./stripe listen --forward-to http://localhost:80/stripe/webhook

    public function __construct()
    {
        $this->stripe = new StripeClient(env('STRIPE_SECRET'));
    }

    public function index(Request $request) {
        $query = Ingresso::query();

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        $active = (bool) $request->has('active');
        if ($active && in_array($request->active, ['0', '1'], true)) {
            $query->where('active', $request->active);
        }        
        if ($request->has('evento') && !empty($request->evento)) {
            $evento = $request->input('evento');
            $query->whereHas('evento', function($q) use ($evento) {
                $q->where('nome', 'ilike', '%' . $evento . '%');
            });
        }

        $ingressos = $query->with('priceAtivo')->orderBy('evento_id', 'asc')->paginate(10);
        return view('ingressos.index', compact('ingressos'));
    }

    public function create()
    {
        $eventos = Evento::all();
        return view('ingressos.create', compact('eventos'));
    }

    public function store(Request $request) {
        $dados = $request->validate([
            'evento_id' => 'required|exists:eventos,id',
            'quantity' => 'required|integer|min:1',
            'name' => 'required|string|min:1',
        ]);

        try {
            $product = $this->stripe->products->create([
                'name' => $request->get('name'),
                'active' => (bool) $request->get('active')
            ]);

            $dados['product_id'] = $product->id;
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return response()->json(['error' => $e->getError()], $e->getHttpStatus() ?? 500);
        }
        
        $ingresso = Ingresso::create($dados);
        return response()->json($ingresso, 201);
    }

    public function show($id) {
        $ingresso = Ingresso::with('evento')->findOrFail($id);
        return response()->json($ingresso);
    }

    public function edit($id)
    {
        $eventos = Evento::all();
        $ingresso = Ingresso::findOrFail($id);
        return view('ingressos.edit', compact('ingresso', 'eventos'));
    }

    public function update(Request $request, $id) {
        $ingresso = Ingresso::findOrFail($id);

        $request->validate([
            'evento_id' => 'required|exists:eventos,id',
            'quantity' => 'required|integer|min:1',
            'name' => 'required|string|min:1',
        ]);
        $dadosProduct = array();
        $dadosProduct['name'] = $request->get('name');
        $dadosProduct['active'] = (bool) $request->has('active');
        try {
            $this->stripe->products->update($ingresso->product_id, $dadosProduct);
            if (!$dadosProduct['active']) {
                $this->desativarPrices($id);
            }
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return response()->json(['error' => $e->getError()], $e->getHttpStatus() ?? 500);
        }
        $ingresso->active = (bool) $request->has('active');
        $ingresso->name = $request->get('name');
        $ingresso->evento_id = $request->get('evento_id');
        $ingresso->quantity = $request->get('quantity');
        $ingresso->save();
        return response()->json($ingresso);
    }

    public function destroy($id) {
        $ingresso = Ingresso::findOrFail($id);
        $prices = Price::where('ingresso_id', $id)->get();
        if (count($prices) > 0) {
            return response()->json(['error' => 'Ingresso possui preços vinculados.']);
        }
        try {
            $this->stripe->products->delete($ingresso->product_id, []);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return response()->json(['error' => $e->getMessage()], $e->getHttpStatus() ?? 500);
        }

        $ingresso->delete();
        return redirect()->route('ingressos.index')->with('success', 'Ingresso removido com sucesso!');
    }

    private function desativarPrices($idIngresso)
    {
        $pricesParaDesativar = Price::where('ingresso_id', $idIngresso)
            ->where('active', true)
            ->get();
        foreach ($pricesParaDesativar as $priceParaDesativar) {
            $this->stripe->prices->update($priceParaDesativar->price_id, ['active' => false]);
            $priceParaDesativar->active = false;
            $priceParaDesativar->save();
        }
    }
}
