<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name', 'Laravel App'))</title>
   
      <link rel="stylesheet" href="{{ url('build/assets/app-3zy86uaP.css') }}">

</head>
<body class="bg-gray-100">

<!-- Navbar -->
<nav class="bg-white shadow mb-6">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <a href="{{ route('dashboard') }}" class="text-xl font-bold text-blue-600">
            {{ config('app.name', 'Laravel App') }}
        </a>
        <div class="flex items-center space-x-4">
            <span>{{ auth()->user()->username ?? 'Guest' }}</span>
            @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition duration-150">
                        Logout
                    </button>
                </form>
            @endauth
        </div>
    </div>
</nav>

<!-- Main Content -->
<main class="container mx-auto px-4">
    @yield('content')
</main>

<!-- Footer -->
<footer class="mt-8 py-4 bg-white">
    <div class="container mx-auto px-4 text-center text-gray-600">
        © {{ date('Y') }} {{ config('app.name', 'Laravel App') }}. All rights reserved.
    </div>
</footer>

<!-- Optional Scripts -->
@stack('scripts')

</body>
</html>
