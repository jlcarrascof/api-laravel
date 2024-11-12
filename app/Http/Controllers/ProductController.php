<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Product::query();

        $products = Product::paginate(5);
        return response()->json($products);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'nullable|integer|exists:categories,id'
        ], [
            'name.required' => 'Product name is mandatory.',
            'name.max' => 'Product name only support until 255 characters.',
            'price.required' => 'Price is mandatory and must be a positive number.',
            'price.min' => 'Price cant be a negative number.',
            'stock.required' => 'Stock is mandatory and must be a positive number.',
            'stock.min' => 'Stock cant be a negative number.',
            'category_id.exists' => 'Category selected doesnt exists.'
        ]);

        $product = Product::create($validated);
        return response()->json([
            'status' => 'success',
            'message' => 'Product created successfully',
            'data' => $product
        ], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return response()->json($product);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'string',
            'description' => 'nullable|string',
            'price' => 'numeric',
            'stock' => 'integer',
            'category_id' => 'nullable|integer'
        ]);

        $product->update($validated);
        return response()->json($product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['message' => 'Product deleted successfully']);
    }
}
