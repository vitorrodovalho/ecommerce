<?php

namespace App\Http\Controllers;

use App\Produto;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Validator;

class CarrinhoController extends Controller
{
    // Retorna listagem com produtos para o carrinho
    public function index()
    {
        return view('cart')->with([
            'discount' => getNumbers()->get('discount'),
            'newSubtotal' => getNumbers()->get('newSubtotal'),
            'newTax' => getNumbers()->get('newTax'),
            'newTotal' => getNumbers()->get('newTotal'),
        ]);
    }

    // Adiciona produto no carrinho
    public function store(Produto $produto)
    {
        $duplicados = Cart::search(function ($cartItem, $rowId) use ($produto) {
            return $cartItem->id === $produto->id;
        });

        if ($duplicados->isNotEmpty()) {
            return redirect()->route('cart.index')->with('success_message', 'O item já está no seu carrinho!');
        }

        Cart::add($produto->id, $produto->nome, 1, $produto->preco)
            ->associate('App\Produto');

        return redirect()->route('cart.index')->with('success_message', 'Item adicionado no Carrinho!');
    }

    // Atualiza produtos do carrinho
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|numeric|between:1,5'
        ]);

        if ($validator->fails()) {
            session()->flash('errors', collect(['A quantidade deve estar entre 1 e 5.']));
            return response()->json(['success' => false], 400);
        }

        if ($request->quantity > $request->productQuantity) {
            session()->flash('errors', collect(['No momento, não temos itens suficientes em estoque.']));
            return response()->json(['success' => false], 400);
        }

        Cart::update($id, $request->quantity);
        session()->flash('success_message', 'A quantidade foi atualizada com sucesso!');
        return response()->json(['success' => true]);
    }

    // Remove item do carrinho
    public function destroy($id)
    {
        Cart::remove($id);
        return back()->with('success_message', 'Item removido do carrinho!');
    }
}
