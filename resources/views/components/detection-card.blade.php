<div class="bg-white rounded-lg shadow p-4 hover:shadow-md transition">

    <!-- Image with proper URL encoding -->
    <img src="{{ route('image.proxy', ['path' => urlencode($d->file_path)]) }}" class="w-full rounded-lg shadow">

    <div class="mt-3 space-y-1">

        <!-- Camera Name -->
        <p class="text-sm font-semibold">Camera: {{ $d->camera->name }}</p>

        <!-- License Plate -->
        <p class="text-sm">Plate: <span class="font-bold">{{ $d->plate }}</span></p>

        <!-- Date and Time -->
        <p class="text-xs text-gray-500">{{ $d->date_str }} | {{ $d->time_str }}</p>

        <!-- Download Button -->
        <a href="/api/detections/{{ $d->id }}/download"
            class="block text-center bg-black text-white py-2 rounded-md hover:bg-gray-800">
            Download
        </a>

    </div>

</div>