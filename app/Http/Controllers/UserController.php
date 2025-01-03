<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Services\DataTables\UserDataTableService;
use App\Services\DataTables\OrderDataTableService;
use App\Services\DataTables\ShippingAddressDataTableService;
use App\Services\Exports\UsersExportService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    protected $userService;
    protected $userDataTableService;
    protected $usersExportService;
    protected $orderDataTableService;
    protected $shippingAddressDataTableService;

    public function __construct(UserService $userService, UserDataTableService $userDataTableService, UsersExportService $usersExportService, OrderDataTableService $orderDataTableService, ShippingAddressDataTableService $shippingAddressDataTableService)
    {
        $this->userService = $userService;
        $this->userDataTableService = $userDataTableService;
        $this->usersExportService = $usersExportService;
        $this->orderDataTableService = $orderDataTableService;
        $this->shippingAddressDataTableService = $shippingAddressDataTableService;
    }

    public function index(): View|JsonResponse|RedirectResponse
    {
        try {
            $assets = ['data-table'];
            $pageTitle = 'User Data';
            $headerAction = [
                '<a href="' . route('admin.user.export') . '" class="btn btn-sm btn-success" role="button">Export User</a>',
            ];
            return $this->userDataTableService->render('admin.user.index', compact('assets', 'pageTitle', 'headerAction'));
        } catch (Exception $e) {
            $error = $e->getMessage();
            return back()->withErrors($error);
        }
    }

    public function export()
    {
        return $this->usersExportService->download('users_' . now() . '.xlsx');
    }

    public function show($id): View|JsonResponse
    {
        $assets = ['data-table'];
        $shippingTitle = 'User Address Shipping List Data';
        $orderTitle = 'User Order List Data';
        $data = $this->userService->getById($id);

        $shippingAddressTable = $this->shippingAddressDataTableService
            ->withUserId($id)
            ->html();
        $orderTable = $this->orderDataTableService
            ->withUserId($id)
            ->html();

        return view('admin.user.show', compact(
            'assets',
            'shippingTitle',
            'orderTitle',
            'data',
            'id',
            'shippingAddressTable',
            'orderTable'
        ));
    }

    public function getShippingAddresses($id): View|JsonResponse
    {
        return $this->shippingAddressDataTableService->withUserId($id)->render('admin.user.show');
    }

    public function getOrders($id): View|JsonResponse
    {
        return $this->orderDataTableService->withUserId($id)->render('admin.user.show');
    }
}
