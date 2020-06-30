<?php

namespace App\Http\Controllers\Site;

use Cart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    public function getCart()
    {

        $condition1 = new \Darryldecode\Cart\CartCondition(array(
            'name' => 'Delivery charge €10',
            'type' => 'delivery',
            'target' => 'total', // this condition will be applied to cart's subtotal when getSubTotal() is called.
            'value' => '+10',
            'order' => 1
        ));
        Cart::condition($condition1);
//        Cart::clear();
//        return Cart::getTotal().' '.Cart::getSubTotal();
        return view('site.pages.cart');
    }

    public function removeItem($id)
    {
        Cart::remove($id);

        if (Cart::isEmpty()) {
            return redirect('/');
        }
        return redirect()->back()->with('message', 'Item removed from cart successfully.');
    }

    public function clearCart()
    {
        Cart::clear();

        return redirect('/');
    }

    public function updateCart(Request $request){
        if($request->input('qty') == 0)
        {
            Cart::remove($request->input('productId'));
            return response()->json(['msg' => 'Cart emptied successfully', 'total' => Cart::getTotal(), 'subTotal' => Cart::getSubTotal()]);

        }
        Cart::update($request->input('productId'), array(
            'quantity' => array(
                'relative' => false,
                'value' => $request->input('qty')
            )
            ));
        return response()->json(['msg' => 'Cart updated successfully', 'total' => Cart::getTotal(), 'subTotal' => Cart::getSubTotal()]);

    }
}
