@extends('layouts.app')

@section('title', 'Projects List')

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

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.02); }
        }
        .animate-pulse {
            animation: pulse 1.5s ease-in-out infinite;
        }

        @keyframes glow {
            0%, 100% { box-shadow: 0 0 5px rgba(0, 123, 255, 0.3); }
            50% { box-shadow: 0 0 15px rgba(0, 123, 255, 0.7); }
        }
        .animate-glow {
            animation: glow 2s ease-in-out infinite;
        }

        @keyframes fanOut {
            from { opacity: 0; transform: translateY(10px) rotate(-5deg); }
            to { opacity: 1; transform: translateY(0) rotate(0deg); }
        }
        .animate-fan-out {
            animation: fanOut 0.3s ease forwards;
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
        .dark-mode .footer-text,
        .dark-mode .text-gray-600 {
            color: #bbb;
        }
        .dark-mode .text-black,
        .dark-mode .text-gray-800 {
            color: #fff;
        }
        .dark-mode .border-[#D3D3D3] {
            border-color: #555;
        }
        .dark-mode .bg-white {
            background: linear-gradient(135deg, #333, #444);
        }

        /* Input and Button Styles */
        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.3);
            border-color: #28A745;
        }
    </style>

    <!-- Particle Background -->
    <div id="particles-js"></div>

    <div x-data="{
        darkMode: false,
        notificationCount: 3,
        showNotifications: false
    }" class="container max-w-5xl mx-auto px-4 py-8 relative" :class="{ 'dark-mode': darkMode }">
        <!-- Sticky Header -->
        <div class="sticky top-0 z-20 bg-white dark:bg-gray-800 shadow-md p-4 rounded-lg mb-10 flex justify-between items-center">
            <h2 class="text-3xl font-bold text-gray-800 dark:text-gray-200 animate-pulse">Projects List</h2>
            <div class="flex items-center space-x-4">
                <!-- Notification Bell -->
                <div class="relative">
                    <button @click="showNotifications = !showNotifications; notificationCount = 0" class="text-gray-600 dark:text-gray-300 hover:text-gray LON-800 transition">
                        <i class="fas fa-bell text-2xl" :class="{ 'animate-bounce': notificationCount > 0 }"></i>
                        <span x-show="notificationCount > 0" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center" x-text="notificationCount"></span>
                    </button>
                    <div x-show="showNotifications" class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-700 rounded-lg shadow-lg p-4">
                        <p class="text-gray-700 dark:text-gray-300">New project updates available!</p>
                    </div>
                </div>
                <!-- Dark Mode Toggle -->
                <button @click="darkMode = !darkMode" class="text-gray-600 dark:text-gray-300 hover:text-gray-800 transition">
                    <i :class="darkMode ? 'fas fa-sun' : 'fas fa-moon'" class="text-2xl"></i>
                </button>
            </div>
        </div>

        <!-- Search Form -->
        <form method="GET" action="{{ route('projects.index') }}" class="mb-6 animate-fade-in">
            <div class="flex">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search projects..." class="w-full border border-[#D3D3D3] rounded-l px-3 py-2 text-gray-800 dark:text-gray-200 dark:bg-gray-700 input-focus transition" />
                <button type="submit" class="bg-[#28A745] text-white px-4 py-2 rounded-r hover:bg-[#218838] transition duration-150">Search</button>
            </div>
        </form>

        <!-- Projects Grid -->
        @if(!$projects || $projects->isEmpty())
            <p class="text-center text-gray-600 dark:text-gray-400 animate-fade-in">No projects available.</p>
        @else
            <div class="cards grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" x-ref="cardsContainer">
                @foreach ($projects as $project)
                    <div class="card bg-white shadow-md rounded-lg p-6 border-l-4 transition-all duration-300 card-hover animate-glow animate-fade-in"
                         style="animation-delay: {{ $loop->index * 0.1 }}s; border-color: #28A745;"
                         x-data="{ open: false }"
                         tabindex="0"
                         data-href="{{ route('projects.show', $project->id) }}"
                         @click="window.location.href='{{ route('projects.show', $project->id) }}'">
                        <div class="flex items-center space-x-4">
                            <div class="icon text-3xl text-[#28A745] animate-bounce">
                                <i class="fas fa-project-diagram"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="title text-lg font-semibold text-black dark:text-white mb-2">{{ $project->name }}</h3>
                                <p class="text-gray-600 dark:text-gray-400 line-clamp-2 mb-2">{{ $project->description }}</p>
                                <div class="footer-text text-sm text-gray-500 dark:text-gray-400 mb-1">
                                    <i class="fas fa-calendar-alt mr-1 text-green-500"></i> Start: {{ $project->start_date }}
                                </div>
                                <div class="footer-text text-sm text-gray-500 dark:text-gray-400 mb-1">
                                    <i class="fas fa-calendar-alt mr-1 text-red-500"></i> End: {{ $project->end_date }}
                                </div>
                                <div class="footer-text text-sm text-gray-500 dark:text-gray-400 mb-4">
                                    <i class="fas fa-user mr-1 text-blue-500"></i> Created by: {{ $project->creator->username ?? 'N/A' }}
                                </div>
                            </div>
                            <!-- Action Button -->
                            <button @click.stop="open = !open" class="text-gray-600 dark:text-gray-300 hover:text-gray-800 transition">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                        </div>
                        <!-- Dropdown Actions -->
                        <div x-show="open" class="mt-4 bg-white dark:bg-gray-700 rounded-lg shadow-lg p-4" @click.stop>
                            <a href="{{ route('projects.edit', $project->id) }}" class="block px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 rounded animate-fan-out" style="animation-delay: 0.1s;">Edit</a>
                            <form action="{{ route('projects.destroy', $project->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="block w-full text-left px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 rounded animate-fan-out" style="animation-delay: 0.2s;" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6 animate-fade-in">
                {{ $projects->appends(request()->query())->links() }}
            </div>
        @endif

        <!-- Floating Action Button (FAB) -->
        <div x-data="{ open: false }" class="fixed bottom-6 right-6">
            <button @click="open = !open" class="bg-[#1abc9c] text-white rounded-full w-14 h-14 flex items-center justify-center shadow-lg hover:bg-[#16a085] transition" :class="{ 'animate-spin': open }">
                <i class="fas fa-plus text-xl"></i>
            </button>
            <div x-show="open" class="absolute bottom-16 right-0 bg-white dark:bg-gray-700 rounded-lg shadow-lg p-4 w-48">
                <a href="{{ route('projects.create') }}" class="block px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 rounded animate-fan-out" style="animation-delay: 0.1s;">New Project</a>
            </div>
        </div>
    </div>

    <!-- Include Particles.js -->
    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
    <script>
        // Initialize Particles.js with a different configuration
        particlesJS('particles-js', {
            particles: {
                number: { value: 60, density: { enable: true, value_area: 1000 } },
                color: { value: '#007bff' }, // Blue particles
                shape: { type: 'star', stroke: { width: 0, color: '#000000' } }, // Star shapes
                opacity: { value: 0.6, random: true },
                size: { value: 4, random: true },
                line_linked: { enable: true, distance: 120, color: '#007bff', opacity: 0.5, width: 1.5 },
                move: { enable: true, speed: 3, direction: 'none', random: true, straight: false, out_mode: 'out', bounce: false }
            },
            interactivity: {
                detect_on: 'canvas',
                events: { onhover: { enable: true, mode: 'bubble' }, onclick: { enable: true, mode: 'repulse' }, resize: true },
                modes: { bubble: { distance: 200, size: 6, duration: 0.3, opacity: 0.8 }, repulse: { distance: 150, duration: 0.4 } }
            },
            retina_detect: true
        });

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
