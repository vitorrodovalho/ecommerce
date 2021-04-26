<div class="might-like-section">
    <div class="container">
        <h2>Voce também pode gostar...</h2>
        <div class="might-like-grid">
            @foreach ($tambemPodeGostar as $produto)
                <a href="{{ route('shop.show', $produto->slug) }}" class="might-like-product">
                    <img src="{{ productImage($produto->imagem) }}" alt="product">
                    <div class="might-like-product-name">{{ $produto->nome }}</div>
                    <div class="might-like-product-price">{{ $produto->precoFormatado() }}</div>
                </a>
            @endforeach

        </div>
    </div>
</div>
