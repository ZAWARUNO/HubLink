<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DigitalProductController extends Controller
{
    /**
     * Generate secure download token for paid order
     */
    public function generateDownloadToken($orderId)
    {
        $order = Order::where('order_id', $orderId)->firstOrFail();
        
        // Check if order is paid
        if (!$order->isPaid()) {
            abort(403, 'Order is not paid yet');
        }
        
        // Generate secure token (valid for 24 hours)
        $token = Str::random(64);
        $expiresAt = now()->addHours(24);
        
        // Get existing midtrans_response (accessor will handle conversion)
        $midtransResponse = $order->midtrans_response;
        
        // Add download token
        $midtransResponse['download_token'] = $token;
        $midtransResponse['download_token_expires_at'] = $expiresAt->toDateTimeString();
        
        // Store token in order (mutator will handle conversion to JSON)
        $order->update([
            'midtrans_response' => $midtransResponse
        ]);
        
        return $token;
    }
    
    /**
     * Download digital product with token verification
     */
    public function download(Request $request, $orderId, $token)
    {
        $order = Order::where('order_id', $orderId)->firstOrFail();
        
        // Check if order is paid
        if (!$order->isPaid()) {
            abort(403, 'Order is not paid yet');
        }
        
        // Get midtrans_response as array
        $midtransResponse = $order->midtrans_response;
        if (!is_array($midtransResponse)) {
            $midtransResponse = [];
        }
        
        // Verify token
        $storedToken = $midtransResponse['download_token'] ?? null;
        $expiresAt = $midtransResponse['download_token_expires_at'] ?? null;
        
        if (!$storedToken || $storedToken !== $token) {
            abort(403, 'Invalid download token');
        }
        
        if ($expiresAt && now()->isAfter($expiresAt)) {
            abort(403, 'Download token has expired');
        }
        
        // Get component
        $component = $order->component;
        
        \Log::info('Attempting download', [
            'order_id' => $orderId,
            'component_id' => $component->id ?? null,
            'digital_product_path' => $component->digital_product_path ?? null
        ]);
        
        if (!$component) {
            \Log::error('Component not found for order', ['order_id' => $orderId]);
            abort(404, 'Product component not found');
        }
        
        if (!$component->digital_product_path) {
            \Log::error('Digital product path is empty', [
                'order_id' => $orderId,
                'component_id' => $component->id
            ]);
            abort(404, 'Digital product file not configured. Please contact the seller.');
        }
        
        // Check if file exists
        $filePath = $component->digital_product_path;
        $fileExists = Storage::exists($filePath);
        
        \Log::info('File check', [
            'path' => $filePath,
            'exists' => $fileExists,
            'disk' => config('filesystems.default')
        ]);
        
        if (!$fileExists) {
            \Log::error('Digital product file not found in storage', [
                'path' => $filePath,
                'order_id' => $orderId
            ]);
            abort(404, 'Digital product file not found. Please contact the seller.');
        }
        
        // Log download
        \Log::info('Digital product downloaded', [
            'order_id' => $orderId,
            'customer_email' => $order->customer_email,
            'product' => $order->product_title
        ]);
        
        // Download file
        return Storage::download(
            $component->digital_product_path,
            $order->product_title . '.' . pathinfo($component->digital_product_path, PATHINFO_EXTENSION)
        );
    }
    
    /**
     * Show download page with instructions
     */
    public function showDownloadPage($orderId)
    {
        $order = Order::where('order_id', $orderId)->firstOrFail();
        
        // Check payment status from Midtrans if still pending
        if ($order->transaction_status === 'pending') {
            try {
                // Initialize Midtrans config
                \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
                \Midtrans\Config::$isProduction = config('services.midtrans.is_production');
                
                if (!config('services.midtrans.is_production')) {
                    \Midtrans\Config::$curlOptions = [
                        CURLOPT_SSL_VERIFYHOST => 0,
                        CURLOPT_SSL_VERIFYPEER => 0,
                        CURLOPT_HTTPHEADER => [],
                    ];
                }
                
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
                    
                    \Log::info('Order status updated from Midtrans on download page', [
                        'order_id' => $orderId,
                        'status' => 'settlement'
                    ]);
                }
            } catch (\Exception $e) {
                \Log::error('Failed to check Midtrans status on download page', [
                    'order_id' => $orderId,
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        // Check if order is paid
        if (!$order->isPaid()) {
            return redirect()->route('payment.failed', $orderId)
                ->with('error', 'Payment is required to download the product');
        }
        
        // Get midtrans_response as array
        $midtransResponse = $order->midtrans_response;
        if (!is_array($midtransResponse)) {
            $midtransResponse = [];
        }
        
        // Generate download token if not exists or expired
        $token = $midtransResponse['download_token'] ?? null;
        $expiresAt = $midtransResponse['download_token_expires_at'] ?? null;
        
        if (!$token || ($expiresAt && now()->isAfter($expiresAt))) {
            $token = $this->generateDownloadToken($orderId);
        }
        
        $downloadUrl = route('digital-product.download', [
            'orderId' => $orderId,
            'token' => $token
        ]);
        
        return view('frontend.download', compact('order', 'downloadUrl'));
    }
}
