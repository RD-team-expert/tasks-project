@extends('layouts.applogin')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-md">
            <h2 class="text-2xl font-bold text-center mb-6">Login to Your Account</h2>

            @if ($errors->any())
                <div class="mb-4 text-red-600 text-center">
                    {{ $errors->first('email') }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Email -->
                <div>
                    <label class="block font-medium text-gray-700">Email</label>
                    <input type="email" name="email" required value="{{ old('email') }}"
                           class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                </div>

                <!-- Password -->
                <div>
                    <label class="block font-medium text-gray-700">Password</label>
                    <input type="password" name="password" required
                           class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input type="checkbox" name="remember" class="border-gray-300 shadow-sm">
                    <label class="ml-2 text-gray-700">Remember Me</label>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit"
                            class="w-full bg-indigo-500 text-black py-2 rounded-md hover:bg-indigo-600 transition duration-150">
                        Login
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
