<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use App\Models\Product;

/**
 * @OA\Info(
 *     title="Project YouTube API Laravel 11 Documentation",
 *     version="1.0.0",
 *     description="API documentation for my Laravel project",
 *     @OA\Contact(
 *         email="your-email@gmail.com"
 *     )
 * )
*/

class ProductController extends Controller
{
    /**
    * @OA\Get(
    *     path="/api/products",
    *     summary="Get all products",
    *     description="Return a list of products using pagination.",
    *     @OA\Response(
    *         response=200,
    *         description="Products List.",
    *         @OA\JsonContent(
    *             type="object",
    *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Product"))
     *         )
    *     )
    * )
    */

    public function index(Request $request)
    {
        $query = Product::with('category');

        // Filter by name

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        // Filter by price range

        if ($request->has('min_price') && $request->has('max_price')) {
            $query->whereBetween('price', [$request->input('min_price'), $request->input('max_price')]);
        }

        // Filter by category

        if ($request->has('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        $products = $query->paginate(5);
        return ProductResource::collection($products);
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
        return new ProductResource($product->load('category'));
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
            'category_id' => 'nullable|integer|exists:categories,id'
        ]);

        $product->update($validated);
        return new ProductResource($product->load('category'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['message' => 'Product deleted successfully'], 200);
    }
}
