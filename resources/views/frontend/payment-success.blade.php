<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#00c499',
                        'primary-dark': '#00a881'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full">
            <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                <!-- Success Icon -->
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-6">
                    <svg class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>

                <!-- Success Message -->
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Payment Successful!</h1>
                <p class="text-gray-600 mb-6">Thank you for your purchase. Your order has been received.</p>

                <!-- Order Details -->
                <div class="bg-gray-50 rounded-lg p-6 mb-6 text-left">
                    <h2 class="font-semibold text-gray-900 mb-4">Order Details</h2>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Order ID:</span>
                            <span class="font-medium text-gray-900">{{ $order->order_id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Product:</span>
                            <span class="font-medium text-gray-900">{{ $order->product_title }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Amount:</span>
                            <span class="font-medium text-gray-900">Rp {{ number_format($order->amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Status:</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ ucfirst($order->transaction_status) }}
                            </span>
                        </div>
                        @if($order->paid_at)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Paid At:</span>
                            <span class="font-medium text-gray-900">{{ $order->paid_at->format('d M Y, H:i') }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Customer Info -->
                <div class="bg-gray-50 rounded-lg p-6 mb-6 text-left">
                    <h2 class="font-semibold text-gray-900 mb-4">Customer Information</h2>
                    <div class="space-y-2 text-sm">
                        <div>
                            <span class="text-gray-600">Name:</span>
                            <p class="font-medium text-gray-900">{{ $order->customer_name }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600">Email:</span>
                            <p class="font-medium text-gray-900">{{ $order->customer_email }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600">Phone:</span>
                            <p class="font-medium text-gray-900">{{ $order->customer_phone }}</p>
                        </div>
                    </div>
                </div>

                <!-- Download Section -->
                @if($order->component && $order->component->digital_product_path)
                <div class="bg-gradient-to-r from-primary to-primary-dark rounded-lg p-6 mb-6 text-white">
                    <div class="flex items-center mb-3">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                        </svg>
                        <h3 class="text-lg font-semibold">Your Digital Product is Ready!</h3>
                    </div>
                    <p class="text-sm mb-4 opacity-90">Click the button below to download your product. The download link is valid for 24 hours.</p>
                    <a href="{{ route('digital-product.page', $order->order_id) }}" 
                       class="inline-block w-full bg-white text-primary hover:bg-gray-100 font-semibold py-3 px-6 rounded-lg transition-colors text-center">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Download Now
                    </a>
                </div>
                @endif

                <!-- Info Message -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <p class="text-sm text-blue-800">
                        <strong>Note:</strong> A confirmation email with download link has been sent to {{ $order->customer_email }}. 
                        Please check your inbox for order details.
                    </p>
                </div>

                <!-- Action Button -->
                <a href="{{ url('/' . $order->domain->slug) }}" class="inline-block w-full border border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold py-3 px-6 rounded-lg transition-colors text-center">
                    Back to Store
                </a>
            </div>
        </div>
    </div>
</body>
</html>
