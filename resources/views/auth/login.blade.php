@extends('layouts.applogin')

@section('title', 'Login')

@section('content')
    <style>
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in { animation: fadeIn 0.5s ease-out forwards; }

        @keyframes slideIn { from { opacity: 0; transform: translateX(-20px); } to { opacity: 1; transform: translateX(0); } }
        .animate-slide-in { animation: slideIn 0.5s ease-out forwards; }

        @keyframes pulse { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.02); } }
        .animate-pulse { animation: pulse 1.5s ease-in-out infinite; }

        @keyframes glow { 0%, 100% { box-shadow: 0 0 5px rgba(138, 43, 226, 0.3); } 50% { box-shadow: 0 0 15px rgba(138, 43, 226, 0.7); } }
        .animate-glow { animation: glow 2s ease-in-out infinite; }

        @keyframes ripple { to { transform: scale(2); opacity: 0; } }
        .ripple-effect { position: absolute; border-radius: 50%; background: rgba(255, 255, 255, 0.4); animation: ripple 0.7s linear; }

        #particles-js { position: absolute; width: 100%; height: 100%; top: 0; left: 0; z-index: -1; }

        .input-focus:focus { box-shadow: 0 0 0 3px rgba(138, 43, 226, 0.3); border-color: #8B2BE2; }
        .tooltip { visibility: hidden; opacity: 0; transition: all 0.3s ease; transform: translateY(10px); }
        .tooltip-parent:hover .tooltip { visibility: visible; opacity: 1; transform: translateY(0); }
        .error-message { background: #fee2e2; color: #b91c1c; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem; text-align: center; }

        .dark-mode { background: linear-gradient(135deg, #1a1a1a, #2a2a2a); color: #e0e0e0; }
        .dark-mode .bg-white { background: linear-gradient(135deg, #2a2a2a, #3a3a3a); }
        .dark-mode .text-gray-800 { color: #f0f0f0; }
        .dark-mode .text-gray-700 { color: #d0d0d0; }
        .dark-mode input, .dark-mode select { background-color: #3a3a3a; color: #f0f0f0; border-color: #555; }
        .dark-mode input::placeholder { color: #999; }
        .dark-mode .error-message { background: #7f1d1d; color: #fecaca; }
    </style>

    <div id="particles-js"></div>

    <div x-data="{ darkMode: $store.darkMode.isDark }" :class="{ 'dark-mode': darkMode }" class="min-h-screen flex items-center justify-center relative animate-fade-in">
        <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-md animate-glow dark:bg-gray-700">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-6 animate-pulse dark:text-gray-200">Login to Your Account</h2>

            @if ($errors->any())
                <div class="error-message animate-fade-in">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Email -->
                <div class="relative tooltip-parent animate-slide-in">
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-1 dark:text-gray-200">
                        Email <span class="text-red-500">*</span>
                        <i class="fas fa-info-circle ml-1 text-gray-500 cursor-pointer dark:text-gray-400"></i>
                        <span class="tooltip absolute bg-gray-800 text-white text-xs rounded p-2 mt-2 dark:bg-gray-700">Enter your email address</span>
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        required
                        value="{{ old('email') }}"
                        class="w-full px-4 py-2 border rounded-lg input-focus dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                        placeholder="you@example.com"
                    >
                    @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="relative tooltip-parent animate-slide-in" style="animation-delay: 0.1s;">
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-1 dark:text-gray-200">
                        Password <span class="text-red-500">*</span>
                        <i class="fas fa-info-circle ml-1 text-gray-500 cursor-pointer dark:text-gray-400"></i>
                        <span class="tooltip absolute bg-gray-800 text-white text-xs rounded p-2 mt-2 dark:bg-gray-700">Enter your password</span>
                    </label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        class="w-full px-4 py-2 border rounded-lg input-focus dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                        placeholder="••••••••"
                    >
                    @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center animate-slide-in" style="animation-delay: 0.2s;">
                    <input
                        type="checkbox"
                        id="remember"
                        name="remember"
                        class="border-gray-300 rounded dark:bg-gray-800 dark:border-gray-600"
                    >
                    <label for="remember" class="ml-2 text-sm text-gray-700 dark:text-gray-300">Remember Me</label>
                </div>

                <!-- Submit Button -->
                <div class="relative tooltip-parent animate-slide-in" style="animation-delay: 0.3s;">
                    <button
                        type="submit"
                        class="w-full px-4 py-2 bg-[#8B2BE2] text-white rounded hover:bg-[#7A26C9] transition dark:bg-purple-700 dark:hover:bg-purple-600"
                        @mousedown="addRipple($event)"
                    >
                        Login
                    </button>
                    <span class="tooltip absolute bg-gray-800 text-white text-xs rounded p-2 mt-2 dark:bg-gray-700">Sign in to your account</span>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        particlesJS('particles-js', {
            particles: {
                number: { value: 70, density: { enable: true, value_area: 900 } },
                color: { value: '#8B2BE2' },
                shape: { type: 'polygon', stroke: { width: 0 }, polygon: { nb_sides: 5 } },
                opacity: { value: 0.7, random: true },
                size: { value: 5, random: true },
                line_linked: { enable: true, distance: 130, color: '#8B2BE2', opacity: 0.5, width: 1.2 },
                move: { enable: true, speed: 2.5, direction: 'none', random: true, straight: false, out_mode: 'out', bounce: false }
            },
            interactivity: {
                detect_on: 'canvas',
                events: { onhover: { enable: true, mode: 'grab' }, onclick: { enable: true, mode: 'push' }, resize: true },
                modes: { grab: { distance: 150, line_linked: { opacity: 0.7 } }, push: { particles_nb: 5 } }
            },
            retina_detect: true
        });

        function addRipple(event) {
            const button = event.currentTarget;
            const ripple = document.createElement('span');
            const diameter = Math.max(button.clientWidth, button.clientHeight);
            const radius = diameter / 2;
            ripple.style.width = ripple.style.height = `${diameter}px`;
            ripple.style.left = `${event.clientX - button.getBoundingClientRect().left - radius}px`;
            ripple.style.top = `${event.clientY - button.getBoundingClientRect().top - radius}px`;
            ripple.classList.add('ripple-effect');
            button.appendChild(ripple);
            setTimeout(() => ripple.remove(), 700);
        }
    </script>
@endsection
