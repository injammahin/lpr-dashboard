<x-app-layout>
    <div class="max-w-3xl mx-auto p-6 bg-white rounded shadow">
        <form method="POST" enctype="multipart/form-data">
            @csrf

            <label class="block font-semibold">Header Text</label>
            <input type="text" name="header_text" class="w-full border p-2" value="{{ $settings->header_text }}">

            <label class="block mt-4 font-semibold">Logo</label>
            <input type="file" name="logo">

            <button class="mt-4 px-4 py-2 bg-black text-white rounded">Save</button>
        </form>
    </div>
</x-app-layout>