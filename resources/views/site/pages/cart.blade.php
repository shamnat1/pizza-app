@extends('site.app')
@section('title', 'Shopping Cart')
@section('content')
    <section class="section-pagetop bg-dark">
        <div class="container clearfix">
            <h2 class="title-page">Cart</h2>
        </div>
    </section>
    <section class="section-content bg padding-y border-top">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    @if (Session::has('message'))
                        <p class="alert alert-success">{{ Session::get('message') }}</p>
                    @endif
                </div>
            </div>
            <div class="row">
                <main class="col-sm-9">
                    @if (\Cart::isEmpty())
                        <p class="alert alert-warning">Your shopping cart is empty.</p>
                    @else
                        <div class="card">
                            <table class="table table-hover shopping-cart-wrap">
                                <thead class="text-muted">
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Product</th>
                                    <th scope="col" width="120">Quantity</th>
                                    <th scope="col" width="120">Price</th>
                                    <th scope="col" class="text-right" width="200">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach(\Cart::getContent() as $item)
                                    <tr>
                                        <td>
                                            <var class="price">{{ $item->id }}</var>
                                        </td>
                                        <td>
                                            <figure class="media">
                                                <figcaption class="media-body">
                                                    <h6 class="title text-truncate">{{ Str::words($item->name,20) }}</h6>
                                                    @foreach($item->attributes as $key  => $value)
                                                        <dl class="dlist-inline small">
                                                            <dt>{{ ucwords($key) }}: </dt>
                                                            <dd>{{ ucwords($value) }}</dd>
                                                        </dl>
                                                    @endforeach
                                                </figcaption>
                                            </figure>
                                        </td>
                                        <td data-th="Quantity">
                                            <input style="width:50%" type="number" value="{{$item->quantity}}" class="quantity" />
                                            <button class="btn btn-info btn-sm update-cart" data-id="{{ $item->id }}"><i class="fa fa-refresh"></i></button>
                                            <i class="fa fa-circle-o-notch fa-spin btn-loading" style="font-size:24px; display: none"></i>
                                        </td>
                                        <td>
                                            <div class="price-wrap">
                                                <var class="price">{{ config('settings.currency_symbol'). $item->price }}</var>
                                                <small class="text-muted">each</small>
                                            </div>
                                        </td>
                                        <td class="text-right">
                                            <a href="{{ route('checkout.cart.remove', $item->id) }}" class="btn btn-outline-danger"><i class="fa fa-times"></i> </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </main>
                <aside class="col-sm-3" id="amount-panel">
                    @if (!\Cart::isEmpty())
                        <a href="{{ route('checkout.cart.clear') }}" class="btn btn-danger btn-block mb-4">Clear Cart</a>

                        <div id="HASH" class="blue-msg" style="display:flex;justify-content:space-between">
                            <span id="time-HASH" class="smalltext">Sub Total:</span>
                            <span class="text-right product-subtotal"><strong>{{ config('settings.currency_symbol') }}{{ \Cart::getSubTotal() }}</strong></span>
                        </div>
                        <div id="HASH" class="blue-msg" style="display:flex;justify-content:space-between">
                            <span id="time-HASH" class="smalltext">Delivery Charge:</span>
                            <span class="text-right"><strong>{{ config('settings.currency_symbol') }}{{ 10 }}</strong</span>
                        </div>
                        <div class="clearfix">&nbsp;</div>

                        {{--<p class="alert alert-success">Add USD 5.00 of eligible items to your order to qualify for FREE Shipping. </p>--}}
                        <dl class="dlist-align h4">
                            <dt>Total:</dt>
                            <dd class="text-right"><strong>{{ config('settings.currency_symbol') }}{{ \Cart::getTotal() }}</strong></dd>
                            <dd class="text-right cart-total"><strong>{{ config('settings.Dollar') }}{{ \Cart::getTotal() *  1.4389 }}</strong></dd>
                        </dl>

                        <hr>
                        <a href="{{ route('checkout.index') }}" class="btn btn-success btn-lg btn-block">Proceed To Checkout</a>
                    @endif
                </aside>
            </div>
        </div>
    </section>
@stop

@push('scripts')
    <script>
        $(document).ready(function () {

            $(".update-cart").click(function (e) {
                e.preventDefault();


                var ele = $(this);

                var parent_row = ele.parents("tr");

                var quantity = parent_row.find(".quantity").val();

                var product_subtotal = parent_row.find("span.product-subtotal");

                var cart_total = $(".cart-total");
                console.log("let ",parent_row,quantity,ele.attr("data-id"))

                var loading = parent_row.find(".btn-loading");

                loading.show();

                $.ajax({
                    url: '{{ url('update-cart') }}',
                    method: "post",
                    data: {_token: '{{ csrf_token() }}', productId: ele.attr("data-id"), qty: quantity},
                    dataType: "json",
                    success: function (response) {
                        console.log(response)
                        loading.hide();

                        $("span#status").html('<div class="alert alert-success">'+response.msg+'</div>');

                        $("#header-bar").html(response.data);

                        product_subtotal.text(response.subTotal);

                        cart_total.text(response.total);

                        $("#amount-panel").load(location.href + " #amount-panel>*", "");

                    },
                    error: function(request){
                        console.log(request)
                    }
                });
            });
        });
    </script>
@endpush
