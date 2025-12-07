<x-app-layout>

    <div class="max-w-5xl mx-auto p-6 space-y-8">

        <!-- PAGE TITLE -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">🚨 Manage Alerts</h1>
                <p class="text-gray-500 mt-1">Create and manage license plate alerts easily.</p>
            </div>

            <a href="/dashboard-ui" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg border border-gray-300 
              hover:bg-gray-200 hover:text-gray-900 shadow-sm transition flex items-center gap-2">
                <span>←</span> Back
            </a>
        </div>

        {{-- SUCCESS MESSAGE --}}
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg border border-green-300 shadow">
                <strong>Success:</strong> {{ session('success') }}
            </div>
        @endif

        <!-- CREATE ALERT CARD -->
        <div class="bg-white/90 backdrop-blur-md p-6 rounded-xl shadow-lg border border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">➕ Create New Alert</h2>

            <form action="{{ route('alerts.store') }}" method="POST" class="space-y-4">
                @csrf

                <div class="flex gap-4">
                    <input type="text" name="plate" placeholder="Enter license plate"
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:outline-none text-gray-800">

                    <button type="submit"
                        class="px-6 py-2 bg-red-600 text-white font-medium rounded-lg shadow hover:bg-red-700 transition">
                        Add Alert
                    </button>
                </div>
            </form>
        </div>

        <!-- ALERT TABLE -->
        <div class="bg-white/90 backdrop-blur-md p-6 rounded-xl shadow-lg border border-gray-200">

            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold text-gray-800">📋 Your Alerts</h2>
            </div>

            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b">
                        <th class="py-3 px-2 text-left text-gray-600 font-medium">Plate</th>
                        <th class="py-3 px-2 text-left text-gray-600 font-medium">Created</th>
                        <th class="py-3 px-2 text-right text-gray-600 font-medium">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($alerts as $alert)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="py-3 px-2 font-semibold text-gray-800">{{ $alert->plate }}</td>
                            <td class="py-3 px-2 text-gray-500">{{ $alert->created_at->format('Y-m-d H:i') }}</td>
                            <td class="py-3 px-2 text-right">
                                <button onclick="confirmDelete('{{ $alert->id }}')"
                                    class="px-3 py-1.5 text-sm bg-red-500 text-white rounded-md shadow hover:bg-red-600 transition">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-4 text-center text-gray-500">
                                No alerts found. Create your first alert above 👆
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>

        <!-- DELETE MODAL -->
        <div id="deleteModal"
            class="fixed inset-0 bg-black bg-opacity-60 hidden justify-center items-center z-50 backdrop-blur-sm">
            <div class="bg-white p-6 rounded-xl shadow-2xl w-80 border border-gray-200">
                <h2 class="text-lg font-bold text-gray-800 mb-3">Delete Alert</h2>
                <p class="text-gray-600">Are you sure you want to delete this alert?</p>

                <div class="mt-6 flex justify-end gap-4">
                    <button onclick="closeDeleteModal()"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                        Cancel
                    </button>

                    <button id="deleteBtn" class="px-4 py-2 bg-red-600 text-white rounded-lg shadow hover:bg-red-700">
                        Yes, Delete
                    </button>
                </div>
            </div>
        </div>

    </div>

    {{-- JS --}}
    <script>
        let deleteId = null;

        function confirmDelete(id) {
            deleteId = id;
            let modal = document.getElementById('deleteModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        document.getElementById('deleteBtn').onclick = function () {
            fetch(`/alerts/${deleteId}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                }
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success || data.message === "Alert deleted") {
                        window.location.reload();
                    }
                });
        };
    </script>

</x-app-layout>