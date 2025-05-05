@extends('layouts.app')

@section('title', 'Users List')

@section('content')
    <style>
        /* CSS Variables for Consistency with layouts.app */
        :root {
            --bg-body: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            --bg-card: #ffffff;
            --bg-card-hover: rgba(255, 255, 255, 0.9);
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --border-accent: #28A745;
            --shadow-card: 0 4px 20px rgba(0, 0, 0, 0.1);
            --particle-color: #007bff;
        }

        .dark {
            --bg-body: linear-gradient(135deg, #1f2937 0%, #111827 100%);
            --bg-card: rgba(31, 41, 55, 0.8);
            --bg-card-hover: rgba(55, 65, 81, 0.9);
            --text-primary: #e5e7eb;
            --text-secondary: #9ca3af;
            --border-accent: #34D399;
            --shadow-card: 0 4px 20px rgba(0, 0, 0, 0.3);
            --particle-color: #60a5fa;
        }

        /* Custom Animations */
        @keyframes fadeInScale {
            from { opacity: 0; transform: translateY(20px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        .animate-fade-in-scale {
            animation: fadeInScale 0.6s ease-out forwards;
        }

        @keyframes pulseGlow {
            0%, 100% { box-shadow: 0 0 10px rgba(40, 167, 69, 0.3); }
            50% { box-shadow: 0 0 20px rgba(40, 167, 69, 0.5); }
        }
        .animate-pulse-glow {
            animation: pulseGlow 2s ease-in-out infinite;
        }

        @keyframes rotateIcon {
            0% { transform: rotate(0deg); }
            50% { transform: rotate(15deg); }
            100% { transform: rotate(0deg); }
        }
        .animate-rotate-icon {
            animation: rotateIcon 1.5s ease-in-out infinite;
        }

        @keyframes fanOut {
            from { opacity: 0; transform: translateY(10px) rotate(-10deg); }
            to { opacity: 1; transform: translateY(0) rotate(0deg); }
        }
        .animate-fan-out {
            animation: fanOut 0.4s cubic-bezier(0.68, -0.55, 0.27, 1.55) forwards;
        }

        /* Card Hover Effect with 3D Tilt */
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            transform-style: preserve-3d;
        }

        .card-hover:hover {
            transform: translateY(-8px) scale(1.03);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2), 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Particle Background */
        #particles-js {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: -1;
            transform: translateZ(0);
        }

        /* Base Styles */
        .container {
            background: var(--bg-body);
            color: var(--text-primary);
            transition: background 0.3s ease, color 0.3s ease;
        }

        .card {
            background: var(--bg-card);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .card:hover {
            background: var(--bg-card-hover);
        }

        .text-gray-600 {
            color: var(--text-secondary);
        }

        .border-[#D3D3D3] {
            border-color: rgba(209, 213, 219, 0.5);
        }

        .bg-white {
            background: var(--bg-card);
        }

        /* Input and Button Styles */
        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.3);
            border-color: var(--border-accent);
        }

        /* Sticky Header */
        .sticky-header {
            background: var(--bg-card);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
    </style>

    <!-- Particle Background -->
    <div id="particles-js"></div>

    <div x-data="{
        darkMode: localStorage.getItem('darkMode') === 'true',
        notificationCount: 3,
        showNotifications: false
    }" x-init="darkMode ? document.documentElement.classList.add('dark') : document.documentElement.classList.remove('dark'); $watch('darkMode', value => { localStorage.setItem('darkMode', value); document.documentElement.classList.toggle('dark', value); })" class="container max-w-6xl mx-auto px-6 py-12 relative">
        <!-- Sticky Header -->
        <div class="sticky-header sticky top-0 z-20 shadow-lg p-6 rounded-xl mb-12 flex justify-between items-center">
            <h2 class="text-4xl font-bold animate-pulse">Users List</h2>
            <div class="flex items-center space-x-6">
                <!-- Notification Bell -->
                <div class="relative">
                    <button @click="showNotifications = !showNotifications; notificationCount = 0" class="text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-gray-100 transition">
                        <i class="fas fa-bell text-2xl" :class="{ 'animate-bounce': notificationCount > 0 }"></i>
                        <span x-show="notificationCount > 0" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center" x-text="notificationCount"></span>
                    </button>
                    <div x-show="showNotifications" class="absolute right-0 mt-2 w-64 bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4">
                        <p class="text-gray-700 dark:text-gray-300">New user updates available!</p>
                    </div>
                </div>
                <!-- Dark Mode Toggle -->
                <button @click="darkMode = !darkMode" class="text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-gray-100 transition">
                    <i :class="darkMode ? 'fas fa-sun' : 'fas fa-moon'" class="text-2xl"></i>
                </button>
                @if(auth()->user()->role === 'Admin')
                    <a href="{{ route('users.create') }}" class="bg-[#28A745] dark:bg-[#34D399] text-white px-4 py-2 rounded-lg hover:bg-[#218838] dark:hover:bg-[#2BB38B] transition duration-150">Create User</a>
                @elseif(auth()->user()->role === 'Manager')
                    <a href="{{ route('users.createEmployee') }}" class="bg-[#28A745] dark:bg-[#34D399] text-white px-4 py-2 rounded-lg hover:bg-[#218838] dark:hover:bg-[#2BB38B] transition duration-150">Add Employee</a>
                @endif
            </div>
        </div>

        <!-- Search Form -->
        <form method="GET" action="{{ route('users.index') }}" class="mb-8 animate-fade-in-scale">
            <div class="flex max-w-lg mx-auto">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search users..." class="w-full border border-[#D3D3D3] dark:border-gray-600 rounded-l-lg px-4 py-3 text-gray-800 dark:text-gray-200 dark:bg-gray-800 input-focus transition" />
                <button type="submit" class="bg-[#28A745] dark:bg-[#34D399] text-white px-6 py-3 rounded-r-lg hover:bg-[#218838] dark:hover:bg-[#2BB38B] transition duration-150">Search</button>
            </div>
        </form>

        <!-- Users Grid -->
        @if(!$users || $users->isEmpty())
            <p class="text-center text-gray-600 dark:text-gray-400 text-lg animate-fade-in-scale">No users found.</p>
        @else
            <div class="cards grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8" x-ref="cardsContainer">
                @foreach ($users as $user)
                    <div class="card shadow-lg rounded-xl p-8 border-l-4 transition-all duration-300 card-hover animate-pulse-glow animate-fade-in-scale"
                         style="animation-delay: {{ $loop->index * 0.15 }}s; border-color: var(--border-accent);"
                         x-data="{ open: false }"
                         tabindex="0"
                         data-href="{{ route('users.show', $user->id) }}"
                         @click="window.location.href='{{ route('users.show', $user->id) }}'">
                        <div class="flex items-start space-x-6">
                            <div class="icon text-4xl text-[#28A745] dark:text-[#34D399] animate-rotate-icon">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="title text-xl font-semibold mb-3">{{ $user->username }}</h3>
                                <div class="footer-text text-sm text-gray-500 dark:text-gray-400 mb-2">
                                    <i class="fas fa-envelope mr-2 text-blue-500 dark:text-blue-400"></i> Email: {{ $user->email }}
                                </div>
                                <div class="footer-text text-sm text-gray-500 dark:text-gray-400 mb-2">
                                    <i class="fas fa-check-circle mr-2 {{ $user->email_verified_at ? 'text-green-500 dark:text-green-400' : 'text-red-500 dark:text-red-400' }}"></i> Email Status: {{ $user->email_verified_at ? 'Verified' : 'Not Verified' }}
                                </div>
                                <div class="footer-text text-sm mb-2">
                                    <span class="inline-block px-2 py-1 rounded text-xs font-semibold
                                        {{ $user->role == 'Admin' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' :
                                           ($user->role == 'Manager' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300' :
                                           'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300') }}">
                                        {{ $user->role }}
                                    </span>
                                </div>
                                <div class="footer-text text-sm text-gray-500 dark:text-gray-400 mb-4">
                                    <i class="fas fa-users mr-2 text-purple-500 dark:text-purple-400"></i> Group:
                                    @if($user->groups && $user->groups->isNotEmpty())
                                        {{ $user->groups->pluck('name')->join(', ') }}
                                    @else
                                        None
                                    @endif
                                </div>
                            </div>
                            @if(auth()->user()->role === 'Admin')
                                <!-- Action Button -->
                                <button @click.stop="open = !open" class="text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-gray-100 transition">
                                    <i class="fas fa-ellipsis-v text-lg"></i>
                                </button>
                            @endif
                        </div>
                        @if(auth()->user()->role === 'Admin')
                            <!-- Dropdown Actions -->
                            <div x-show="open" class="mt-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg p-4" @click.stop>
                                <a href="{{ route('users.edit', $user->id) }}" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded animate-fan-out" style="animation-delay: 0.1s;">Edit</a>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded animate-fan-out" style="animation-delay: 0.2s;" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12 animate-fade-in-scale">
                {{ $users->appends(request()->query())->links() }}
            </div>
        @endif

        <!-- Floating Action Button (FAB) -->
        @if(auth()->user()->role === 'Admin' || auth()->user()->role === 'Manager')
            <div x-data="{ open: false }" class="fixed bottom-8 right-8">
                <button @click="open = !open" class="bg-[#1abc9c] dark:bg-[#2DD4BF] text-white rounded-full w-16 h-16 flex items-center justify-center shadow-lg hover:bg-[#16a085] dark:hover:bg-[#26A69A] transition" :class="{ 'animate-spin': open }">
                    <i class="fas fa-plus text-2xl"></i>
                </button>
                <div x-show="open" class="absolute bottom-20 right-0 bg-white dark:bg-gray-800 rounded-lg shadow-lg p-4 w-56">
                    @if(auth()->user()->role === 'Admin')
                        <a href="{{ route('users.create') }}" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded animate-fan-out" style="animation-delay: 0.1s;">New User</a>
                    @elseif(auth()->user()->role === 'Manager')
                        <a href="{{ route('users.createEmployee') }}" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded animate-fan-out" style="animation-delay: 0.1s;">New Employee</a>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Include Particles.js -->
    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
    <script>
        // Initialize Particles.js with Parallax Effect
        particlesJS('particles-js', {
            particles: {
                number: { value: 50, density: { enable: true, value_area: 1200 } },
                color: { value: 'var(--particle-color)' },
                shape: { type: ['circle', 'triangle', 'star'], stroke: { width: 0 } },
                opacity: { value: 0.5, random: true },
                size: { value: 5, random: true },
                line_linked: { enable: true, distance: 150, color: 'var(--particle-color)', opacity: 0.4, width: 1 },
                move: { enable: true, speed: 2, direction: 'none', random: true, straight: false, out_mode: 'out', bounce: false }
            },
            interactivity: {
                detect_on: 'canvas',
                events: { onhover: { enable: true, mode: 'bubble' }, onclick: { enable: true, mode: 'repulse' }, resize: true },
                modes: { bubble: { distance: 250, size: 8, duration: 0.3, opacity: 0.8 }, repulse: { distance: 200, duration: 0.4 } }
            },
            retina_detect: true
        });

        // Parallax Effect for Particles
        document.addEventListener('mousemove', (e) => {
            const particles = document.getElementById('particles-js');
            const moveX = (e.clientX * -0.05) / 2;
            const moveY = (e.clientY * -0.05) / 2;
            particles.style.transform = `translate(${moveX}px, ${moveY}px)`;
        });

        // Make Cards Draggable
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.querySelector('.cards');
            if (!container) return;

            let draggedCard = null;

            container.querySelectorAll('.card').forEach(card => {
                card.draggable = true;
                card.addEventListener('dragstart', () => draggedCard = card);
                card.addEventListener('dragover', e => e.preventDefault());
                card.addEventListener('drop', () => {
                    if (draggedCard !== card) {
                        const allCards = [...container.querySelectorAll('.card')];
                        const draggedIndex = allCards.indexOf(draggedCard);
                        const droppedIndex = allCards.indexOf(card);
                        if (draggedIndex < droppedIndex) {
                            card.after(draggedCard);
                        } else {
                            card.before(draggedCard);
                        }
                    }
                });
            });

            // Keyboard Navigation
            container.querySelectorAll('.card').forEach((card, index) => {
                card.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter') {
                        window.location.href = card.getAttribute('data-href');
                    }
                    const cards = [...container.querySelectorAll('.card')];
                    if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
                        const nextCard = cards[index + 1];
                        if (nextCard) nextCard.focus();
                    }
                    if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
                        const prevCard = cards[index - 1];
                        if (prevCard) prevCard.focus();
                    }
                });
            });
        });
    </script>
@endsection
