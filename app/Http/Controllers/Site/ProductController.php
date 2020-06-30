<?php

namespace App\Http\Controllers\Site;

use Cart;
use Illuminate\Http\Request;
use App\Contracts\ProductContract;
use App\Http\Controllers\Controller;
use App\Contracts\AttributeContract;

class ProductController extends Controller
{
    protected $productRepository;

    protected $attributeRepository;

    public function __construct(ProductContract $productRepository, AttributeContract $attributeRepository)
    {
        $this->productRepository = $productRepository;
        $this->attributeRepository = $attributeRepository;
    }

    public function show($id)
    {
        $product = $this->productRepository->findProductById($id);
        $attributes = $this->attributeRepository->listAttributes();

        return view('site.pages.product', compact('product', 'attributes'));
    }

    public function addToCart(Request $request)
    {
        $product = $this->productRepository->findProductById($request->input('productId'));
        $options = $request->except('_token', 'productId', 'price', 'qty');
//        Cart::add(uniqid(), $product->name, $request->input('price'), $request->input('qty'), $options);
        Cart::add($product->sku, $product->name, $product->price, $request->input('qty'), $options);
        return response()->json(['msg' => 'Item added to cart successfully']);
//        return redirect()->back()->with('message', 'Item added to cart successfully.');
    }

    public function searchProduct(Request $request){
        $category = $this->productRepository->searchProduct($request->get("search_name"));
        return view('site.pages.homepage', compact('category'));
        return $request->get("search_name");
    }

}
