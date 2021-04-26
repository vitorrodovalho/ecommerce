@extends('layout')

@section('title', 'Pagamento')

@section('extra-css')
    <style>
        .mt-32 {
            margin-top: 32px;
        }
    </style>

    <script src="https://js.stripe.com/v3/"></script>
@endsection

@section('content')

    <div class="container">

        @if (session()->has('success_message'))
            <div class="spacer"></div>
            <div class="alert alert-success">
                {{ session()->get('success_message') }}
            </div>
        @endif

        @if(count($errors) > 0)
            <div class="spacer"></div>
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{!! $error !!}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <h1 class="checkout-heading stylish-heading">Checkout</h1>
        <div class="checkout-section">
            <div>
                <form action="{{ route('checkout.store') }}" method="POST" id="payment-form">
                    {{ csrf_field() }}
                    <h2>Detalhes do Pedido</h2>

                    <div class="form-group">
                        <label for="email">Email</label>
                        @if (auth()->user())
                            <input type="email" class="form-control" id="email" name="email" value="{{ auth()->user()->email }}" readonly>
                        @else
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="nome">Nome</label>
                        <input type="text" class="form-control" id="nome" name="nome" value="{{ old('nome') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="endereco">Endereço</label>
                        <input type="text" class="form-control" id="endereco" name="endereco" value="{{ old('endereco') }}" required>
                    </div>

                    <div class="half-form">
                        <div class="form-group">
                            <label for="cidade">Cidade</label>
                            <input type="text" class="form-control" id="cidade" name="cidade" value="{{ old('cidade') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="estado">Estado</label>
                            <input type="text" class="form-control" id="estado" name="estado" value="{{ old('estado') }}" required>
                        </div>
                    </div> <!-- end half-form -->

                    <div class="half-form">
                        <div class="form-group">
                            <label for="cep">CEP</label>
                            <input type="text" class="form-control" id="cep" name="cep" value="{{ old('cep') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="telefone">Telefone</label>
                            <input type="text" class="form-control" id="telefone" name="telefone" value="{{ old('telefone') }}" required>
                        </div>
                    </div> <!-- end half-form -->

                    <div class="spacer"></div>

                    <h2>Detalhes Pagamento</h2>

                    <div class="form-group">
                        <label for="nome_cartao">Nome no Cartão</label>
                        <input type="text" class="form-control" id="nome_cartao" name="nome_cartao" value="">
                    </div>

                    <div class="form-group">
                        <label for="card-element">
                          Cartão de crédito ou débito
                        </label>
                        <div id="card-element">
                          <!-- o script de pagamento do Stripe será inserido aqui. -->
                        </div>

                        <!-- usado para exibir erros no formulario -->
                        <div id="card-errors" role="alert"></div>
                    </div>
                    <div class="spacer"></div>

                    <button type="submit" id="complete-order" class="button-primary full-width">Finalizar Pedido</button>
                </form>
            </div>

            <div class="checkout-table-container">
                <h2>Seu Pedido</h2>

                <div class="checkout-table">
                    @foreach (Cart::content() as $item)
                    <div class="checkout-table-row">
                        <div class="checkout-table-row-left">
                            <img src="{{ productImage($item->model->image) }}" alt="item" class="checkout-table-img">
                            <div class="checkout-item-details">
                                <div class="checkout-table-item">{{ $item->model->name }}</div>
                                <div class="checkout-table-description">{{ $item->model->details }}</div>
                                <div class="checkout-table-price">{{ $item->model->presentPrice() }}</div>
                            </div>
                        </div> <!-- end checkout-table -->

                        <div class="checkout-table-row-right">
                            <div class="checkout-table-quantity">{{ $item->qty }}</div>
                        </div>
                    </div> <!-- end checkout-table-row -->
                    @endforeach

                </div> <!-- end checkout-table -->

                <div class="checkout-totals">
                    <div class="checkout-totals-left">
                        Subtotal <br>
                        @if (session()->has('coupon'))
                            Desconto ({{ session()->get('coupon')['name'] }}) :
                            <br>
                            <hr>
                            Novo Subtotal <br>
                        @endif
                        <span class="checkout-totals-total">Total</span>
                    </div>

                    <div class="checkout-totals-right">
                        {{ precoFormatado(Cart::subtotal()) }} <br>
                        @if (session()->has('coupon'))
                            -{{ precoFormatado($discount) }} <br>
                            <hr>
                            {{ precoFormatado($newSubtotal) }} <br>
                        @endif
                        {{ precoFormatado($newTax) }} <br>
                        <span class="checkout-totals-total">{{ precoFormatado($newTotal) }}</span>

                    </div>
                </div> <!-- end checkout-totals -->
            </div>

        </div> <!-- end checkout-section -->
    </div>

@endsection

@section('extra-js')
    <script src="https://js.braintreegateway.com/web/dropin/1.13.0/js/dropin.min.js"></script>

    <script>
        (function(){
            // Crie um cliente Stripe
            var stripe = Stripe('{{ config('services.stripe.key') }}');

            // Crie uma instância de Elements
            var elements = stripe.elements();

            // Estilo personalizado para campos de pagamento do Stripe.
            var style = {
              base: {
                color: '#32325d',
                lineHeight: '18px',
                fontFamily: '"Roboto", Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: 'antialiased',
                fontSize: '16px',
                '::placeholder': {
                  color: '#aab7c4'
                }
              },
              invalid: {
                color: '#fa755a',
                iconColor: '#fa755a'
              }
            };

            // Crie uma instância do elemento do cartão
            var card = elements.create('card', {
                style: style,
                hidePostalCode: true
            });

            // Adicione uma instância do elemento card no `card-element` <div>
            card.mount('#card-element');

            // Manipula erros de validação em tempo real da transacao.
            card.addEventListener('change', function(event) {
              var displayError = document.getElementById('card-errors');
              if (event.error) {
                displayError.textContent = event.error.message;
              } else {
                displayError.textContent = '';
              }
            });

            // Evento envio do formulário
            var form = document.getElementById('payment-form');
            form.addEventListener('submit', function(event) {
              event.preventDefault();

              // Desabilita o botão de envio para evitar cliques repetidos
              document.getElementById('complete-order').disabled = true;

              var options = {
                name: document.getElementById('name_on_card').value,
                address_line1: document.getElementById('address').value,
                address_city: document.getElementById('city').value,
                address_state: document.getElementById('province').value,
                address_zip: document.getElementById('postalcode').value
              }

              stripe.createToken(card, options).then(function(result) {
                if (result.error) {
                  // Informar ao usuário se houve um erro
                  var errorElement = document.getElementById('card-errors');
                  errorElement.textContent = result.error.message;

                  // Habilite o botão de envio
                  document.getElementById('complete-order').disabled = false;
                } else {
                  // Envie o token para o servidor
                  stripeTokenHandler(result.token);
                }
              });
            });

            function stripeTokenHandler(token) {
              // Insira o ID do token no formulário para que seja enviado ao servidor
              var form = document.getElementById('payment-form');
              var hiddenInput = document.createElement('input');
              hiddenInput.setAttribute('type', 'hidden');
              hiddenInput.setAttribute('name', 'stripeToken');
              hiddenInput.setAttribute('value', token.id);
              form.appendChild(hiddenInput);

              // Envia o formulário
              form.submit();
            }
        })();
    </script>
@endsection
