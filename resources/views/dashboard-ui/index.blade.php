<x-app-layout>

    {{-- ========================= HEADER ========================= --}}
    <div class="w-full bg-white border-b shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto flex items-center justify-between px-4 py-3">

            {{-- LEFT SIDE --}}
            <div class="flex items-center gap-4">

                {{-- LOGO --}}
                <img id="logo" src="" class="h-10 w-auto object-contain" alt="Logo">

                {{-- TEXT --}}
                <span id="headerText" class="text-lg font-semibold">Lakewood Shomrim LPR System</span>

                {{-- CAMERA FILTER --}}
                <select id="cameraFilter" onchange="filterCamera()"
                    class="px-3 py-1.5 bg-gray-200 rounded-md hover:bg-gray-300">
                    <option value="all">All Cameras</option>
                    @foreach($cameras as $cam)
                        <option value="{{ $cam->name }}" @selected(request('camera') == $cam->name)>
                            {{ $cam->name }}
                        </option>
                    @endforeach
                </select>

                {{-- ALERT BUTTON --}}
                <button onclick="openAlertModal()"
                    class="px-3 py-1.5 bg-red-600 text-white rounded-md hover:bg-red-700">
                    Add Alert
                </button>

                {{-- MANAGE ALERTS --}}
                <a href="/alerts" class="px-3 py-1.5 bg-gray-100 rounded-md hover:bg-gray-200">
                    Manage Alerts
                </a>
            </div>

            {{-- SEARCH BAR --}}
            <input type="text" id="search" placeholder="Search by plate" value="{{ request('search') }}"
                onkeyup="handleSearch(event)" class="px-3 py-1 border rounded-md w-52 md:w-64">

            {{-- USER DROPDOWN --}}
            @auth
                <div class="sm:flex sm:items-center">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-3 py-2 rounded-md text-gray-500 bg-white hover:text-gray-700">
                                <div>{{ Auth::user()->name }}</div>
                                <svg class="h-4 w-4 ml-1" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0
                                        111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1
                                        1 0 010-1.414z" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">Profile</x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    Log Out
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            @endauth
        </div>
    </div>



    {{-- ========================= LIVE FEED TOGGLE ========================= --}}
    <div class="max-w-7xl mx-auto px-4 py-4">
        <label class="flex items-center gap-2 cursor-pointer">
            <span class="font-medium text-sm">Live Feed</span>
            <input type="checkbox" id="liveToggle" onchange="toggleLiveFeed()" class="w-5 h-5">
        </label>
    </div>



    {{-- ========================= DETECTION GRID ========================= --}}
    <div class="max-w-7xl mx-auto px-4 pb-10">

        <div id="detectionGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">

            @foreach($detections as $d)
                <div class="rounded-xl overflow-hidden bg-white shadow hover:shadow-lg transition border">
                    <img src="{{ route('image.proxy', ['path' => $d->file_path]) }}"
                        class="w-full h-48 object-cover bg-gray-200">

                    <div class="p-4 space-y-1">
                        <div class="text-xl font-bold tracking-wider">{{ $d->plate }}</div>
                        <div class="text-sm text-gray-600">
                            Camera: <span class="font-semibold">{{ $d->camera->name }}</span>
                        </div>
                        <div class="text-xs text-gray-500">{{ $d->date_str }} — {{ $d->time_str }}</div>
                    </div>

                    <a href="/api/detections/{{ $d->id }}/download"
                        class="block text-center bg-black text-white py-2 text-sm hover:bg-gray-800">
                        Download Image
                    </a>
                </div>
            @endforeach
        </div>

        {{-- PAGINATION --}}
        <div class="mt-6 flex justify-center">
            {{ $detections->appends(request()->query())->links() }}
        </div>
    </div>



    {{-- ========================= ALERT POPUP ========================= --}}
    <div id="alertPopupContainer"></div>



    {{-- ========================= ALERT MODAL ========================= --}}
    <div id="alertModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50">
        <div class="bg-white p-6 rounded-xl w-96 shadow-xl">
            <h2 class="text-xl font-bold mb-3">Create Alert</h2>

            <input id="alertPlate" type="text" placeholder="License Plate" class="w-full px-4 py-2 border rounded">

            <button onclick="createAlert()" class="w-full mt-3 bg-red-600 text-white py-2 rounded-md hover:bg-red-700">
                Save Alert
            </button>

            <button onclick="closeAlertModal()" class="w-full mt-2 bg-gray-300 py-2 rounded-md hover:bg-gray-400">
                Cancel
            </button>
        </div>
    </div>



    {{-- ========================= SCRIPTS ========================= --}}
    <script>
        let latestId = {{ $detections->first()->id ?? 0 }};
        let live = false;
        let timer = null;
        let shownPlates = new Set();

        /* ================= FILTER CAMERA ================= */
        function filterCamera() {
            const cam = document.getElementById('cameraFilter').value;
            window.location.href = "/dashboard-ui?camera=" + cam;
        }

        /* ================= SEARCH ================= */
        function handleSearch(e) {
            if (e.key === "Enter") {
                let q = document.getElementById('search').value;
                window.location.href = "/dashboard-ui?search=" + q;
            }
        }

        /* ================= LIVE FEED ================= */
        function toggleLiveFeed() {
            live = document.getElementById('liveToggle').checked;

            if (live) {
                shownPlates.clear();
                timer = setInterval(fetchLiveUpdates, 2000);
            } else {
                clearInterval(timer);
            }
        }

        function fetchLiveUpdates() {
            fetch(`/api/live?after_id=${latestId}`)
                .then(res => res.json())
                .then(list => {
                    if (!list.length) return;
                    list.forEach(det => appendCard(det));
                    latestId = list[list.length - 1].id;
                });
        }

        function appendCard(det) {
            if (shownPlates.has(det.plate)) return;
            shownPlates.add(det.plate);

            let grid = document.getElementById('detectionGrid');

            let card = `
    <div class="rounded-xl overflow-hidden bg-white shadow hover:shadow-lg transition border">
        <img src="/image-proxy?path=${encodeURIComponent(det.file_path)}"
             class="w-full h-48 object-cover bg-gray-200">

        <div class="p-4 space-y-1">
            <div class="text-xl font-bold">${det.plate}</div>
            <div class="text-sm text-gray-600">
                Camera: <span class="font-semibold">${det.camera.name}</span>
            </div>
            <div class="text-xs text-gray-500">${det.date_str} — ${det.time_str}</div>
        </div>

        <a href="/api/detections/${det.id}/download"
           class="block text-center bg-black text-white py-2 text-sm hover:bg-gray-800">
            Download Image
        </a>
    </div>`;

            grid.insertAdjacentHTML('afterbegin', card);
        }

        /* ================= ALERT MODAL ================= */
        function openAlertModal() {
            document.getElementById("alertModal").classList.remove("hidden");
            document.getElementById("alertModal").classList.add("flex");
        }

        function closeAlertModal() {
            document.getElementById("alertModal").classList.add("hidden");
        }

        async function createAlert() {
            let plate = document.getElementById("alertPlate").value.toUpperCase();
            let token = "{{ csrf_token() }}";

            let res = await fetch("/alerts", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": token
                },
                body: JSON.stringify({ plate })
            });

            closeAlertModal();
            showSuccess("Alert Added Successfully!");
        }

        /* ================= NICE SUCCESS POPUP ================= */
        function showSuccess(msg) {
            let box = `
        <div class="fixed bottom-6 right-6 bg-green-600 text-white px-5 py-3 rounded-lg shadow-lg animate-slideIn z-50">
            ${msg}
        </div>
    `;
            document.body.insertAdjacentHTML("beforeend", box);
            setTimeout(() => document.body.lastElementChild.remove(), 3000);
        }

        /* ================= LOAD SETTINGS (LOGO + TITLE) ================= */
        fetch('/settings')
            .then(res => res.json())
            .then(data => {
                document.getElementById('logo').src = "/storage/" + data.logo_path;
                document.getElementById('headerText').textContent = data.header_title;
            });
    </script>
    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const token = urlParams.get("token");

        if (token) {
            localStorage.setItem("auth_token", token);
            console.log("Token saved:", token);

            // Remove token from URL after saving
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    </script>

    <style>
        @keyframes slideIn {
            from {
                transform: translateX(120%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .animate-slideIn {
            animation: slideIn .35s ease-out;
        }
    </style>

</x-app-layout>