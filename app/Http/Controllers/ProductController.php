<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
   
    public function index()
    {
        $products = Product::all();
        return view('index' , compact('products'));
    }

    
    public function products()
    {
        $products = Product::all();
        return view('products' , compact('products'));
    }

    
    public function store(Request $request)
    {
        //
    }

   
    public function show(Product $product)
    {
        return view('single' , compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
