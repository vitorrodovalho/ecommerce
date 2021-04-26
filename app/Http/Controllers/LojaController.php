<?php

namespace App\Http\Controllers;

use App\Produto;
use App\Categoria;
use Illuminate\Http\Request;

class LojaController extends Controller
{
    // Retorna itens para a página de exibição da loja
    public function index()
    {
        $paginacao = 9;
        $categorias = Categoria::all();

        if (request()->categoria) {
            $produtos = Produto::with('categorias')
                ->whereHas('categorias', function ($query) {
                $query->where('slug', request()->categoria);
            });
            $categoriaNome = optional($categorias->where('slug', request()->categoria)->first())->nome;
        } else {
            $produtos = Produto::where('destaque', true);
            $categoriaNome = 'Destaque';
        }

        if (request()->sort == 'low_high') {
            $produtos = $produtos->orderBy('preco')->paginate($paginacao);
        } elseif (request()->sort == 'high_low') {
            $produtos = $produtos->orderBy('preco', 'desc')->paginate($paginacao);
        } else {
            $produtos = $produtos->paginate($paginacao);
        }

        return view('shop')->with([
            'produtos' => $produtos,
            'categorias' => $categorias,
            'categoriaNome' => $categoriaNome,
        ]);
    }


    public function show($slug)
    {
        $produto = Produto::where('slug', $slug)->firstOrFail();
        $estoqueQuantidade = getStockLevel($produto->quantidade);
        return view('product')->with([
            'produto' => $produto,
            'estoqueQuantidade' => $estoqueQuantidade,
        ]);
    }

    // Pesquisa por produto
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|min:3',
        ]);

        $query = $request->input('query');
        $produtos = Produto::search($query)->paginate(10);
        return view('search-results')->with('produtos', $produtos);
    }
}
