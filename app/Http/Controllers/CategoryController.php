<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResponse;
use App\Models\Category;
use App\Models\Product;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use HttpResponses;
    //
    public function get_products_by_category($id)
    {
        try {
            $category = Category::findOrFail($id);
            return  ProductResponse::collection($category->products);
        } catch (\Exception $e) {
            return $this->error('', 'No category found.', 404);
        }
    }
}
