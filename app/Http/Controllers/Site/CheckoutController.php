<?php

namespace App\Http\Controllers\Site;

use Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Contracts\OrderContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\orderRequest;

class CheckoutController extends Controller
{


    protected $orderRepository;

    public function __construct(OrderContract $orderRepository)
    {

        $this->orderRepository = $orderRepository;
    }

    public function getCheckout()
    {
        return view('site.pages.checkout');
    }

    public function placeOrder(orderRequest $request)
    {

        return "ggg";
        $order = $this->orderRepository->storeOrderDetails($request->all());



        if ($order) {

            Cart::clear();
            return view('site.pages.success', compact('order'));
        }

        return redirect()->back()->with('message','Order not placed');
    }


}
