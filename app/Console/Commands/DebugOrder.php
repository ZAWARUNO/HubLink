<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;

class DebugOrder extends Command
{
    protected $signature = 'order:debug {orderId?}';
    protected $description = 'Debug order midtrans_response';

    public function handle()
    {
        $orderId = $this->argument('orderId');
        
        if ($orderId) {
            $order = Order::where('order_id', $orderId)->first();
            
            if (!$order) {
                $this->error("Order not found: {$orderId}");
                return 1;
            }
            
            $this->info("Order ID: {$order->order_id}");
            $this->info("Transaction Status: {$order->transaction_status}");
            $this->info("Component ID: " . ($order->component_id ?? 'null'));
            
            if ($order->component) {
                $this->info("Component Type: " . $order->component->type);
                $this->info("Digital Product Path: " . ($order->component->digital_product_path ?? 'null'));
                
                if ($order->component->digital_product_path) {
                    $exists = \Storage::exists($order->component->digital_product_path);
                    $this->info("File Exists: " . ($exists ? 'Yes' : 'No'));
                    
                    if (!$exists) {
                        $this->warn("File not found in storage!");
                        $this->info("Looking in: storage/app/" . $order->component->digital_product_path);
                    }
                }
            } else {
                $this->warn("Component not found!");
            }
            
            $this->newLine();
            
            // Get raw value from database
            $rawValue = $order->getAttributes()['midtrans_response'] ?? null;
            $this->info("Raw DB Value Type: " . gettype($rawValue));
            $this->info("Raw DB Value: " . substr($rawValue, 0, 200));
            
            // Get via accessor
            $accessorValue = $order->midtrans_response;
            $this->info("Accessor Value Type: " . gettype($accessorValue));
            $this->info("Accessor Value: " . json_encode($accessorValue));
            
            if (is_array($accessorValue)) {
                $this->info("Keys: " . implode(', ', array_keys($accessorValue)));
            }
        } else {
            // Show all orders
            $orders = Order::latest()->take(5)->get();
            
            $this->table(
                ['Order ID', 'Status', 'Response Type', 'Has Token'],
                $orders->map(function ($order) {
                    $response = $order->midtrans_response;
                    return [
                        $order->order_id,
                        $order->transaction_status,
                        gettype($response),
                        isset($response['download_token']) ? 'Yes' : 'No'
                    ];
                })
            );
        }
        
        return 0;
    }
}
