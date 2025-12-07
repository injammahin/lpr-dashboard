<x-guest-layout>

    <!-- LOGO -->
    <div class="flex justify-center mb-6 mt-4">
        <img id="logo" src=""
            class="w-24 h-24 rounded-full shadow-lg border border-gray-200 p-1 bg-white/10 backdrop-blur-lg" alt="Logo"
            style="height: 70px">
    </div>

    <!-- TITLES -->
    <h1 class="text-2xl font-bold text-gray-900 text-center">Welcome to <span class="text-indigo-600">Lakewood
            Shomrim</span></h1>

    <h1 class="text-lg font-semibold text-gray-700 text-center mt-1">Security System</h1>

    <h1 class="text-sm text-gray-500 text-center mb-6 tracking-wide">
        Community safety and security monitoring platform
    </h1>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6 px-2">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="font-semibold text-gray-700" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required
                autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-600" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="font-semibold text-gray-700" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-600" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-2">
            <label for="remember_me" class="inline-flex items-center text-gray-700">
                <input id="remember_me" type="checkbox"
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm">Remember me</span>
            </label>
        </div>

        <!-- Login Actions -->
        <div class="flex items-center justify-between mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-indigo-600 hover:text-indigo-800" href="{{ route('password.request') }}">
                    Forgot your password?
                </a>
            @endif

            <x-primary-button class="bg-indigo-600 hover:bg-indigo-700">
                {{ __('Log in') }}
            </x-primary-button>
        </div>

        <!-- Google Login Button -->
        <a href="{{ route('google.redirect') }}" class="w-full flex items-center justify-center gap-3 py-3 mt-4 rounded-lg 
                   font-semibold bg-white border shadow hover:bg-gray-100 transition text-gray-700">
            <img src="https://developers.google.com/identity/images/g-logo.png" class="w-5 h-5">
            <span>Sign in with Google</span>
        </a>

        <!-- Register Link -->
        <p class="text-center text-sm text-gray-600 mt-3">
            Not registered yet?
            <a href="{{ route('register') }}" class="text-indigo-600 font-semibold hover:underline">
                Register Now
            </a>
        </p>

    </form>

    <h1 class="text-center mt-8 text-gray-500 text-sm tracking-wide">
        🔒 Powered by <span class="text-indigo-600 font-semibold">Lakewood Shomrim Safety Patrol</span>
    </h1>

    <!-- FETCH DYNAMIC LOGO -->
    <script>
        fetch('/settings')
            .then(res => res.json())
            .then(data => {
                document.getElementById('logo').src = "/storage/" + data.logo_path;
            });
    </script>

</x-guest-layout>