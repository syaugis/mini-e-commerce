<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use App\Services\ProductService;
use App\Services\DataTables\ProductDataTableService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    protected $productService;
    protected $productDataTableService;

    public function __construct(ProductService $productService, ProductDataTableService $productDataTableService)
    {
        $this->productService = $productService;
        $this->productDataTableService = $productDataTableService;
    }

    public function index()
    {
        try {
            $assets = ['data-table'];
            $pageTitle = 'Product Data';
            $headerAction = '<a href="' . route('admin.product.create') . '" class="btn btn-sm btn-primary" role="button">Add Product</a>';
            return $this->productDataTableService->render('admin.product.index', compact('assets', 'pageTitle', 'headerAction'));
        } catch (Exception $e) {
            $error = $e->getMessage();
            return back()->withErrors($error);
        }
    }

    public function create(): View
    {
        $product_categories = ProductCategory::pluck('name', 'id');
        return view('admin.product.form', compact('product_categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->only([
            'name',
            'description',
            'price',
            'stock',
            'category_id',
        ]);
        $data['images'] = $request->file('images');

        $response = $this->productService->store($data);

        if (isset($response['error'])) {
            return back()->withErrors($response['error'])->withInput();
        }

        return redirect()->route('admin.product.index')->withSuccess(__('global-message.save_form', ['form' => 'Product data']));
    }

    public function edit($id): View
    {
        $product_categories = ProductCategory::pluck('name', 'id');
        $data = $this->productService->getById($id);
        return view('admin.product.form', compact('data', 'id', 'product_categories'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $data = $request->only([
            'name',
            'description',
            'price',
            'stock',
            'category_id',
            'images',
        ]);
        $data['images'] = $request->file('images');

        $response = $this->productService->update($data, $id);

        if (isset($response['error'])) {
            return back()->withErrors($response['error'])->withInput();
        }

        return redirect()->route('admin.product.index')->withSuccess(__('global-message.update_form', ['form' => 'Product data']));
    }

    public function destroy($id): RedirectResponse
    {
        $response = $this->productService->destroy($id);

        if (isset($response['error'])) {
            return back()->withErrors($response['error'])->withInput();
        }

        return redirect()->back()->withSuccess(__('global-message.delete_form', ['form' => 'Product data']));;
    }
}
