<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Download Your Product</title>
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
        <div class="max-w-2xl w-full">
            <div class="bg-white rounded-lg shadow-lg p-8">
                <!-- Header -->
                <div class="text-center mb-8">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-primary bg-opacity-10 mb-4">
                        <svg class="h-10 w-10 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">Download Your Digital Product</h1>
                    <p class="text-gray-600">Thank you for your purchase!</p>
                </div>

                <!-- Product Info -->
                <div class="bg-gray-50 rounded-lg p-6 mb-6">
                    <h2 class="font-semibold text-gray-900 mb-4">Product Details</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between items-start">
                            <span class="text-gray-600">Product:</span>
                            <span class="font-medium text-gray-900 text-right">{{ $order->product_title }}</span>
                        </div>
                        @if($order->product_description)
                        <div class="flex justify-between items-start">
                            <span class="text-gray-600">Description:</span>
                            <span class="text-gray-900 text-right text-sm">{{ $order->product_description }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between items-start">
                            <span class="text-gray-600">Order ID:</span>
                            <span class="font-medium text-gray-900">{{ $order->order_id }}</span>
                        </div>
                        <div class="flex justify-between items-start">
                            <span class="text-gray-600">Purchase Date:</span>
                            <span class="font-medium text-gray-900">{{ $order->paid_at->format('d M Y, H:i') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Download Button -->
                <div class="mb-6">
                    <a href="{{ $downloadUrl }}" 
                       class="flex items-center justify-center w-full bg-primary hover:bg-primary-dark text-white font-semibold py-4 px-6 rounded-lg transition-colors">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Download Product
                    </a>
                </div>

                <!-- Important Notes -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                    <h3 class="font-semibold text-yellow-900 mb-2 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Important Notes
                    </h3>
                    <ul class="text-sm text-yellow-800 space-y-1 ml-7">
                        <li>• Download link is valid for 24 hours from purchase</li>
                        <li>• Save the file to a secure location after download</li>
                        <li>• If you have any issues, contact support with your Order ID</li>
                        <li>• A copy of this download link has been sent to your email</li>
                    </ul>
                </div>

                <!-- Customer Support -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h3 class="font-semibold text-gray-900 mb-2">Need Help?</h3>
                    <p class="text-sm text-gray-600">
                        If you encounter any problems downloading your product, please contact the seller at 
                        <a href="mailto:{{ $order->domain->user->email ?? 'support@example.com' }}" class="text-primary hover:underline">
                            {{ $order->domain->user->email ?? 'support@example.com' }}
                        </a>
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3">
                    <a href="{{ route('payment.success', $order->order_id) }}" 
                       class="flex-1 text-center border border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold py-3 px-6 rounded-lg transition-colors">
                        View Receipt
                    </a>
                    <a href="{{ url('/' . $order->domain->slug) }}" 
                       class="flex-1 text-center border border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold py-3 px-6 rounded-lg transition-colors">
                        Back to Store
                    </a>
                </div>
            </div>

            <!-- Security Notice -->
            <div class="mt-6 text-center text-sm text-gray-500">
                <p>🔒 This is a secure download link. Do not share this link with others.</p>
            </div>
        </div>
    </div>
</body>
</html>
