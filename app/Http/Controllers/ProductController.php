<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResponse;
use App\Models\Product;
use App\Traits\HttpResponses;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use HttpResponses;
    //
    public function get_all_products()
    {
        try {
            $products = Product::paginate(10);
            if ($products->isEmpty()) {
                return $this->error('', 'No products found.', 404);
            }

            return ProductResponse::collection($products);
        } catch (ModelNotFoundException $exception) {
            return $this->error('', $exception->getMessage(), 404);
        }
    }
    public function get_product_by_id($id)
    {
        try {
            $product = Product::findOrFail($id);
            return  new ProductResponse($product);
        } catch (\Exception $e) {
            return $this->error('', 'No products found.', 404);
        }
    }
    public function filter_products($searchString)
    {
        try {
            $products = Product::where('name', 'like', '%' . $searchString . '%')
                ->orWhere('description', 'like', '%' . $searchString . '%')
                ->get();
            if ($products->count() == 0) {
                return $this->error('', 'No products found.', 404);
            }
            return ProductResponse::collection($products);
        } catch (\Exception $th) {
            return $this->error('', 'No products found.', 404);
        }
    }
}
