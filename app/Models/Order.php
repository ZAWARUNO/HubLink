<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'domain_id',
        'component_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'product_title',
        'product_description',
        'amount',
        'payment_type',
        'transaction_id',
        'transaction_status',
        'paid_at',
        'snap_token',
        'midtrans_response'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime'
    ];

    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }

    public function component()
    {
        return $this->belongsTo(Component::class);
    }

    public function isPaid()
    {
        return $this->transaction_status === 'settlement';
    }

    public function isPending()
    {
        return $this->transaction_status === 'pending';
    }
    
    /**
     * Get midtrans_response as array (handle both string and array)
     */
    public function getMidtransResponseAttribute($value)
    {
        // If null, return empty array
        if ($value === null) {
            return [];
        }
        
        // If already an array, return it
        if (is_array($value)) {
            return $value;
        }
        
        // If string, try to decode
        if (is_string($value)) {
            // If empty string, return empty array
            if (trim($value) === '') {
                return [];
            }
            
            $decoded = json_decode($value, true);
            
            // If decode successful and result is array, return it
            if (is_array($decoded)) {
                return $decoded;
            }
            
            // If decode failed, might be double-encoded, try again
            if (is_string($decoded)) {
                $doubleDecoded = json_decode($decoded, true);
                if (is_array($doubleDecoded)) {
                    return $doubleDecoded;
                }
            }
            
            // If still failed, log error and return empty array
            \Log::warning('Failed to decode midtrans_response', [
                'value' => substr($value, 0, 100),
                'error' => json_last_error_msg()
            ]);
            
            return [];
        }
        
        // Fallback to empty array
        return [];
    }
    
    /**
     * Set midtrans_response (always store as JSON string)
     */
    public function setMidtransResponseAttribute($value)
    {
        // If null or empty, store null
        if (empty($value)) {
            $this->attributes['midtrans_response'] = null;
            return;
        }
        
        // If array, encode to JSON
        if (is_array($value)) {
            $this->attributes['midtrans_response'] = json_encode($value);
            return;
        }
        
        // If already string (assume it's JSON), store as is
        if (is_string($value)) {
            $this->attributes['midtrans_response'] = $value;
            return;
        }
        
        // Fallback: try to encode whatever it is
        $this->attributes['midtrans_response'] = json_encode($value);
    }
}
