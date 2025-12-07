<x-guest-layout>

    <div class="flex justify-center mt-10">
        <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-xl border border-gray-200">

            <div class="text-center mb-6">
                <img src="{{ asset('logo.png') }}" class="w-20 h-20 mx-auto mb-3 drop-shadow" alt="Logo">

                <h2 class="text-3xl font-bold text-gray-800">Finish Your Registration</h2>

                <p class="text-gray-600 mt-1 text-sm">
                    You were invited to access the system using:
                </p>

                <p class="font-semibold text-indigo-600">{{ $invite->email }}</p>
            </div>

            @if ($errors->any())
                <div class="bg-red-100 border border-red-300 text-red-700 p-3 rounded mb-4">
                    <ul class="text-sm">
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('invite.register.submit', $invite->token) }}" class="space-y-4">
                @csrf

                <!-- FULL NAME -->
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Full Name</label>
                    <input name="name" value="{{ old('name') }}"
                        class="w-full border-gray-300 p-3 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Enter your full name" required>
                </div>

                <!-- PASSWORD -->
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Password</label>
                    <input type="password" name="password"
                        class="w-full border-gray-300 p-3 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Create a password" required>
                </div>

                <!-- CONFIRM PASSWORD -->
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Confirm Password</label>
                    <input type="password" name="password_confirmation"
                        class="w-full border-gray-300 p-3 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Re-enter password" required>
                </div>

                <button
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-3 rounded-lg font-semibold transition shadow-md">
                    Create Account
                </button>

                <p class="text-center text-sm text-gray-500 mt-3">
                    Already registered?
                    <a href="{{ route('login') }}" class="text-indigo-600 font-medium hover:underline">Login</a>
                </p>

            </form>

        </div>
    </div>

</x-guest-layout>