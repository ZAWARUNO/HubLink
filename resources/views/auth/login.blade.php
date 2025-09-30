<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - HubLink</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        /* Mobile responsiveness */
        @media (max-width: 640px) {
            .container {
                padding: 1rem;
            }
            .form-container {
                padding: 1.5rem;
            }
            .text-container {
                margin-bottom: 1.5rem;
            }
        }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#00c499',
                        'primary-dark': '#00a882',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-primary">HubLink</h1>
            <p class="text-gray-600 mt-2">Masuk ke akun Anda</p>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-8">
            @if ($errors->any())
                <div class="mb-4">
                    <div class="bg-red-50 text-red-700 p-3 rounded-lg">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-5">
                    <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition"
                        placeholder="masukkan email Anda"
                        required
                    >
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-gray-700 font-medium mb-2">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition"
                        placeholder="masukkan password Anda"
                        required
                    >
                </div>

                <button 
                    type="submit" 
                    class="w-full bg-primary hover:bg-primary-dark text-white font-semibold py-3 px-4 rounded-lg transition shadow-md"
                >
                    Masuk
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-600">
                    Belum punya akun? 
                    <a href="{{ route('register') }}" class="text-primary font-semibold hover:underline">Daftar sekarang</a>
                </p>
            </div>
        </div>

        <div class="mt-6 text-center">
            <a href="{{ url('/') }}" class="text-primary font-medium hover:underline">&larr; Kembali ke beranda</a>
        </div>
    </div>
</body>
</html>