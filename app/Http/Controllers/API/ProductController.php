<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductImage;
use App\Helpers\APIResponse;
use Illuminate\Support\Facades\Validator;


class ProductController extends Controller
{
    /**
     * Add a new product along with multiple images.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function AddProduct(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust max file size as needed
        ]);


        // Create the product
        $product = Product::create([
            'name' => $request->input('name'),
            'user_id' => $request->input('user_id'),
            'description' => $request->input('description'),
            'price' => $request->input('price'),
            'quantity' => $request->input('quantity'),
        ]);

        // Upload and attach images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePath = $image->store('product_images');
                $productImage = new ProductImage([
                    'image_path' => $imagePath
                ]);
                $product->images()->save($productImage);
            }
        }

        return APIResponse::success(['product' => $product->load('images')], 'Product added successfully');
    }

    /**
     * Get all products belonging to the authenticated user with images.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function GetUserProducts(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
        ]);

        // Get all products belonging to the user with images (eager loading)
        $products = Product::with('images')->where('user_id', $request->user_id)->get();
        if ($products->isEmpty()) {
            return APIResponse::error('No products found for the authenticated user', 404);
        } else {
            return APIResponse::success(['products' => $products], 'Get all user product successfully');
        }
    }



    /**
     * Get all products with images.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function GetAllProducts(Request $request)
    {
        // Get all products belonging to the user with images (eager loading)
        $products = Product::with('images')->get();
        if ($products->isEmpty()) {
            return APIResponse::error('No products found', 404);
        } else {
            return APIResponse::success(['products' => $products], 'Get all product successfully');
        }
    }
}
