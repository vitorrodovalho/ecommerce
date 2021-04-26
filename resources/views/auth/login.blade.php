@extends('layout')

@section('title', 'Login')

@section('content')
<div class="container">
    <div class="auth-pages">
        <div class="auth-left">
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
            <h2>Já possui uma Conta?</h2>
            <div class="spacer"></div>

            <form action="{{ route('login') }}" method="POST">
                {{ csrf_field() }}

                <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Email" required autofocus>
                <input type="password" id="password" name="password" value="{{ old('password') }}" placeholder="Senha" required>

                <div class="login-container">
                    <button type="submit" class="auth-button">Login</button>
                    <label>
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Lembrar-me
                    </label>
                </div>

                <div class="spacer"></div>

                <a href="{{ route('password.request') }}">
                    Esqueceu sua senha?
                </a>

            </form>
        </div>

        <div class="auth-right">
            <h2>Nova Conta</h2>
            <div class="spacer"></div>
            <p><strong>Economize tempo agora.</strong></p>
            <p>Você não precisa de uma conta para finalizar a compra.</p>
            <div class="spacer"></div>
            <a href="{{ route('guestCheckout.index') }}" class="auth-button-hollow">Continuar como Convidado</a>
            <div class="spacer"></div>
            &nbsp;
            <div class="spacer"></div>
            <p><strong>Economize tempo depois.</strong></p>
            <p>Crie uma conta para pagamento rápido e fácil acesso ao histórico de pedidos.</p>
            <div class="spacer"></div>
            <a href="{{ route('register') }}" class="auth-button-hollow">Criar Conta</a>

        </div>
    </div>
</div>
@endsection
