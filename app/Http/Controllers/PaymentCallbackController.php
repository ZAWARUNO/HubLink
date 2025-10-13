<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Notification;

class PaymentCallbackController extends Controller
{
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized');
        Config::$is3ds = config('services.midtrans.is_3ds');
        
        // Disable SSL verification for local development (NOT for production!)
        if (!config('services.midtrans.is_production')) {
            Config::$curlOptions = [
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_HTTPHEADER => [],
            ];
        }
    }

    public function handle(Request $request)
    {
        try {
            // Log incoming notification
            \Log::info('Midtrans notification received', [
                'payload' => $request->all()
            ]);
            
            // Create notification instance
            $notification = new Notification();

            // Get transaction details
            $transactionStatus = $notification->transaction_status;
            $orderId = $notification->order_id;
            $fraudStatus = $notification->fraud_status;
            $paymentType = $notification->payment_type;
            
            \Log::info('Processing Midtrans notification', [
                'order_id' => $orderId,
                'transaction_status' => $transactionStatus,
                'payment_type' => $paymentType
            ]);

            // Find order
            $order = Order::where('order_id', $orderId)->first();

            if (!$order) {
                \Log::error('Order not found in callback', ['order_id' => $orderId]);
                return response()->json(['message' => 'Order not found'], 404);
            }

            // Update order with transaction details
            $order->transaction_id = $notification->transaction_id;
            $order->payment_type = $paymentType;
            $order->midtrans_response = json_encode($notification);

            // Handle transaction status
            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'accept') {
                    $order->transaction_status = 'settlement';
                    $order->paid_at = now();
                }
            } elseif ($transactionStatus == 'settlement') {
                $order->transaction_status = 'settlement';
                $order->paid_at = now();
            } elseif ($transactionStatus == 'pending') {
                $order->transaction_status = 'pending';
            } elseif ($transactionStatus == 'deny') {
                $order->transaction_status = 'deny';
            } elseif ($transactionStatus == 'expire') {
                $order->transaction_status = 'expire';
            } elseif ($transactionStatus == 'cancel') {
                $order->transaction_status = 'cancel';
            }

            $order->save();
            
            \Log::info('Order status updated successfully', [
                'order_id' => $orderId,
                'new_status' => $order->transaction_status
            ]);

            return response()->json(['message' => 'Notification handled successfully']);
        } catch (\Exception $e) {
            \Log::error('Error handling Midtrans notification', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}
