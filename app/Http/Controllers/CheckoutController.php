<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\Models\Order;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;

class CheckoutController extends Controller
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

    public function show(Request $request, Domain $domain, $componentId)
    {
        // Get the component from the domain
        $component = $domain->components()->findOrFail($componentId);
        
        // Check if the component is a template type
        if ($component->type !== 'template') {
            abort(404);
        }

        // Get the product details from the component properties
        $product = [
            'title' => $component->properties['title'] ?? 'Product Title',
            'description' => $component->properties['description'] ?? 'Product Description',
            'price' => $component->properties['price'] ?? 0,
            'image' => $component->properties['image'] ?? 'https://placehold.co/400x300',
        ];

        return view('frontend.checkout', compact('domain', 'product', 'component'));
    }

    public function process(Request $request, Domain $domain, $componentId)
    {
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
        ]);

        // Get the component
        $component = $domain->components()->findOrFail($componentId);
        
        if ($component->type !== 'template') {
            abort(404);
        }

        // Generate unique order ID
        $orderId = 'ORDER-' . time() . '-' . rand(1000, 9999);

        // Create order record
        $order = Order::create([
            'order_id' => $orderId,
            'domain_id' => $domain->id,
            'component_id' => $component->id,
            'customer_name' => $validated['name'],
            'customer_email' => $validated['email'],
            'customer_phone' => $validated['phone'],
            'customer_address' => $validated['address'],
            'product_title' => $component->properties['title'] ?? 'Product',
            'product_description' => $component->properties['description'] ?? '',
            'amount' => $component->properties['price'] ?? 0,
            'transaction_status' => 'pending',
        ]);

        // Prepare transaction details for Midtrans
        $transactionDetails = [
            'order_id' => $orderId,
            'gross_amount' => (int) $order->amount,
        ];

        // Item details
        $itemDetails = [
            [
                'id' => $component->id,
                'price' => (int) $order->amount,
                'quantity' => 1,
                'name' => $order->product_title,
            ]
        ];

        // Customer details
        $customerDetails = [
            'first_name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'billing_address' => [
                'address' => $validated['address'],
            ],
        ];

        // Transaction data
        $transactionData = [
            'transaction_details' => $transactionDetails,
            'item_details' => $itemDetails,
            'customer_details' => $customerDetails,
        ];

        try {
            // Validate Midtrans configuration
            if (empty(config('services.midtrans.server_key'))) {
                throw new \Exception('Midtrans Server Key is not configured. Please set MIDTRANS_SERVER_KEY in your .env file.');
            }
            
            if (empty(config('services.midtrans.client_key'))) {
                throw new \Exception('Midtrans Client Key is not configured. Please set MIDTRANS_CLIENT_KEY in your .env file.');
            }
            
            // Debug: Log transaction data
            \Log::info('Creating Midtrans transaction', [
                'order_id' => $orderId,
                'amount' => $order->amount,
                'transaction_data' => $transactionData
            ]);
            
            // Get Snap Token from Midtrans using createTransaction
            $snapResponse = \Midtrans\Snap::createTransaction($transactionData);
            
            // Debug: Log response
            \Log::info('Midtrans response received', [
                'response' => $snapResponse
            ]);
            
            if (!isset($snapResponse->token)) {
                throw new \Exception('Failed to get snap token from Midtrans. Response: ' . json_encode($snapResponse));
            }
            
            $snapToken = $snapResponse->token;
            
            // Save snap token to order
            $order->update(['snap_token' => $snapToken]);

            // Return JSON response with snap token
            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
                'order_id' => $orderId,
            ]);
        } catch (\Exception $e) {
            // Delete order if failed
            $order->delete();
            
            // Log the error for debugging
            \Log::error('Midtrans Payment Error', [
                'message' => $e->getMessage(),
                'order_id' => $orderId,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create payment: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function success(Request $request, $orderId)
    {
        $order = Order::where('order_id', $orderId)->firstOrFail();
        
        // Check payment status from Midtrans if still pending
        if ($order->transaction_status === 'pending') {
            try {
                // Get transaction status from Midtrans
                $status = \Midtrans\Transaction::status($orderId);
                
                // Update order based on Midtrans response
                if ($status->transaction_status === 'settlement' || $status->transaction_status === 'capture') {
                    $order->transaction_status = 'settlement';
                    $order->transaction_id = $status->transaction_id;
                    $order->payment_type = $status->payment_type;
                    $order->paid_at = now();
                    $order->midtrans_response = json_encode($status);
                    $order->save();
                    
                    \Log::info('Order status updated from Midtrans', [
                        'order_id' => $orderId,
                        'status' => 'settlement'
                    ]);
                } elseif (in_array($status->transaction_status, ['deny', 'cancel', 'expire'])) {
                    // Redirect to failed page if payment failed
                    return redirect()->route('payment.failed', $orderId);
                }
            } catch (\Exception $e) {
                \Log::error('Failed to check Midtrans status', [
                    'order_id' => $orderId,
                    'error' => $e->getMessage()
                ]);
                // Continue to show page even if status check fails
            }
        }
        
        return view('frontend.payment-success', compact('order'));
    }

    public function failed(Request $request, $orderId)
    {
        $order = Order::where('order_id', $orderId)->firstOrFail();
        
        return view('frontend.payment-failed', compact('order'));
    }
}