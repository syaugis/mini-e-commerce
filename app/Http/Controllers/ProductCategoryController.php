<?php

namespace App\Http\Controllers;

use App\Services\ProductCategoryService;
use App\Services\DataTables\ProductCategoryDataTableService;
use App\Services\Exports\ProductCategoriesExportService;
use App\Services\Exports\ProductCategoriesTemplateService;
use App\Services\Imports\ProductCategoriesImportService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductCategoryController extends Controller
{
    protected $productCategoryService;
    protected $productCategoryDataTableService;
    protected $productCategoriesExportService;
    protected $productCategoriesTemplateService;
    protected $productCategoriesImportService;

    public function __construct(ProductCategoryService $productCategoryService, ProductCategoryDataTableService $productCategoryDataTableService, ProductCategoriesExportService $productCategoriesExportService, ProductCategoriesTemplateService $productCategoriesTemplateService, ProductCategoriesImportService $productCategoriesImportService)
    {
        $this->productCategoryService = $productCategoryService;
        $this->productCategoryDataTableService = $productCategoryDataTableService;
        $this->productCategoriesExportService = $productCategoriesExportService;
        $this->productCategoriesTemplateService = $productCategoriesTemplateService;
        $this->productCategoriesImportService = $productCategoriesImportService;
    }

    public function index(): View|JsonResponse|RedirectResponse
    {
        try {
            $assets = ['data-table'];
            $pageTitle = 'Product Category Data';
            $headerAction = [
                '<a href="' . route('admin.category.create') . '" class="btn btn-sm btn-primary" role="button">Add Category</a>',
                '<button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#ImportModalCategory"> Import Category </button>',
                '<a href="' . route('admin.category.export') . '" class="btn btn-sm btn-success" role="button">Export Category</a>',
            ];
            return $this->productCategoryDataTableService->render('admin.category.index', compact('assets', 'pageTitle', 'headerAction'));
        } catch (Exception $e) {
            $error = $e->getMessage();
            return back()->withErrors($error);
        }
    }

    public function export()
    {
        return $this->productCategoriesExportService->download('product_categories' . now() . '.xlsx');
    }

    public function template()
    {
        return $this->productCategoriesTemplateService->download('product_categories_template.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx',
        ]);
        $this->productCategoriesImportService->import($request->file('file'));

        return redirect()->route('admin.category.index')->withSuccess(__('global-message.save_form', ['form' => 'Category data']));
    }

    public function create(): View
    {
        return view('admin.category.form');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->only([
            'name',
        ]);

        $response = $this->productCategoryService->store($data);

        if (isset($response['error'])) {
            return back()->withErrors($response['error'])->withInput();
        }

        return redirect()->route('admin.category.index')->withSuccess(__('global-message.save_form', ['form' => 'Category data']));
    }

    public function edit($id): View
    {
        $data = $this->productCategoryService->getById($id);
        return view('admin.category.form', compact('data', 'id'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $data = $request->only([
            'name',
        ]);

        $response = $this->productCategoryService->update($data, $id);

        if (isset($response['error'])) {
            return back()->withErrors($response['error'])->withInput();
        }

        return redirect()->route('admin.category.index')->withSuccess(__('global-message.update_form', ['form' => 'Category data']));
    }

    public function destroy($id): RedirectResponse
    {
        $response = $this->productCategoryService->destroy($id);

        if (isset($response['error'])) {
            return back()->withErrors($response['error'])->withInput();
        }

        return redirect()->back()->withSuccess(__('global-message.delete_form', ['form' => 'Category data']));;
    }
}
