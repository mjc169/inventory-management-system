<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;

class PosController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with(['category', 'unit'])->get();

        $users = User::all()->sortBy('name');

        $carts = Cart::content();

        return view('pos.index', [
            'products' => $products,
            'users' => $users,
            'carts' => $carts,
        ]);
    }

    public function addCartItem(Request $request)
    {
        $request->all();
        //dd($request);

        $rules = [
            'id' => 'required|numeric',
            'name' => 'required|string',
            'selling_price' => 'required|numeric',
        ];

        $validatedData = $request->validate($rules);

        //        Cart::add([
        //            'id'        => $validatedData['id'],
        //            'name'      => $validatedData['name'],
        //            'qty'       => 1,
        //            'price'     => $validatedData['selling_price'],
        //            'weight'    => 1,
        //            //'options' => []
        //        ]);

        Cart::add(
            $validatedData['id'],
            $validatedData['name'],
            1,
            $validatedData['selling_price'],
            1,
            (array)$options = null
        );

        return redirect()
            ->back()
            ->with('success', 'Product has been added to cart!');
    }

    public function resellerAddCartItem(Request $request)
    {
        $request->all();
        //dd($t); exit;

        $rules = [
            'id' => 'required|numeric',
            'name' => 'required|string',
            'unit' => 'required|string',
            'selling_price' => 'required|numeric',
            'qty' => 'required|numeric',
        ];

        $validatedData = $request->validate($rules);

        Cart::add(
            $validatedData['id'],
            $validatedData['name'],
            $validatedData['qty'],
            $validatedData['selling_price'],
            1,
            (array)$options = ['size' => $validatedData['unit']]
        );

        return redirect()->route('reseller.showCart');
    }

    public function updateCartItem(Request $request, $rowId)
    {
        $rules = [
            'quantity' => 'required|numeric',
        ];

        $validatedData = $request->validate($rules);

        Cart::update($rowId, $validatedData['quantity']);

        return redirect()
            ->back()
            ->with('success', 'Product has been updated from cart!');
    }

    public function deleteCartItem(String $rowId)
    {
        Cart::remove($rowId);

        return redirect()
            ->back()
            ->with('success', 'Product has been deleted from cart!');
    }
}
