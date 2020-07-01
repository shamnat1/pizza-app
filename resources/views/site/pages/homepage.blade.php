@extends('site.app')
@section('title', 'All')
@section('content')
    <section class="section-pagetop bg-dark">
        <div class="container clearfix">
            <h2 class="title-page">{{ '' }}</h2>
        </div>
    </section>
    <section class="section-content bg padding-y">
        <div class="container">
            <div id="code_prod_complex">
                <div class="row">
                    @foreach($category as $cat)
                    @forelse($cat['products'] as $product)
                        <div class="col-md-4">
                            <figure class="card card-product">
                                @if ($product->images->count() > 0)
{{--                                    <div class="img-wrap padding-y"><img src="{{ asset('storage/'.$product->images->first()->full) }}" alt=""></div>--}}
                                    <div class="img-wrap padding-y"><img src="{{ $product->images->first()->full}}" alt=""></div>
                                @else
                                    <div class="img-wrap padding-y"><img src="https://via.placeholder.com/176" alt=""></div>
                                @endif
                                <figcaption class="info-wrap">
                                    <h6 class="title">{{ $product->name }}</a></h6>
                                </figcaption>
                                <div class="col-sm-12 col-md-12 col-lg-12">{{ str_limit($product->description, 100) }}</div>
                                    <div class="bottom-wrap row row-list col-md-12">

                                        @if ($product->sale_price != 0)
                                            <div class="price-wrap h5">
                                                <span class="price"> {{ config('settings.currency_symbol').$product->sale_price }} </span>
                                                <del class="price-old"> {{ config('settings.currency_symbol').$product->price }}</del>
                                            </div>
                                        @else
                                            <div class="price-wrap h5 col-md-4">
                                                <span class="price" > {{ config('settings.currency_symbol').$product->price }} </span>
                                            </div>
                                        @endif
                                        <div class="price-wrap h5 col-md-4">

                                        </div>
                                        <div class="price-wrap h5 col-md-4">
                                            @if(\Cart::get($product->sku) && \Cart::get($product->sku)->quantity >0 )
                                                <input class="form-control input_quantity" id="qty_{{$product->id}}" type="number" onClick="quantityChage({{$product}})" min="1" value="{{ \Cart::get($product->sku)->quantity }}" max="{{ $product->quantity }}" name="qty" style="display:block;">
                                            @else
                                                <input class="form-control input_quantity" id="qty_{{$product->id}}" type="number" onClick="quantityChage({{$product}})" min="0" value="1" max="{{ $product->quantity }}" name="qty" style="display:none;">
                                                <button type="submit" class="btn btn-success" id="add_cart_{{$product->id}}" onClick="showCartCount({{$product}})"><i class="fas fa-shopping-cart"></i> Add To Cart</button>
                                            @endif
                                            {{--<a href="{{ route('product.show', $product->id) }}"  product-id="{{ $product->id }}" class="btn btn-success float-right"><i class="fa fa-shopping-cart"></i>Add to Cart</a>--}}
                                        </div>

                                    </div>

                            </figure>
                        </div>
                    @empty

                    @endforelse
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@stop

@push('scripts')
    <script>
        $(function(){

        });
        $(document).ready(function () {


        });


        function quantityChage(product) {
            console.log("product",$('#qty_'+product['id']).val(),product['id']);
            $.ajax({
                url: '{{ url('update-cart') }}',
                method: "post",
                data: {_token: '{{ csrf_token() }}', productId:product['sku'], qty: $('#qty_'+product['id']).val()},
                dataType: "json",
                success: function (response) {
                    console.log(response)
                    $("#cart-details").load(location.href + " #cart-details>*", "");

                },
                error: function(request){
                    console.log(request)
                }
            });
            if($('#qty_'+product['id']).val() == 0){
                $('#qty_'+product['id']).hide();
                $('#add_cart_'+product['id']).show();
            }

        }

        function showCartCount(product){
            console.log("product",$('#qty_'+product['id']).val(),product['id']);
            $('#qty_'+product['id']).show();
            $('#add_cart_'+product['id']).hide();
            $('#qty_'+product['id']).val(1);
            addToCart(product)
        }

        function addToCart(product){
            console.log($('#qty_'+product['id']).val())
            $.ajax({
                url: '{{ url('/product/add/cart') }}',
                method: "post",
                data: {_token: '{{ csrf_token() }}', productId:product['id'], qty: $('#qty_'+product['id']).val()},
                dataType: "json",
                success: function (response) {
                    console.log(response)
                    $("#cart-details").load(location.href + " #cart-details>*", "");


                },
                error: function(request){
                    console.log(request)
                }
            });
        }
    </script>
@endpush
