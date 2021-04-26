<?php

namespace App\Http\Controllers;

use App\Product;
use App\Produto;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function index()
    {
        $produtos = Produto::where('destaque', true)->take(8)->inRandomOrder()->get();
        return view('landing-page')->with('produtos', $produtos);
    }
}
