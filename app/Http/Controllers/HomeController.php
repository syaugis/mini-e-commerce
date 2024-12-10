<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $assets = ['chart', 'animation'];
        $data['total_products'] = Product::count();
        $data['total_categories'] = ProductCategory::count();
        $data['total_orders'] = Order::count();
        $data['total_revenues'] = Order::where('status', '=', 3)->sum('total_price');

        $data['total_users'] = User::where('role', 1)->count();
        $data['new_users'] = User::where('role', 1)->whereDate('created_at', Carbon::today())->count();

        return view('admin.dashboard', compact('assets', 'data'));
    }
}
