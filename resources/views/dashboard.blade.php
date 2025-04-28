@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <style>
        /* Custom Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }
        .animate-bounce {
            animation: bounce 0.5s ease-in-out;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.02); }
        }
        .animate-pulse {
            animation: pulse 1.5s ease-in-out infinite;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-10px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .animate-slide-in {
            animation: slideIn 0.5s ease-out forwards;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(45deg); }
        }
        .animate-spin {
            animation: spin 0.3s ease forwards;
        }

        @keyframes fanOut {
            from { opacity: 0; transform: translateY(10px) rotate(-5deg); }
            to { opacity: 1; transform: translateY(0) rotate(0deg); }
        }
        .animate-fan-out {
            animation: fanOut 0.3s ease forwards;
        }

        @keyframes glow {
            0%, 100% { box-shadow: 0 0 5px rgba(0, 255, 0, 0.3); }
            50% { box-shadow: 0 0 15px rgba(0, 255, 0, 0.7); }
        }
        .animate-glow {
            animation: glow 2s ease-in-out infinite;
        }

        /* Card Hover Effect */
        .card-hover:hover {
            transform: translateY(-5px) rotate(1deg);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }

        /* Particle Background */
        #particles-js {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: -1;
        }

        /* Dark Mode */
        .dark-mode {
            background-color: #1a1a1a;
            color: #e0e0e0;
        }
        .dark-mode .card {
            background: linear-gradient(135deg, #333, #444);
        }
        .dark-mode .title,
        .dark-mode .footer-text {
            color: #bbb;
        }
        .dark-mode .value {
            color: #fff;
        }
    </style>

    <!-- Particle Background -->
    <div id="particles-js"></div>

    <div x-data="{
        open: false,
        darkMode: false,
        notificationCount: 3,
        showNotifications: false,
        cards: [
            { id: 1, route: '{{ route('users.index') }}', icon: 'fas fa-user-friends', color: '#22c55e', title: 'Total Users', value: {{ $totalUsers }}, footer: '↑ The users in this period' },
            { id: 2, route: '{{ route('projects.index') }}', icon: 'fas fa-project-diagram', color: '#f97316', title: 'Total Projects', value: {{ $totalProjects }}, footer: '↗ The projects in this period' },
            { id: 3, route: '{{ route('tasks.index') }}', icon: 'fas fa-tasks', color: '#ef4444', title: 'Total Tasks', value: {{ $totalTasks }}, footer: '↓ The tasks in this period' }
        ]
    }" class="dashboard-container max-w-5xl mx-auto p-8 rounded-lg relative" :class="{ 'dark-mode': darkMode }">
        <!-- Sticky Header -->
        <div class="sticky top-0 z-20 bg-white dark:bg-gray-800 shadow-md p-4 rounded-lg mb-10 flex justify-between items-center">
            <h1 class="text-4xl font-bold text-gray-800 dark:text-gray-200 animate-pulse">Welcome, {{ auth()->user()->username }}!</h1>
            <div class="flex items-center space-x-4">
                <!-- Notification Bell -->
                <div class="relative">
                    <button @click="showNotifications = !showNotifications; notificationCount = 0" class="text-gray-600 dark:text-gray-300 hover:text-gray-800 transition">
                        <i class="fas fa-bell text-2xl" :class="{ 'animate-bounce': notificationCount > 0 }"></i>
                        <span x-show="notificationCount > 0" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center" x-text="notificationCount"></span>
                    </button>
                    <div x-show="showNotifications" class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-700 rounded-lg shadow-lg p-4">
                        <p class="text-gray-700 dark:text-gray-300">New updates available!</p>
                    </div>
                </div>
                <!-- Dark Mode Toggle -->
                <button @click="darkMode = !darkMode" class="text-gray-600 dark:text-gray-300 hover:text-gray-800 transition">
                    <i :class="darkMode ? 'fas fa-sun' : 'fas fa-moon'" class="text-2xl"></i>
                </button>
            </div>
        </div>

        <!-- Cards (Masonry Grid) -->
        <div class="cards grid grid-cols-1 md:grid-cols-3 gap-6" x-ref="cardsContainer">
            <template x-for="(card, index) in cards" :key="card.id">
                <div class="animate-fade-in" :style="'animation-delay: ' + (index * 0.2) + 's'" x-data="{ count: 0 }" x-init="setTimeout(() => { let start = 0; let end = card.value; let duration = 2000; let step = end / (duration / 16); let counter = setInterval(() => { start += step; if (start >= end) { start = end; clearInterval(counter); } count = Math.floor(start); }, 16); }, 500)">
                    <a :href="card.route" class="card rounded-lg p-6 flex items-center space-x-4 border-l-4 transition-all duration-300 card-hover animate-glow" :style="'border-color: ' + card.color + '; background: linear-gradient(135deg, ' + card.color + '20, #222);'" tabindex="0">
                        <div :class="'icon text-3xl animate-bounce' + (index + 1)" :style="'color: ' + card.color">
                            <i :class="card.icon"></i>
                        </div>
                        <div class="flex-1">
                            <div class="title text-sm text-[#aaa]" x-text="card.title"></div>
                            <div class="value text-2xl font-bold my-3 text-white" x-text="count"></div>
                            <div class="footer-text text-xs text-[#aaa]" x-text="card.footer"></div>
                        </div>
                    </a>
                </div>
            </template>
        </div>

        <!-- Floating Action Button (FAB) -->
        <div x-data="{ open: false }" class="fixed bottom-6 right-6">
            <button @click="open = !open" class="bg-[#1abc9c] text-white rounded-full w-14 h-14 flex items-center justify-center shadow-lg hover:bg-[#16a085] transition" :class="{ 'animate-spin': open }">
                <i class="fas fa-plus text-xl"></i>
            </button>
            <div x-show="open" class="absolute bottom-16 right-0 bg-white dark:bg-gray-700 rounded-lg shadow-lg p-4 w-48">
                <a href="{{ route('projects.create') }}" class="block px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 rounded animate-fan-out" style="animation-delay: 0.1s;">New Project</a>
                <a href="{{ route('tasks.create') }}" class="block px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 rounded animate-fan-out" style="animation-delay: 0.2s;">New Task</a>
                @if(auth()->user()->role === 'Admin')
                    <a href="{{ route('groups.create') }}" class="block px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 rounded animate-fan-out" style="animation-delay: 0.3s;">New Group</a>
                    <a href="{{ route('users.create') }}" class="block px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 rounded animate-fan-out" style="animation-delay: 0.4s;">New User</a>
                @endif
            </div>
        </div>
    </div>

    <!-- Include Particles.js -->
    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
    <script>
        // Initialize Particles.js
        particlesJS('particles-js', {
            particles: {
                number: { value: 80, density: { enable: true, value_area: 800 } },
                color: { value: '#22c55e' },
                shape: { type: 'circle' },
                opacity: { value: 0.5, random: true },
                size: { value: 3, random: true },
                line_linked: { enable: true, distance: 150, color: '#22c55e', opacity: 0.4, width: 1 },
                move: { enable: true, speed: 2, direction: 'none', random: false, straight: false, out_mode: 'out', bounce: false }
            },
            interactivity: {
                detect_on: 'canvas',
                events: { onhover: { enable: true, mode: 'repulse' }, onclick: { enable: true, mode: 'push' }, resize: true },
                modes: { repulse: { distance: 100, duration: 0.4 }, push: { particles_nb: 4 } }
            },
            retina_detect: true
        });

        // Counting Animation Function
        function startCounting(countRef, endValue) {
            let start = 0;
            const duration = 2000;
            const step = endValue / (duration / 16);
            const counter = setInterval(() => {
                start += step;
                if (start >= endValue) {
                    start = endValue;
                    clearInterval(counter);
                }
                countRef.count = Math.floor(start);
            }, 16);
        }

        // Make Cards Draggable
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.querySelector('.cards');
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
                        window.location.href = card.href;
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
