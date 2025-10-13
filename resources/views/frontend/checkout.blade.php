<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Checkout - {{ $domain->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
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
    <div class="min-h-screen py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-3xl font-bold text-gray-900">Checkout</h1>
                <p class="mt-2 text-gray-600">Complete your purchase from {{ $domain->name }}</p>
            </div>

            @if(session('success'))
                <div class="mb-8 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Order Summary -->
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h2 class="text-xl font-semibold mb-4">Order Summary</h2>
                    <div class="flex gap-4 mb-6">
                        <img src="{{ $product['image'] }}" alt="{{ $product['title'] }}" class="w-24 h-24 object-cover rounded">
                        <div>
                            <h3 class="font-medium">{{ $product['title'] }}</h3>
                            <p class="text-gray-500 text-sm mt-1">{{ $product['description'] }}</p>
                            <p class="text-primary font-bold mt-2">Rp {{ number_format($product['price'], 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Buyer Information Form -->
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h2 class="text-xl font-semibold mb-4">Buyer Information</h2>
                    <form action="{{ route('checkout.process', ['domain' => $domain, 'componentId' => request()->route('componentId')]) }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                <input type="text" id="name" name="name" required
                                       class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary"
                                       value="{{ old('name') }}">
                                @error('name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                <input type="email" id="email" name="email" required
                                       class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary"
                                       value="{{ old('email') }}">
                                @error('email')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                <input type="tel" id="phone" name="phone" required
                                       class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary"
                                       value="{{ old('phone') }}">
                                @error('phone')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Shipping Address</label>
                                <textarea id="address" name="address" required rows="3"
                                          class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">{{ old('address') }}</textarea>
                                @error('address')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit" id="pay-button" class="w-full bg-primary hover:bg-primary-dark text-white font-semibold py-3 rounded-lg transition-colors">
                                Proceed to Payment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const payButton = document.getElementById('pay-button');
            
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Disable button and show loading
                payButton.disabled = true;
                payButton.textContent = 'Processing...';
                
                // Get form data
                const formData = new FormData(form);
                
                // Send AJAX request
                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Open Midtrans Snap popup
                        snap.pay(data.snap_token, {
                            onSuccess: function(result) {
                                window.location.href = '/payment/success/' + data.order_id;
                            },
                            onPending: function(result) {
                                window.location.href = '/payment/success/' + data.order_id;
                            },
                            onError: function(result) {
                                window.location.href = '/payment/failed/' + data.order_id;
                            },
                            onClose: function() {
                                // Re-enable button
                                payButton.disabled = false;
                                payButton.textContent = 'Proceed to Payment';
                            }
                        });
                    } else {
                        alert('Error: ' + data.message);
                        payButton.disabled = false;
                        payButton.textContent = 'Proceed to Payment';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                    payButton.disabled = false;
                    payButton.textContent = 'Proceed to Payment';
                });
            });
        });
    </script>
</body>
</html>