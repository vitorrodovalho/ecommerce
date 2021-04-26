<?php

namespace App\Http\Controllers;

use App\Order;
use App\Pedido;
use App\Product;
use App\PedidoProduto;
use App\Mail\OrderPlaced;
use App\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\CheckoutRequest;
use Gloudemans\Shoppingcart\Facades\Cart;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use Cartalyst\Stripe\Exception\CardErrorException;

class CheckoutController extends Controller
{
    public function index()
    {
        if (Cart::instance('default')->count() == 0) {
            return redirect()->route('shop.index');
        }

        if (auth()->user() && request()->is('guestCheckout')) {
            return redirect()->route('checkout.index');
        }

        return view('checkout')->with([
            'desconto' => getNumbers()->get('desconto'),
            'newSubtotal' => getNumbers()->get('newSubtotal'),
            'newTax' => getNumbers()->get('newTax'),
            'newTotal' => getNumbers()->get('newTotal'),
        ]);
    }


    // Processa o pedido
    public function store(CheckoutRequest $request)
    {
        // Verifique se produtos selecionados ainda estão disponíveis
        if ($this->produtosNaoDisponiveis()) {
            return back()->withErrors('Desculpe! Um dos itens em seu carrinho não está mais disponível.');
        }

        $contents = Cart::content()->map(function ($item) {
            return $item->model->slug.', '.$item->qty;
        })->values()->toJson();

        try {
            $charge = Stripe::charges()->create([
                'amount' => getNumbers()->get('newTotal') / 100,
                'currency' => 'CAD',
                'source' => $request->stripeToken,
                'description' => 'Order',
                'receipt_email' => $request->email,
                'metadata' => [
                    'contents' => $contents,
                    'quantity' => Cart::instance('default')->count(),
                    'discount' => collect(session()->get('cupom'))->toJson(),
                ],
            ]);

            $this->adicionaPedidoTabela($request, null);

            // diminuir as quantidades de todos os produtos no carrinho
            $this->decrementaQuantidade();

            Cart::instance('default')->destroy();
            session()->forget('coupon');

            return redirect()->route('confirmation.index')->with('success_message', 'Obrigado! Seu pagamento foi efeutado com sucesso!');
        } catch (CardErrorException $e) {
            $this->adicionaPedidoTabela($request, $e->getMessage());
            return back()->withErrors('Erro! ' . $e->getMessage());
        }
    }

    // Insere pedido na tabela
    protected function adicionaPedidoTabela($request, $error)
    {
        $pedido = Pedido::create([
            'usuario_id' => auth()->user() ? auth()->user()->id : null,
            'email' => $request->email,
            'nome' => $request->nome,
            'endereco' => $request->endereco,
            'cidade' => $request->cidade,
            'estado' => $request->estado,
            'cep' => $request->cep,
            'telefone' => $request->telefone,
            'nome_cartao' => $request->nome_cartao,
            'desconto' => getNumbers()->get('desconto'),
            'codigo_desconto' => getNumbers()->get('codigo'),
            'subtotal' => getNumbers()->get('newSubtotal'),
            'total' => getNumbers()->get('newTotal'),
            'erro' => $error,
        ]);

        // Insere na tabela order_product
        foreach (Cart::content() as $item) {
            PedidoProduto::create([
                'pedido_id' => $pedido->id,
                'produto_id' => $item->model->id,
                'quantidade' => $item->qty,
            ]);
        }
        return $pedido;
    }

    // Decrementa a quantidade de todos os itens do pedido
    protected function decrementaQuantidade()
    {
        foreach (Cart::content() as $item) {
            $produto = Produto::find($item->model->id);
            $produto->update(['quantidade' => $produto->quantidade - $item->qty]);
        }
    }

    // Garante que os produtos possui a quantidade certa antes de finalizar a compra
    protected function produtosNaoDisponiveis()
    {
        foreach (Cart::content() as $item) {
            $produto = Produto::find($item->model->id);
            if ($produto->quantidade < $item->qty) {
                return true;
            }
        }
        return false;
    }
}
