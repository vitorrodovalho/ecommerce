<?php

namespace App\Http\Controllers;

use App\Cupom;
use App\Jobs\UpdateCoupon;
use Illuminate\Http\Request;

class CuponsController extends Controller
{
    // Insere cupom no pedido
    public function store(Request $request)
    {
        $cupom = Cupom::where('codigo', $request->coupon_code)->first();
        if (!$cupom) {
            return back()->withErrors('Código de cupom inválido. Por favor, tente novamente.');
        }
        dispatch_now(new UpdateCoupon($cupom));
        return back()->with('success_message', 'O cupom foi aplicado!');
    }

    // Remove cupom do pedido
    public function destroy()
    {
        session()->forget('cupom');
        return back()->with('success_message', 'Cupom removido!');
    }
}
