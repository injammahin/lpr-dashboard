<x-app-layout>
    <div class="flex">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-800 text-white h-screen flex flex-col justify-between">
            <div>
                <div class="p-4 text-center text-xl font-semibold">
                    <span>Admin Panel</span>
                </div>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="block p-4 hover:bg-gray-700">
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="/dashboard-ui" target="_blank" class="block p-4 hover:bg-gray-700">
                            Back to Application
                        </a>
                    </li>
                </ul>
            </div>
            <div class="p-4 text-center">
                <button onclick="document.getElementById('logoutModal').classList.remove('hidden')"
                    class="w-full p-2 bg-red-500 text-white rounded-md">Logout</button>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-6 bg-gray-100">
            <div class="max-w-7xl mx-auto">
                <h2 class="text-3xl font-semibold mb-6">Settings</h2>

                <!-- Settings Form -->
                <form action="{{ route('admin.update') }}" method="POST" enctype="multipart/form-data"
                    class="bg-white p-6 rounded-lg shadow-md">
                    @csrf

                    <div class="mb-6">
                        <label for="header_title" class="block text-sm font-medium text-gray-700">Header Title</label>
                        <input type="text" id="header_title" name="header_title"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            value="{{ old('header_title', $settings->header_title ?? '') }}" required>
                    </div>

                    <div class="mb-6">
                        <label for="logo" class="block text-sm font-medium text-gray-700">Logo</label>
                        <input type="file" id="logo" name="logo"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        @if (isset($settings->logo_path))
                            <img src="{{ asset('storage/' . $settings->logo_path) }}" alt="Logo" class="mt-2 w-32">
                        @endif
                    </div>

                    <div class="mb-6">
                        <button type="submit" class="px-4 py-2 bg-red-500  text-white rounded-md w-full ">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- LOGOUT MODAL -->
    <div id="logoutModal" class="fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg">
            <h3 class="text-lg font-semibold">Are you sure you want to logout?</h3>
            <div class="flex gap-4 mt-4">
                <button onclick="logout()" class="px-4 py-2 bg-red-500 text-white rounded-md">Yes, Logout</button>
                <button onclick="closeLogoutModal()" class="px-4 py-2 bg-gray-300 rounded-md">Cancel</button>
            </div>
        </div>
    </div>

    <script>
        // Logout Modal
        function showLogoutModal() {
            document.getElementById('logoutModal').classList.remove('hidden');
        }

        function closeLogoutModal() {
            document.getElementById('logoutModal').classList.add('hidden');
        }

        function logout() {
            document.getElementById('logoutModal').classList.add('hidden');
            window.location.href = '/logout'; // Adjust the logout route as needed
        }
    </script>
</x-app-layout>