<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $orders = Order::where('user_id', $user->id)
    ->select('id', 'total_amount as total', 'status', 'created_at')
    ->get();


        return response()->json($orders);
    }
    
}
