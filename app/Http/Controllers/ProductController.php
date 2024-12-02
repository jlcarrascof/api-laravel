<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use App\Models\Product;

/**
 * @OA\Info(
 *     title="API Laravel Documentation",
 *     version="1.0.0",
 *     description="Project YouTube API Laravel 11 Documentation",
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
     * @OA\Post(
     *     path="/api/products",
     *     summary="Create a new product",
     *     description="Let create a new product with the ",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Product created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     )
     * )
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
    * @OA\Get(
    *     path="/api/products/{id}",
    *     summary="To get an specific product",
    *     description="Return a specific product using its ID",
    *     @OA\Parameter(
    *         name="id",
    *         in="path",
    *         description="ID of the product",
    *         required=true,
    *         @OA\Schema(type="integer", example=1)
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Product found",
    *         @OA\JsonContent(ref="#/components/schemas/Product")
    *     ),
    *     @OA\Response(
    *         response=404,
    *         description="Product not found"
    *     )
    * )
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
     * @OA\Put(
     *     path="/api/products/{id}",
     *     summary="Update a product",
     *     description="Update a product using its ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the product",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found"
     *     )
     * )
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
     * @OA\Delete(
     *     path="/api/products/{id}",
     *     summary="Delete a product",
     *     description="Delete a product using its ID from the database",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Product ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description=""
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found"
     *     )
     * )
    */
    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['message' => 'Product deleted successfully'], 200);
    }
}
