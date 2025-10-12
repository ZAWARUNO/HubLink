<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
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

        return view('frontend.checkout', compact('domain', 'product'));
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

        // Here you would typically:
        // 1. Create an order record
        // 2. Process payment (integrate with payment gateway)
        // 3. Send confirmation emails
        // 4. etc.

        // For now, we'll just return a success response
        return redirect()->back()->with('success', 'Order processed successfully! We will contact you shortly.');
    }
}