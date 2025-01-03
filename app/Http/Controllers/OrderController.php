<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use App\Services\DataTables\OrderDataTableService;
use App\Services\DataTables\OrderItemDataTableService;
use App\Services\Exports\OrdersExportService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    protected $orderService;
    protected $orderDataTableService;
    protected $orderItemDataTableService;
    protected $ordersExportService;

    public function __construct(OrderService $orderService, OrderDataTableService $orderDataTableService, OrderItemDataTableService $orderItemDataTableService, OrdersExportService $ordersExportService)
    {
        $this->orderService = $orderService;
        $this->orderDataTableService = $orderDataTableService;
        $this->orderItemDataTableService = $orderItemDataTableService;
        $this->ordersExportService = $ordersExportService;
    }

    public function index(): View|JsonResponse|RedirectResponse
    {
        try {
            $assets = ['data-table'];
            $pageTitle = 'Order Data';
            $headerAction = [
                '<a href="' . route('admin.order.export') . '" class="btn btn-sm btn-success" role="button">Export Order</a>',
            ];
            return $this->orderDataTableService->render('admin.order.index', compact('assets', 'pageTitle', 'headerAction'));
        } catch (Exception $e) {
            $error = $e->getMessage();
            return back()->withErrors($error);
        }
    }

    public function export()
    {
        return $this->ordersExportService->download('orders_' . now() . '.xlsx');
    }

    public function show($id): View|JsonResponse
    {
        $assets = ['data-table'];
        $pageTitle = 'Order Item List Data';
        $data = $this->orderService->getById($id);

        return $this->orderItemDataTableService->withOrderId($id)->render('admin.order.show', compact('assets', 'pageTitle', 'data', 'id'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $data = $request->only([
            'status',
        ]);

        $response = $this->orderService->update($data, $id);

        if (isset($response['error'])) {
            return back()->withErrors($response['error'])->withInput();
        }

        $redirectRoute = $request->input('redirect') ?? route('admin.order.index');

        return redirect($redirectRoute)
            ->withSuccess(__('global-message.update_form', ['form' => 'Order data']));
    }
}
