<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fix any midtrans_response that might be stored incorrectly
        DB::table('orders')
            ->whereNotNull('midtrans_response')
            ->orderBy('id')
            ->each(function ($order) {
                $response = $order->midtrans_response;
                
                // If it's already valid JSON, skip
                if (is_string($response)) {
                    $decoded = json_decode($response, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        return; // Already valid JSON
                    }
                }
                
                // If it's not valid JSON, try to fix it
                if (is_string($response) && !empty($response)) {
                    DB::table('orders')
                        ->where('id', $order->id)
                        ->update(['midtrans_response' => json_encode(['raw' => $response])]);
                }
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse
    }
};
