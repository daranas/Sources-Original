<div class="container home-product">
    @foreach($products as $product)
        <h1 class="home-product-title">{{$product['title']}} <a href="{{ url('/categories') .'/'. $product['category_slug'] .'/products'}}" class="float-right">Selengkapnya</a></h1>
        <div class="product-swiper">
            @foreach($product['data'] as $getProduct)
            <div class="product-card">
                <div class="product-card-top">
                    @if (! $getProduct->base_image->exists)
                        <a href="{{ $getProduct->url() }}" class="product-image">
                            <img src="{{ asset('themes/storefront/public/images/image-placeholder.png') }}" alt="Image placeholder" class="image-placeholder">
                        </a>
                    @else
                        <a href="{{ $getProduct->url() }}" class="product-image">
                            <img class="lazy" data-src="{{ $getProduct->base_image->path }}" alt="Product image">
                        </a>
                    @endif
                </div>
                <div class="product-card-middle">
                    <a href="{{ $getProduct->url() }}" class="product-name">
                        <h6>{{ $getProduct->name }}</h6>
                    </a>
                    <div class="product-price product-price-clone">{{ $getProduct->price->format() }}</div>
                </div>
                <div class="product-card-bottom">
                    <div class="product-price">{{ $getProduct->price->format() }}</div>
                    <a href="{{ $getProduct->url() }}" class="btn btn-primary btn-add-to-cart">DETAIL</a>
                </div>
            </div>
            @endforeach
        </div>
    @endforeach
</div>