@extends('layouts.app')

@section('title', 'My Projects')

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

        .tooltip { visibility: hidden; opacity: 0; transition: all 0.3s ease; transform: translateY(10px); }
        .tooltip-parent:hover .tooltip { visibility: visible; opacity: 1; transform: translateY(0); }
        .no-projects-message { background: #f3f4f6; color: #6b7280; padding: 1rem; border-radius: 0.5rem; text-align: center; }
        .button-container { display: flex; justify-content: center; gap: 1rem; }
        .pagination { display: flex; justify-content: center; gap: 0.5rem; margin-top: 1rem; }
        .pagination a, .pagination span { padding: 0.5rem 1rem; border-radius: 0.5rem; background: #e5e7eb; color: #374151; }
        .pagination a:hover { background: #d1d5db; }
        .pagination .current { background: #8B2BE2; color: white; }

        .dark-mode { background: linear-gradient(135deg, #1a1a1a, #2a2a2a); color: #e0e0e0; }
        .dark-mode .bg-white { background: linear-gradient(135deg, #2a2a2a, #3a3a3a); }
        .dark-mode .text-gray-800 { color: #f0f0f0; }
        .dark-mode .text-gray-700 { color: #d0d0d0; }
        .dark-mode .text-gray-600 { color: #b0b0b0; }
        .dark-mode .no-projects-message { background: #3a3a3a; color: #9ca3af; }
        .dark-mode .pagination a, .dark-mode .pagination span { background: #4b5563; color: #e5e7eb; }
        .dark-mode .pagination a:hover { background: #6b7280; }
        .dark-mode .pagination .current { background: #7A26C9; color: #f0f0f0; }
    </style>

    <div id="particles-js"></div>

    <div x-data="{ darkMode: $store.darkMode.isDark }" :class="{ 'dark-mode': darkMode }" class="max-w-3xl mx-auto p-6 relative animate-fade-in">
        <div class="sticky top-0 z-20 bg-white shadow-md p-4 rounded-lg mb-8 flex justify-between items-center animate-slide-in dark:bg-gray-800">
            <h2 class="text-3xl font-bold text-gray-800 animate-pulse dark:text-gray-200">My Projects</h2>
            <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500" @mousedown="addRipple($event)">Back to Dashboard</a>
        </div>

        @if ($errors->any())
            <div class="error-message animate-fade-in" style="background: #fee2e2; color: #b91c1c; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem;">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="space-y-6">
            @forelse($projects as $index => $project)
                <div class="bg-white p-6 rounded-lg shadow-lg animate-glow dark:bg-gray-700" style="animation-delay: {{ $index * 0.1 }}s;">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <p class="text-gray-700 dark:text-gray-300 animate-slide-in" style="animation-delay: 0.1s;">
                            <strong class="font-semibold text-gray-800 dark:text-gray-200">Name:</strong> {{ $project->name ?? 'Untitled Project' }}
                        </p>
                        <p class="sm:col-span-2 text-gray-700 dark:text-gray-300 animate-slide-in" style="animation-delay: 0.2s;">
                            <strong class="font-semibold text-gray-800 dark:text-gray-200">Description:</strong> {{ $project->description ?? 'No description' }}
                        </p>
                        <p class="text-gray-700 dark:text-gray-300 animate-slide-in" style="animation-delay: 0.3s;">
                            <strong class="font-semibold text-gray-800 dark:text-gray-200">Start Date:</strong> {{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('M d, Y') : 'Not set' }}
                        </p>
                        <p class="text-gray-700 dark:text-gray-300 animate-slide-in" style="animation-delay: 0.4s;">
                            <strong class="font-semibold text-gray-800 dark:text-gray-200">End Date:</strong> {{ $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('M d, Y') : 'Not set' }}
                        </p>
                    </div>

                </div>
            @empty
                <p class="no-projects-message animate-fade-in dark:text-gray-400">You have no assigned projects.</p>
            @endforelse
        </div>

        @if(!$projects->isEmpty())
            <div class="pagination animate-fade-in">
                {{ $projects->links() }}
            </div>
        @endif
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
