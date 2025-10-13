<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Failed</title>
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
                <!-- Failed Icon -->
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-6">
                    <svg class="h-10 w-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>

                <!-- Failed Message -->
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Payment Failed</h1>
                <p class="text-gray-600 mb-6">Unfortunately, your payment could not be processed.</p>

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
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                {{ ucfirst($order->transaction_status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Info Message -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                    <p class="text-sm text-yellow-800">
                        <strong>What happened?</strong><br>
                        Your payment was not completed. This could be due to insufficient funds, 
                        incorrect payment details, or a cancelled transaction.
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-3">
                    <a href="{{ route('checkout.show', ['domain' => $order->domain, 'componentId' => $order->component_id]) }}" 
                       class="inline-block w-full bg-primary hover:bg-primary-dark text-white font-semibold py-3 px-6 rounded-lg transition-colors">
                        Try Again
                    </a>
                    <a href="{{ url('/' . $order->domain->slug) }}" 
                       class="inline-block w-full border border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold py-3 px-6 rounded-lg transition-colors">
                        Back to Store
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
