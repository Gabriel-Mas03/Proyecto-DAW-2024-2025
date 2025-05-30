<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;



class OrderItemController extends Controller
{
    //
    public function createFromCart(Request $request)
{
    $user = Auth::user();
    $cartItems = CartItem::where('user_id', $user->id)->get();

    if ($cartItems->isEmpty()) {
        return response()->json(['message' => 'Carrito vacío'], 400);
    }

    $order = Order::create([
        'user_id' => $user->id,
        'total_amount' => 0, // se actualiza abajo
        'status' => 'pendiente',
    ]);

    $total = 0;

    foreach ($cartItems as $item) {
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $item->product_id,
            'quantity' => $item->quantity,
            'price' => $item->product->price,
        ]);

        $total += $item->quantity * $item->product->price;
    }

    $order->total_amount = $total;
    $order->save();

    // Vaciar carrito
    CartItem::where('user_id', $user->id)->delete();

    return response()->json(['message' => 'Orden creada', 'order' => $order]);
}

}
