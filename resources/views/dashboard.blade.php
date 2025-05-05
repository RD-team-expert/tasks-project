@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <style>
        /* CSS Variables System for Theming */
        :root {
            --primary-color: #22c55e;
            --secondary-color: #f97316;
            --tertiary-color: #ef4444;
            --accent-color: #1abc9c;
            --bg-header: #1F2937;
            --bg-card: #ffffff;
            --bg-card-hover: #f8fafc;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --text-card-title: #4b5563;
            --text-card-value: #111827;
            --text-card-footer: #6b7280;
            --particle-color: var(--primary-color);
            --shadow-card: 0 4px 20px rgba(0, 0, 0, 0.1);
            --shadow-header: 0 4px 15px rgba(0, 0, 0, 0.1);
            --shadow-fab: 0 6px 24px rgba(0, 0, 0, 0.2);
            --card-border-opacity: 0.2;
        }

        .dark {
            --bg-header: #111827;
            --bg-card: #1e293b;
            --bg-card-hover: #283548;
            --text-primary: #e5e7eb;
            --text-secondary: #9ca3af;
            --text-card-title: #d1d5db;
            --text-card-value: #ffffff;
            --text-card-footer: #9ca3af;
            --particle-color: #34d399;
            --shadow-card: 0 4px 20px rgba(0, 0, 0, 0.3);
            --shadow-header: 0 4px 15px rgba(0, 0, 0, 0.3);
            --shadow-fab: 0 6px 24px rgba(0, 0, 0, 0.4);
            --card-border-opacity: 0.1;
        }

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
            0%, 100% { box-shadow: 0 0 5px rgba(34, 197, 94, 0.3); }
            50% { box-shadow: 0 0 15px rgba(34, 197, 94, 0.7); }
        }
        .animate-glow {
            animation: glow 2s ease-in-out infinite;
        }

        /* Card Hover Effect */
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            transform-style: preserve-3d;
            box-shadow: var(--shadow-card);
        }

        .card-hover:hover {
            transform: translateY(-5px) rotate(1deg);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
            background: var(--bg-card-hover);
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
        .dashboard-container {
            color: var(--text-primary);
            transition: background 0.3s ease, color 0.3s ease;
        }

        .card {
            background: var(--bg-card);
            border: 1px solid rgba(0, 0, 0, var(--card-border-opacity));
        }

        /* Card Text Colors */
        .title {
            color: var(--text-card-title);
            font-weight: 600;
        }

        .value {
            color: var(--text-card-value);
            font-weight: 700;
        }

        .footer-text {
            color: var(--text-card-footer);
        }

        /* Sticky Header */
        .sticky-header {
            background: var(--bg-header);
            color: #ffffff;
            box-shadow: var(--shadow-header);
        }

        /* FAB Menu */
        .fab-button {
            background: var(--accent-color);
            box-shadow: var(--shadow-fab);
        }

        .fab-button:hover {
            background: color-mix(in srgb, var(--accent-color), #000 15%);
        }

        /* Notification Panel */
        .notification-panel {
            background: var(--bg-card);
            border: 1px solid rgba(0, 0, 0, var(--card-border-opacity));
            box-shadow: var(--shadow-card);
        }

        /* Dashboard Summary */
        .dashboard-summary {
            background: var(--bg-card);
            color: var(--text-primary);
            border: 1px solid rgba(0, 0, 0, var(--card-border-opacity));
        }

        /* Icon Colors */
        .icon-green {
            color: var(--primary-color);
        }

        .icon-orange {
            color: var(--secondary-color);
        }

        .icon-red {
            color: var(--tertiary-color);
        }

        .icon-blue {
            color: #3b82f6;
        }

        .icon-yellow {
            color: #eab308;
        }
    </style>

    <!-- Particle Background -->
    <div id="particles-js"></div>

    <div x-data="{
        open: false,
        darkMode: localStorage.getItem('darkMode') === 'true',
        notificationCount: 3,
        showNotifications: false,
        cards: [
            { id: 1, route: '{{ route('users.index') }}', icon: 'fas fa-user-friends', color: 'var(--primary-color)', title: 'Total Users', value: {{ $totalUsers }}, footer: '↑ The users in this period' },
            { id: 2, route: '{{ route('projects.index') }}', icon: 'fas fa-project-diagram', color: 'var(--secondary-color)', title: 'Total Projects', value: {{ $totalProjects }}, footer: '↗ The projects in this period' },
            { id: 3, route: '{{ route('tasks.index') }}', icon: 'fas fa-tasks', color: 'var(--tertiary-color)', title: 'Total Tasks', value: {{ $totalTasks }}, footer: '↓ The tasks in this period' }
        ]
    }"
         x-init="
        darkMode ? document.documentElement.classList.add('dark') : document.documentElement.classList.remove('dark');
        $watch('darkMode', value => {
            localStorage.setItem('darkMode', value);
            document.documentElement.classList.toggle('dark', value);
        })
    "
         class="dashboard-container max-w-5xl mx-auto px-6 py-8 rounded-lg relative">

        <!-- Sticky Header -->
        <div class="sticky-header sticky top-0 z-20 p-6 rounded-xl mb-10 flex justify-between items-center">
            <h1 class="text-4xl font-bold animate-pulse">Welcome, {{ auth()->user()->username }}!</h1>
            <div class="flex items-center space-x-6">
                <!-- Notification Bell -->
                <div class="relative">
                    <button @click="showNotifications = !showNotifications; notificationCount = 0" class="text-white hover:text-gray-300 transition">
                        <i class="fas fa-bell text-2xl" :class="{ 'animate-bounce': notificationCount > 0 }"></i>
                        <span x-show="notificationCount > 0" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center" x-text="notificationCount"></span>
                    </button>
                    <div x-show="showNotifications" @click.away="showNotifications = false" class="notification-panel absolute right-0 mt-2 w-64 rounded-xl p-4 z-30">
                        <div class="text-lg font-semibold mb-2">Notifications</div>
                        <p class="text-gray-700 dark:text-gray-300">New updates available!</p>
                        <p class="text-gray-700 dark:text-gray-300 mt-2">Project "Marketing Campaign" deadline approaching.</p>
                        <p class="text-gray-700 dark:text-gray-300 mt-2">2 new team members joined.</p>
                    </div>
                </div>
                <!-- Dark Mode Toggle -->
                <button @click="darkMode = !darkMode" class="text-white hover:text-gray-300 transition">
                    <i :class="darkMode ? 'fas fa-sun' : 'fas fa-moon'" class="text-2xl"></i>
                </button>
            </div>
        </div>

        <!-- Cards (Masonry Grid) -->
        <div class="cards grid grid-cols-1 md:grid-cols-3 gap-8" x-ref="cardsContainer">
            <template x-for="(card, index) in cards" :key="card.id">
                <div class="animate-fade-in" :style="'animation-delay: ' + (index * 0.2) + 's'" x-data="{ count: 0 }" x-init="setTimeout(() => {
                    let start = 0;
                    let end = card.value;
                    let duration = 2000;
                    let step = end / (duration / 16);
                    let counter = setInterval(() => {
                        start += step;
                        if (start >= end) {
                            start = end;
                            clearInterval(counter);
                        }
                        count = Math.floor(start);
                    }, 16);
                }, 500)">
                    <a :href="card.route" class="card rounded-xl p-8 flex items-center space-x-6 border-l-8 transition-all duration-300 card-hover animate-glow" :style="'border-color: ' + card.color" tabindex="0">
                        <div class="text-4xl" :class="'icon-' + (index === 0 ? 'green' : (index === 1 ? 'orange' : 'red'))">
                            <i :class="card.icon"></i>
                        </div>
                        <div class="flex-1">
                            <div class="title text-lg" x-text="card.title"></div>
                            <div class="value text-3xl my-2" x-text="count"></div>
                            <div class="footer-text text-sm" x-text="card.footer"></div>
                        </div>
                    </a>
                </div>
            </template>
        </div>

        <!-- Floating Action Button (FAB) -->
        <div x-data="{ open: false }" class="fixed bottom-8 right-8">
            <button @click="open = !open" class="fab-button text-white rounded-full w-16 h-16 flex items-center justify-center hover:bg-[#16a085] transition duration-300" :class="{ 'animate-spin': open }">
                <i class="fas fa-plus text-2xl"></i>
            </button>
            <div x-show="open" @click.away="open = false" class="absolute bottom-20 right-0 bg-white dark:bg-gray-800 rounded-lg shadow-lg p-4 w-56">
                <a href="{{ route('projects.create') }}" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg animate-fan-out" style="animation-delay: 0.1s;">
                    <i class="fas fa-project-diagram mr-2"></i> New Project
                </a>
                <a href="{{ route('tasks.create') }}" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg animate-fan-out" style="animation-delay: 0.2s;">
                    <i class="fas fa-tasks mr-2"></i> New Task
                </a>
                @if(auth()->user()->role === 'Admin')
                    <a href="{{ route('groups.create') }}" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg animate-fan-out" style="animation-delay: 0.3s;">
                        <i class="fas fa-users mr-2"></i> New Group
                    </a>
                    <a href="{{ route('users.create') }}" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg animate-fan-out" style="animation-delay: 0.4s;">
                        <i class="fas fa-user-plus mr-2"></i> New User
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Include Particles.js -->
    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
    <script>
        // Initialize Particles.js with dynamic color from CSS variables
        document.addEventListener('DOMContentLoaded', () => {
            const getParticleColor = () => {
                return getComputedStyle(document.documentElement).getPropertyValue('--particle-color').trim();
            };

            const initParticles = () => {
                particlesJS('particles-js', {
                    particles: {
                        number: { value: 60, density: { enable: true, value_area: 1000 } },
                        color: { value: getParticleColor() },
                        shape: { type: 'circle' },
                        opacity: { value: 0.5, random: true },
                        size: { value: 3, random: true },
                        line_linked: { enable: true, distance: 150, color: getParticleColor(), opacity: 0.4, width: 1 },
                        move: { enable: true, speed: 2, direction: 'none', random: false, straight: false, out_mode: 'out', bounce: false }
                    },
                    interactivity: {
                        detect_on: 'canvas',
                        events: { onhover: { enable: true, mode: 'repulse' }, onclick: { enable: true, mode: 'push' }, resize: true },
                        modes: { repulse: { distance: 100, duration: 0.4 }, push: { particles_nb: 4 } }
                    },
                    retina_detect: true
                });
            };

            // Initial particles setup
            initParticles();

            // Reinitialize particles when dark mode changes to update colors
            const darkModeObserver = new MutationObserver((mutations) => {
                mutations.forEach((mutation) => {
                    if (mutation.attributeName === 'class') {
                        if (document.documentElement.classList.contains('dark')) {
                            setTimeout(initParticles, 100); // Short delay to ensure CSS variables are updated
                        } else {
                            setTimeout(initParticles, 100);
                        }
                    }
                });
            });

            darkModeObserver.observe(document.documentElement, { attributes: true });

            // Make Cards Draggable
            const container = document.querySelector('.cards');
            let draggedCard = null;

            if (container) {
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
            }
        });
    </script>
@endsection
