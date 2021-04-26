@extends('layout')

@section('title', 'Meu Pedido')

@section('content')

    @component('components.breadcrumbs')
        <a href="/">Home</a>
        <i class="fa fa-chevron-right breadcrumb-separator"></i>
        <span>Meu Pedido</span>
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

    <div class="products-section my-orders container">
        <div class="sidebar">

            <ul>
              <li><a href="{{ route('users.edit') }}">Meu Perfil</a></li>
              <li class="active"><a href="{{ route('orders.index') }}">Meus Pedidos</a></li>
            </ul>
        </div> <!-- end sidebar -->
        <div class="my-profile">
            <div class="products-header">
                <h1 class="stylish-heading">Pedido ID: {{ $order->id }}</h1>
            </div>

            <div>
                <div class="order-container">
                    <div class="order-header">
                        <div class="order-header-items">
                            <div>
                                <div class="uppercase font-bold">Pedido Realizado</div>
                                <div>{{ presentDate($order->created_at) }}</div>
                            </div>
                            <div>
                                <div class="uppercase font-bold">Pedido ID</div>
                                <div>{{ $order->id }}</div>
                            </div><div>
                                <div class="uppercase font-bold">Total</div>
                                <div>{{ presentPrice($order->total) }}</div>
                            </div>
                        </div>
                        <div>
                            <div class="order-header-items">
                                <div><a href="#">Fatura</a></div>
                            </div>
                        </div>
                    </div>
                    <div class="order-products">
                        <table class="table" style="width:50%">
                            <tbody>
                                <tr>
                                    <td>Nome</td>
                                    <td>{{ $order->user->name }}</td>
                                </tr>
                                <tr>
                                    <td>Endereço</td>
                                    <td>{{ $order->endereco }}</td>
                                </tr>
                                <tr>
                                    <td>Cidade</td>
                                    <td>{{ $order->cidade }}</td>
                                </tr>
                                <tr>
                                    <td>Subtotal</td>
                                    <td>{{ precoFormatado($order->subtotal) }}</td>
                                </tr>
                                <tr>
                                    <td>Total</td>
                                    <td>{{ precoFormatado($order->total) }}</td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                </div> <!-- end order-container -->

                <div class="order-container">
                    <div class="order-header">
                        <div class="order-header-items">
                            <div>
                                Itens do Pedido
                            </div>

                        </div>
                    </div>
                    <div class="order-products">
                        @foreach ($produtos as $produto)
                            <div class="order-product-item">
                                <div><img src="{{ asset($produto->imagem) }}" alt="Imagem Produto"></div>
                                <div>
                                    <div>
                                        <a href="{{ route('shop.show', $produto->slug) }}">{{ $produto->nome }}</a>
                                    </div>
                                    <div>{{ precoFormatado($produto->preco) }}</div>
                                    <div>Quantidade: {{ $produto->pivot->quantity }}</div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div> <!-- end order-container -->
            </div>

            <div class="spacer"></div>
        </div>
    </div>

@endsection

@section('extra-js')
    <script src="https://cdn.jsdelivr.net/autocomplete.js/0/autocomplete.min.js"></script>
@endsection
