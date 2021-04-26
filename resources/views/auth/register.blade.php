@extends('layout')

@section('title', 'Sign Up for an Account')

@section('content')
<div class="container">
    <div class="auth-pages">
        <div>
            @if (session()->has('success_message'))
            <div class="alert alert-success">
                {{ session()->get('success_message') }}
            </div>
            @endif @if(count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <h2>Criar Conta</h2>
            <div class="spacer"></div>

            <form method="POST" action="{{ route('register') }}">
                {{ csrf_field() }}

                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="Nome" required autofocus>

                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email" required>

                <input id="password" type="password" class="form-control" name="password" placeholder="Password" placeholder="Senha" required>

                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Confirmar Senha"
                    required>

                <div class="login-container">
                    <button type="submit" class="auth-button">Criar Conta</button>
                    <div class="already-have-container">
                        <p><strong>Já possui uma conta?</strong></p>
                        <a href="{{ route('login') }}">Login</a>
                    </div>
                </div>

            </form>
        </div>

        <div class="auth-right">
            <h2>Novo Cliente</h2>
            <div class="spacer"></div>
            <p><strong>Economize tempo agora.</strong></p>
            <p>Criar uma conta permitirá que você faça check-out mais rapidamente no futuro, tenha fácil acesso ao histórico de pedidos e personalize sua experiência de acordo com suas preferências.</p>
        </div>
    </div> <!-- end auth-pages -->
</div>
@endsection
