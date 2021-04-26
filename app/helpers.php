<?php

use Carbon\Carbon;

function precoFormatado($preco)
{
    return money_format('R$%i', $preco / 100);
}

function presentDate($date)
{
    return Carbon::parse($date)->format('M d, Y');
}

function setCategoriaAtual($categoria, $output = 'active')
{
    return request()->categoria == $categoria ? $output : '';
}

function productImage($path)
{
    return $path && file_exists('storage/'.$path) ? asset('storage/'.$path) : asset('img/not-found.jpg');
}

function getNumbers()
{
    $desconto = session()->get('cupom')['desconto'] ?? 0;
    $codigo = session()->get('cupom')['nome'] ?? null;
    //$newSubtotal = (Cart::subtotal() - $desconto);
    $newSubtotal = 0;
    if ($newSubtotal < 0) {
        $newSubtotal = 0;
    }
    $newTotal = $newSubtotal;

    return collect([
        'desconto' => $desconto,
        'code' => $codigo,
        'newSubtotal' => $newSubtotal,
        'newTotal' => $newTotal,
    ]);
}

function getStockLevel($quantity)
{
    if ($quantity > setting('site.stock_threshold', 5)) {
        $stockLevel = '<div class="badge badge-success">In Stock</div>';
    } elseif ($quantity <= setting('site.stock_threshold', 5) && $quantity > 0) {
        $stockLevel = '<div class="badge badge-warning">Low Stock</div>';
    } else {
        $stockLevel = '<div class="badge badge-danger">Not available</div>';
    }

    return $stockLevel;
}
