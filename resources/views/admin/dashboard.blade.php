{{-- resources/views/admin/dashboard.blade.php --}}
<x-admin-layout>

    <h2 class="text-3xl font-semibold mb-6">System Settings</h2>

    @if (session('success'))
        <div class="p-4 bg-green-200 text-green-800 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.update') }}" method="POST" enctype="multipart/form-data"
        class="bg-white p-6 rounded-xl shadow-md">

        @csrf

        <div class="mb-6">
            <label class="block text-gray-700 font-medium">Header Title</label>
            <input type="text" name="header_title" class="w-full mt-1 border-gray-300 rounded-lg shadow-sm"
                value="{{ old('header_title', $settings->header_title ?? '') }}">
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 font-medium">Logo</label>
            <input type="file" name="logo" class="w-full mt-1 border-gray-300 rounded-lg shadow-sm">

            @if ($settings->logo_path ?? false)
                <img src="{{ asset('storage/' . $settings->logo_path) }}" class="mt-3 w-28 rounded shadow">
            @endif
        </div>

        <button class="w-full py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            Save Settings
        </button>

    </form>

</x-admin-layout>