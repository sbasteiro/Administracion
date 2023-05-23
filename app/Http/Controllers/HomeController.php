<?php

namespace App\Http\Controllers;

use App\Models\Shipping;
use Illuminate\Http\Request;

class HomeController extends Controller
{
	public function index(Request $request)
    {
        $to_search = $request->input('q') ?? '';
        $context = $request->input('context') ?? '';
        $order_by = $request->input('order_by');
        $shipping = Shipping::with('zone');
        switch ($context) {
            case 'pending':
                $shipping->where('status', 'pending');
                break;
            case 'delivered':
                $shipping->where('status', 'delivered');
                break;
            case 'deleted':
                $shipping->onlyTrashed();
                break;
            default:
                $shipping->whereNull('deleted_at');
                break;
        }
        if ($to_search){
            $shipping->where('address', 'like', '%' . $to_search . '%')
                ->orWhere('id_shipping', $to_search)
                ->orWhere('buyer_name', 'like', '%' . $to_search . '%');
        }
        if ($order_by){
            $shipping->orderBy('created_at', $order_by);
        }
        $orders_shipping = $shipping->paginate(50);
        $pending_count = Shipping::where('status', 'pending')->count();
        $delivered_count = Shipping::where('status', 'delivered')->count();
        $deleted_count = Shipping::onlyTrashed()->count();
        return view('home', compact(
            'orders_shipping',
            'pending_count',
            'delivered_count',
            'deleted_count',
            'context',
            'to_search',
            'order_by'
        ));
    }
}
