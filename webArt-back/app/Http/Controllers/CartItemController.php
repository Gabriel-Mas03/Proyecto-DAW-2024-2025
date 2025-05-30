<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\OrderItem;

class CartItemController extends Controller
{
    public function index()
    {
        return CartItem::all();
    }
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
            'size' => 'nullable|string|max:10' 
        ]);

        $user = Auth::user();

        $item = CartItem::firstOrNew([
            'user_id'    => $user->id,
            'product_id' => $request->product_id,
            'size' => $request->size
        ]);

        $item->quantity += $request->quantity;
        $item->save();

        return response()->json(['message' => 'Producto añadido al carrito'], 201);
    }

    public function myCart()
    {
        $items = CartItem::with('product')
            ->where('user_id', Auth::id())
            ->get();

        return response()->json($items);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $item = CartItem::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $item->quantity = $request->quantity;
        $item->save();

        return response()->json(['message' => 'Cantidad actualizada']);
    }

    public function destroy($id)
    {
        $item = CartItem::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $item->delete();

        return response()->json(['message' => 'Producto eliminado del carrito']);
    }

public function checkout(Request $request)
{
    $request->validate([
        'items' => 'required|array',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.quantity' => 'required|integer|min:1',
    ]);

    $user = Auth::user();

    $cartItems = collect($request->items)->map(function ($item) {
        $product = Product::findOrFail($item['product_id']);
        return (object) [
            'product_id' => $product->id,
            'product' => $product,
            'quantity' => $item['quantity'],
        ];
    });

    if ($cartItems->isEmpty()) {
        return response()->json(['message' => 'El carrito está vacío'], 400);
    }

    $total = $cartItems->reduce(function ($carry, $item) {
        return $carry + ($item->product->price * $item->quantity);
    }, 0);

    $order = new Order();
    $order->user_id = $user->id;
    $order->total_amount = $total;
    $order->status = 'pendiente';
    $order->save();

    foreach ($cartItems as $item) {
        $orderItem = new OrderItem();
        $orderItem->order_id = $order->id;
        $orderItem->product_id = $item->product_id;
        $orderItem->quantity = $item->quantity;
        $orderItem->price = $item->product->price;
        $orderItem->save();
    }
    CartItem::where('user_id', $user->id)->delete();
    return response()->json([
        'message' => 'Compra realizada con éxito',
        'order_id' => $order->id,
        'total_amount' => $order->total_amount,
        'status' => $order->status,
        'items' => $cartItems->map(function ($item) {
            return [
                'product_id' => $item->product_id,
                'name' => $item->product->name,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
            ];
        }),
    ]);
}


}
