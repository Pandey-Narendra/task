<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $products = Product::paginate(9);
            // return response()->json(['success_message' => $products]);
            return view('products.index', compact('products'));
        } catch (Exception $e) {
            return back()->with('error_message', 'Error fetching products: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            return view('products.create');
        } catch (Exception $e) {
            return back()->with('error_message', 'Error displaying product creation form: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'price' => 'required|numeric',
                'description' => 'nullable',
                'quantity' => 'required|integer',
                'image' => 'nullable|image',
            ]);

            $product = new Product();
            $product->name = $request->name;
            $product->price = $request->price;
            $product->description = $request->description;
            $product->quantity = $request->quantity;
            $product->user_id = Auth::id();

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('images', 'public');
                $product->image = $path;
            }

            $product->save();

            return redirect()->route('products.index')->with('success_message', 'Product created successfully!');
        } catch (Exception $e) {
            return back()->with('error_message', 'Error storing product: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        try {
            return view('products.show', compact('product'));
        } catch (Exception $e) {
            return back()->with('error_message', 'Error displaying product details: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        try {
            return view('products.edit', compact('product'));
        } catch (Exception $e) {
            return back()->with('error_message', 'Error displaying product edit form: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        try {
            $request->validate([
                'name' => 'required',
                'price' => 'required|numeric',
                'description' => 'nullable',
                'quantity' => 'required|integer',
                'image' => 'nullable|image',
            ]);

            $product->name = $request->name;
            $product->price = $request->price;
            $product->description = $request->description;
            $product->quantity = $request->quantity;

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('images', 'public');
                $product->image = $path;
            }

            $product->save();

            return redirect()->route('products.index')->with('success_message', 'Product updated successfully!');
        } catch (Exception $e) {
            return back()->with('error_message', 'Error updating product: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        try {
            $product->delete();
            return redirect()->route('products.index')->with('success_message', 'Product deleted successfully!');
        } catch (Exception $e) {
            return back()->with('error_message', 'Error deleting product: ' . $e->getMessage());
        }
    }
}
