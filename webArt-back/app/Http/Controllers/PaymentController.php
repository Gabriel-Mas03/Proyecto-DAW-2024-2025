<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Payment;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PaymentController extends Controller
{
    //
    public function pay(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        $user = Auth::user();
        $order = Order::where('id', $request->order_id)
                      ->where('user_id', $user->id)
                      ->firstOrFail();

        if ($order->status === 'pagado') {
            return response()->json(['message' => 'Este pedido ya ha sido pagado.'], 400);
        }

        $payment = new Payment();
        $payment->user_id = $user->id;
        $payment->order_id = $order->id;
        $payment->amount = $order->total_amount;
        $payment->status = 'completado'; // Simulado
        $payment->save();

        $order->status = 'pagado';
        $order->save();

        return response()->json([
            'message' => 'Pago realizado con éxito.',
            'payment_id' => $payment->id,
            'order_status' => $order->status,
        ]);
        
    }
    public function createStripeSession(Request $request)
{
    $request->validate([
        'product_name' => 'required|string',
        'quantity' => 'required|integer|min:1',
        'price' => 'required|numeric',
    ]);

    Stripe::setApiKey(env('STRIPE_SECRET'));

    $session = Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency' => 'eur',
                'product_data' => [
                    'name' => $request->product_name,
                ],
                'unit_amount' => intval($request->price * 100), // en céntimos
            ],
            'quantity' => $request->quantity,
        ]],
        'mode' => 'payment',
        'success_url' => env('APP_URL2') . '/#/gracias',
        'cancel_url' => env('APP_URL2') . '/#/cancelado',
    ]);

    return response()->json(['url' => $session->url]);
}
}
