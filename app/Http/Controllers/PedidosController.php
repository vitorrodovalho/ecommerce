<?php

namespace App\Http\Controllers;

use App\Pedido;

class PedidosController extends Controller
{
    public function index()
    {
        $pedidos = auth()->user()->pedidos()->with('produtos')->get(); // fix n + 1 issues
        return view('my-orders')->with('orders', $pedidos);
    }

    // Exibe pedido único
    public function show(Pedido $pedido)
    {
        if (auth()->id() !== $pedido->user_id) {
            return back()->withErrors('Você não tem permissão para visualizar o pedido!');
        }

        $produtos = $pedido->produtos;

        return view('my-order')->with([
            'order' => $pedido,
            'products' => $produtos,
        ]);
    }
}
