<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        $filters = [
            'category_id' => $request->query('category_id'),
            'sort_by_price' => $request->query('sort_by_price'),
        ];

        return $this->productService->getProducts($filters);;
    }
}
