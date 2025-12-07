<!-- resources/views/layouts/admin.blade.php -->

<x-app-layout>
    <div class="flex min-h-screen bg-gray-100">

        <!-- SIDEBAR -->
        <aside class="w-64 bg-gray-900 text-white shadow-lg flex flex-col justify-between" style="padding: 30px">

            <div>
                <div class="p-5 text-center border-b border-gray-700">
                    <h1 class="text-xl font-bold tracking-wide">⚙️ Admin Panel</h1>
                </div>

                <nav class="mt-4">
                    <a href="{{ route('admin.dashboard') }}"
                        class="block px-5 py-3 hover:bg-gray-800 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800' : '' }}">
                        📊 Dashboard
                    </a>

                    <a href="{{ route('invite.page') }}"
                        class="block px-5 py-3 hover:bg-gray-800 {{ request()->routeIs('invite.page') ? 'bg-gray-800' : '' }}">
                        ✉️ Invite Users
                    </a>

                    <a href="/dashboard-ui" target="_blank" class="block px-5 py-3 hover:bg-gray-800">
                        🖥️ Back to Application
                    </a>
                </nav>
            </div>

            <div class="p-5 border-t border-gray-700">
                <button onclick="openLogoutModal()"
                    class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg shadow-md">
                    Logout
                </button>
            </div>

        </aside>

        <!-- MAIN CONTENT -->
        <main class="flex-1 p-8">
            {{ $slot }}
        </main>
    </div>

    <!-- LOGOUT MODAL -->
    <div id="logoutModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden justify-center items-center z-50">

        <div class="bg-white p-6 rounded-xl shadow-2xl w-80">
            <h2 class="text-lg font-bold mb-3">Confirm Logout</h2>
            <p class="text-gray-600">Are you sure you want to logout?</p>

            <div class="mt-5 flex justify-end gap-3">
                <button onclick="closeLogoutModal()" class="px-4 py-2 bg-gray-300 rounded-lg">Cancel</button>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="px-4 py-2 bg-red-600 text-white rounded-lg">Logout</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openLogoutModal() {
            document.getElementById('logoutModal').classList.remove('hidden');
            document.getElementById('logoutModal').classList.add('flex');
        }
        function closeLogoutModal() {
            document.getElementById('logoutModal').classList.add('hidden');
            document.getElementById('logoutModal').classList.remove('flex');
        }
    </script>
</x-app-layout>