<x-authentication-layout>
    <div class="max-w-md w-full space-y-5">

<!-- Main Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl p-8 space-y-6">

            <!-- Header -->
            <div class="text-center">
                <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-1">
                    Selamat Datang!
                </h1>
                <p class="text-gray-500 dark:text-gray-400 text-sm">
                    Sistem ERP Multi-Modul — Masuk untuk menjelajahi fitur
                </p>
            </div>

            @if (session('status'))
                <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg text-green-600 dark:text-green-400 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="" class="space-y-5" id="login-form">
                @csrf
                <div class="space-y-4">
                    <div>
                        <x-label for="email" value="{{ __('Email address') }}" class="mb-1" />
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <x-input id="email" type="email" name="email" :value="old('email')" required autofocus
                                class="pl-10 w-full" placeholder="demo@example.com" />
                        </div>
                    </div>

                    <div>
                        <x-label for="password" value="{{ __('Password') }}" class="mb-1" />
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <x-input id="password" type="password" name="password" required autocomplete="current-password"
                                class="pl-10 w-full pr-10" placeholder="••••••••" />
                            <button type="button"
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                                onclick="togglePasswordVisibility()">
                                <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg id="eye-slash-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div>
                    <x-button class="w-full justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        {{ __('Sign in') }}
                    </x-button>
                </div>
            </form>

            <x-validation-errors class="!mt-0" />
        </div>

        <!-- Demo Accounts Panel -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5 border border-indigo-100 dark:border-gray-700">
            <div class="flex items-center gap-2 mb-4">
                <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">Akun Demo — Klik untuk login otomatis</span>
            </div>

            <div class="grid grid-cols-3 gap-2">
                <!-- Admin Account -->
                <button type="button" onclick="fillLogin('admin_tsno@gmail.com', 'user123')"
                    class="group flex flex-col items-center p-3 rounded-lg border-2 border-indigo-200 dark:border-indigo-700 bg-indigo-50 dark:bg-indigo-900/20 hover:border-indigo-500 hover:bg-indigo-100 dark:hover:bg-indigo-900/40 transition-all cursor-pointer">
                    <div class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <span class="text-xs font-bold text-indigo-700 dark:text-indigo-300">ADMIN</span>
                    <span class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Full Access</span>
                </button>

                <!-- User Account -->
                <button type="button" onclick="fillLogin('user_tsno@gmail.com', 'user123')"
                    class="group flex flex-col items-center p-3 rounded-lg border-2 border-emerald-200 dark:border-emerald-700 bg-emerald-50 dark:bg-emerald-900/20 hover:border-emerald-500 hover:bg-emerald-100 dark:hover:bg-emerald-900/40 transition-all cursor-pointer">
                    <div class="w-8 h-8 rounded-full bg-emerald-600 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <span class="text-xs font-bold text-emerald-700 dark:text-emerald-300">USER</span>
                    <span class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">General</span>
                </button>

                <!-- Purchasing Account -->
                <button type="button" onclick="fillLogin('purchasing_tsno@gmail.com', 'user123')"
                    class="group flex flex-col items-center p-3 rounded-lg border-2 border-amber-200 dark:border-amber-700 bg-amber-50 dark:bg-amber-900/20 hover:border-amber-500 hover:bg-amber-100 dark:hover:bg-amber-900/40 transition-all cursor-pointer">
                    <div class="w-8 h-8 rounded-full bg-amber-500 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <span class="text-xs font-bold text-amber-700 dark:text-amber-300">PURCHASING</span>
                    <span class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Procurement</span>
                </button>
            </div>

            <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                <p class="text-xs text-center text-gray-400 dark:text-gray-500">
                    Password semua akun: <code class="font-mono font-semibold text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 px-1.5 py-0.5 rounded">user123</code>
                </p>
            </div>
        </div>

        <!-- Tech Stack Info -->
        <div class="text-center space-y-2">
            <p class="text-xs text-gray-400 dark:text-gray-500">Dibangun dengan</p>
            <div class="flex justify-center flex-wrap gap-2">
                <span class="px-2 py-0.5 text-xs rounded bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 font-medium">Laravel 11</span>
                <span class="px-2 py-0.5 text-xs rounded bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 font-medium">Tailwind CSS</span>
                <span class="px-2 py-0.5 text-xs rounded bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 font-medium">Alpine.js</span>
                <span class="px-2 py-0.5 text-xs rounded bg-pink-100 dark:bg-pink-900/30 text-pink-700 dark:text-pink-300 font-medium">Livewire</span>
                <span class="px-2 py-0.5 text-xs rounded bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-300 font-medium">MySQL</span>
            </div>
        </div>

    </div>

    <script>
        function fillLogin(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
            document.getElementById('login-form').submit();
        }

        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            const eyeSlashIcon = document.getElementById('eye-slash-icon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.add('hidden');
                eyeSlashIcon.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('hidden');
                eyeSlashIcon.classList.add('hidden');
            }
        }
    </script>
</x-authentication-layout>
