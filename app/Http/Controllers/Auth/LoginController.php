<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        session()->put('previousUrl', url()->previous());

        return view('auth.login');
    }

    // substituir o logout para que o conteúdo do carrinho permaneça:
    public function logout(Request $request)
    {
        $cart = collect(session()->get('cart'));

        $destination = \Auth::logout();

        if (!config('cart.destroy_on_logout')) {
            $cart->each(function ($rows, $identifier) {
                session()->put('cart.' . $identifier, $rows);
            });
        }

        return redirect()->to($destination);
    }

    public function redirectTo()
    {
        return str_replace(url('/'), '', session()->get('previousUrl', '/'));
    }
}
