@extends('layout')

@section('title', 'Produtos')

@section('content')

    @component('components.breadcrumbs')
        <a href="/">Home</a>
        <i class="fa fa-chevron-right breadcrumb-separator"></i>
        <span>Loja</span>
    @endcomponent

    <div class="container">
        @if (session()->has('success_message'))
            <div class="alert alert-success">
                {{ session()->get('success_message') }}
            </div>
        @endif

        @if(count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <div class="products-section container">
        <div class="sidebar">
            <h3>Por Categoria</h3>
            <ul>
                @foreach ($categorias as $categoria)
                    <li class="{{ setCategoriaAtual($categoria->slug) }}"><a href="{{ route('shop.index', ['categoria' => $categoria->slug]) }}">{{ $categoria->nome }}</a></li>
                @endforeach
            </ul>
        </div> <!-- end sidebar -->
        <div>
            <div class="products-header">
                <h1 class="stylish-heading">{{ $categoriaNome }}</h1>
                <div>
                    <strong>Preço: </strong>
                    <a href="{{ route('shop.index', ['categoria'=> request()->categoria, 'sort' => 'low_high']) }}">Menor para Maior</a> |
                    <a href="{{ route('shop.index', ['categoria'=> request()->categoria, 'sort' => 'high_low']) }}">Maior para Menor</a>
                </div>
            </div>

            <div class="products text-center">
                @forelse ($produtos as $produto)
                    <div class="product">
                        <a href="{{ route('shop.show', $produto->slug) }}"><img src="{{ productImage($produto->image) }}" alt="produto"></a>
                        <a href="{{ route('shop.show', $produto->slug) }}"><div class="product-name">{{ $produto->nome }}</div></a>
                        <div class="product-price">{{ $produto->precoFormatado() }}</div>
                    </div>
                @empty
                    <div style="text-align: left">Nenhum item encontrado</div>
                @endforelse
            </div> <!-- end products -->

            <div class="spacer"></div>
            {{ $produtos->appends(request()->input())->links() }}
        </div>
    </div>

@endsection

@section('extra-js')
    <script src="https://cdn.jsdelivr.net/autocomplete.js/0/autocomplete.min.js"></script>
@endsection
