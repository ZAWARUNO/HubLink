<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Profile - HubLink</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        /* Mobile responsiveness */
        @media (max-width: 640px) {
            .container {
                padding: 0.5rem;
            }
            .form-container {
                padding: 1rem;
            }
            .text-container {
                margin-bottom: 1rem;
            }
            .profile-photo-container {
                flex-direction: column;
                gap: 1rem;
            }
            .file-input-wrapper {
                width: 100%;
            }
            .file-button {
                width: 100%;
                padding: 0.75rem;
            }
            .form-input {
                padding: 0.75rem;
            }
            .submit-button {
                padding: 0.75rem;
            }
        }
        /* Custom file input styling */
        .file-input-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
        }
        
        .file-input-wrapper input[type=file] {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }
        
        /* Improved mobile spacing */
        @media (max-width: 768px) {
            .mobile-padding {
                padding: 1rem;
            }
            .mobile-gap {
                gap: 1.5rem;
            }
            .mobile-form-gap {
                gap: 1rem;
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
        
        // Function to display selected file name and update preview
        function handleFileSelect(input) {
            const fileName = input.files[0] ? input.files[0].name : 'Pilih file';
            document.getElementById('file-name').textContent = fileName;
            
            // Update preview image
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    document.getElementById('profile-preview').innerHTML = 
                        '<img src="' + e.target.result + '" alt="Profile Photo" class="w-full h-full object-cover">';
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-0 sm:p-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-6 sm:mb-8 mobile-padding">
            <h1 class="text-2xl sm:text-3xl font-bold text-primary">HubLink</h1>
            <p class="text-gray-600 mt-1 sm:mt-2 text-sm sm:text-base">Edit Profil Anda</p>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-8 mobile-padding mobile-gap">
            @if ($errors->any())
                <div class="mb-4">
                    <div class="bg-red-50 text-red-700 p-3 rounded-lg">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li class="text-sm sm:text-base">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <!-- Profile Photo -->
                <div class="mb-5">
                    <label class="block text-gray-700 font-medium mb-2 text-sm sm:text-base">Foto Profil</label>
                    <div class="flex flex-col sm:flex-row items-center gap-4">
                        <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full bg-gray-200 overflow-hidden border-2 border-gray-300 flex-shrink-0">
                            <div id="profile-preview">
                                @if($user->profile_photo)
                                    <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Profile Photo" class="w-full h-full object-cover">
                                @else
                                    <div class="bg-gray-200 border-2 border-dashed rounded-xl w-full h-full flex items-center justify-center">
                                        <svg class="w-6 h-6 sm:w-8 sm:h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="w-full flex flex-col items-center sm:items-start">
                            <div class="file-input-wrapper w-full max-w-xs">
                                <button type="button" class="file-button bg-primary hover:bg-primary-dark text-white py-2 px-4 rounded-lg transition flex items-center justify-center gap-2 w-full">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                    </svg>
                                    <span class="text-sm sm:text-base">Pilih Foto</span>
                                </button>
                                <input 
                                    type="file" 
                                    name="profile_photo" 
                                    accept="image/*"
                                    onchange="handleFileSelect(this)"
                                >
                            </div>
                            <p id="file-name" class="mt-2 text-xs sm:text-sm text-gray-500 text-center sm:text-left">
                                @if($user->profile_photo)
                                    {{ basename($user->profile_photo) }}
                                @else
                                    Belum ada file dipilih
                                @endif
                            </p>
                            <p class="mt-1 text-xs text-gray-500 text-center sm:text-left">Format: JPG, PNG, GIF (Max: 2MB)</p>
                        </div>
                    </div>
                </div>

                <!-- Personal Information -->
                <div class="mb-4 sm:mb-5 mobile-form-gap">
                    <label for="name" class="block text-gray-700 font-medium mb-2 text-sm sm:text-base">Nama</label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="{{ old('name', $user->name) }}"
                        class="form-input w-full px-3 py-2 sm:px-4 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition input-field text-sm sm:text-base"
                        placeholder="Masukkan nama Anda"
                        required
                    >
                </div>

                <div class="mb-4 sm:mb-5 mobile-form-gap">
                    <label for="email" class="block text-gray-700 font-medium mb-2 text-sm sm:text-base">Email</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email', $user->email) }}"
                        class="form-input w-full px-3 py-2 sm:px-4 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition input-field text-sm sm:text-base"
                        placeholder="Masukkan email Anda"
                        required
                    >
                </div>

                <div class="mb-4 sm:mb-5 mobile-form-gap">
                    <label for="phone" class="block text-gray-700 font-medium mb-2 text-sm sm:text-base">Nomor Telepon</label>
                    <input 
                        type="tel" 
                        id="phone" 
                        name="phone" 
                        value="{{ old('phone', $user->phone) }}"
                        class="form-input w-full px-3 py-2 sm:px-4 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition input-field text-sm sm:text-base"
                        placeholder="Masukkan nomor telepon Anda"
                        required
                    >
                    <p class="mt-1 text-xs text-gray-500">Nomor telepon harus diawali dengan +62</p>
                </div>

                <!-- Password Section -->
                <div class="border-t border-gray-200 pt-4 sm:pt-5 mt-5 sm:mt-6">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4">Ubah Password</h3>
                    
                    <div class="mb-4 sm:mb-5 mobile-form-gap">
                        <label for="current_password" class="block text-gray-700 font-medium mb-2 text-sm sm:text-base">Password Saat Ini</label>
                        <input 
                            type="password" 
                            id="current_password" 
                            name="current_password" 
                            class="form-input w-full px-3 py-2 sm:px-4 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition input-field text-sm sm:text-base"
                            placeholder="Masukkan password saat ini"
                        >
                    </div>

                    <div class="mb-4 sm:mb-5 mobile-form-gap">
                        <label for="password" class="block text-gray-700 font-medium mb-2 text-sm sm:text-base">Password Baru</label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="form-input w-full px-3 py-2 sm:px-4 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition input-field text-sm sm:text-base"
                            placeholder="Masukkan password baru"
                        >
                    </div>

                    <div class="mb-4 sm:mb-5 mobile-form-gap">
                        <label for="password_confirmation" class="block text-gray-700 font-medium mb-2 text-sm sm:text-base">Konfirmasi Password Baru</label>
                        <input 
                            type="password" 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            class="form-input w-full px-3 py-2 sm:px-4 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition input-field text-sm sm:text-base"
                            placeholder="Konfirmasi password baru"
                        >
                    </div>
                </div>

                <button 
                    type="submit" 
                    class="submit-button w-full bg-primary hover:bg-primary-dark text-white font-semibold py-2 px-4 sm:py-3 sm:px-4 rounded-lg transition shadow-md text-sm sm:text-base"
                >
                    Simpan Perubahan
                </button>
            </form>

            <div class="mt-4 sm:mt-6 text-center">
                <a href="{{ route('cms.home') }}" class="text-primary font-medium hover:underline text-sm sm:text-base">&larr; Kembali ke dashboard</a>
            </div>
        </div>
    </div>
</body>
</html>