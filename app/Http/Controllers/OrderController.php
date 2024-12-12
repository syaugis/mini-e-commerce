<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use App\Services\DataTables\OrderDataTableService;
use App\Services\DataTables\OrderItemDataTableService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class OrderController extends Controller
{
    protected $orderService;
    protected $orderDataTableService;
    protected $orderItemDataTableService;

    public function __construct(OrderService $orderService, OrderDataTableService $orderDataTableService, OrderItemDataTableService $orderItemDataTableService)
    {
        $this->orderService = $orderService;
        $this->orderDataTableService = $orderDataTableService;
        $this->orderItemDataTableService = $orderItemDataTableService;
    }

    public function index(): View|JsonResponse
    {
        try {
            $assets = ['data-table'];
            $pageTitle = 'Order Data';
            return $this->orderDataTableService->render('admin.order.index', compact('assets', 'pageTitle'));
        } catch (Exception $e) {
            $error = $e->getMessage();
            return back()->withErrors($error);
        }
    }

    public function show($id): View|JsonResponse
    {
        $assets = ['data-table'];
        $pageTitle = 'Order Item List Data';
        $data = $this->orderService->getById($id);

        return $this->orderItemDataTableService->withOrderId($id)->render('admin.order.show', compact('assets', 'pageTitle', 'data', 'id'));
    }
}
