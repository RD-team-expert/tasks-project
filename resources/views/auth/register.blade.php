    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>User Registration</title>
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    </head>
    <body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 shadow rounded-lg w-full max-w-sm">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Register New Account</h2>

            @if ($errors->any())
                <div class="mb-4 text-red-600">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block text-gray-700">Username</label>
                    <input type="text" name="username" required value="{{ old('username') }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">Email</label>
                    <input type="email" name="email" required value="{{ old('email') }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">Password</label>
                    <input type="password" name="password" required
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">Confirm Password</label>
                    <input type="password" name="password_confirmation" required
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500">
                </div>

                <button type="submit" class="w-full bg-indigo-500 text-white p-2 rounded hover:bg-indigo-600 transition">
                    Register
                </button>

                <div class="mt-4 text-center text-gray-600">
                    Already have an account? <a href="{{ route('login') }}" class="text-indigo-600">Login here</a>
                </div>
            </form>
        </div>
    </div>
    </body>
    </html>

