<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Auth;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return response()->json(['success' => 'index controller']);
        try {
            $cartItems = Cart::where('user_id', Auth::id())->with('product')->paginate(4);
            // return response()->json(['success' => $cartItems]);
            return view('cart.index', compact('cartItems'));
        } catch (Exception $e) {
            return back()->withError('error_message: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return response()->json(['success' => $request->product_id]);
        try {
            $product = Product::findOrFail($request->product_id);
            $cartItem = Cart::where('user_id', Auth::id())->where('product_id', $product->id)->first();

            // return response()->json(['success' =>  $cartItem]);
            if ($cartItem) {
                $cartItem->quantity += 1;
                if ($cartItem->quantity > $product->quantity) {
                    return response()->json(['error' => 'Requested quantity exceeds available quantity for this product.'], 400);
                }
                $cartItem->save();
            } else {
                Cart::create([
                    'user_id' => Auth::id(),
                    'product_id' => $product->id,
                    'quantity' => 1,
                ]);
            }

            return response()->json(['success' => 'Product added to cart']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Product not found.'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error adding product to cart: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $cartItem = Cart::findOrFail($id);
            $product = Product::findOrFail($cartItem->product_id);
            // return response()->json(['success' => $product]);
            if ($request->quantity > $product->quantity) {
                return response()->json(['error' => 'Requested quantity exceeds available quantity for this product.'], 400);
            }

            $cartItem->quantity = $request->quantity;
            $cartItem->save();

            return response()->json(['success' => 'Cart updated']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Cart item not found.'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error updating cart item: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id )
    {
        try {
            $cartItem = Cart::findOrFail($id);
            // return response()->json(['success' => $cartItem]);
            $cartItem->delete();
            return  redirect()->back()->with('success_message', 'Cart Item deleted successfully!');
        } catch (ModelNotFoundException $e) {
            return  redirect()->back()->with('error_message', 'Error deleting product: ' . $e->getMessage());
        } catch (Exception $e) {
            return  redirect()->back()->with('error_message', 'Error deleting product: ' . $e->getMessage());
        }
    }
}
