<x-app-layout>
    <!-- HEADER NAVIGATION -->
    <div class="w-full bg-white border-b shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between px-4 py-3 gap-3">
            <div class="flex flex-wrap items-center gap-3">
                <!-- Logo & Text from Backend -->
                <img id="logo" src="/dashboard-ui" alt="Logo" class="w-32 h-auto" style="height: 50px">
                <span id="headerText" class="text-lg font-semibold">Lakewood Shomrim LPR System</span>

                <!-- Camera Filter -->
                <select id="cameraFilter" class="px-4 py-1.5 bg-gray-100 rounded-md hover:bg-gray-200"
                    style="width: 150px" onchange="filterCamera()">
                    <option value="all">All Cameras</option>
                    @foreach($cameras as $cam)
                        <option value="{{ $cam->name }}" @selected(request('camera') == $cam->name)>{{ $cam->name }}</option>
                    @endforeach
                </select>

                <button class="px-3 py-1.5 bg-gray-100 rounded-md hover:bg-gray-200">Notifications</button>
                <button class="px-3 py-1.5 bg-gray-100 rounded-md hover:bg-gray-200">Advanced Search</button>
            </div>

            <!-- SEARCH BAR -->
            <input type="text" id="search" placeholder="Search by plate"
                class="px-3 py-1 border rounded-md w-48 md:w-64" value="{{ request('search') }}" onkeyup="autoSearch()">


            <!-- LOGOUT ICON (Only if Logged In) -->
            @auth
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                                                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            @endauth

        </div>
    </div>

    <!-- LIVE FEED SWITCH -->
    <div class="max-w-7xl mx-auto px-4 py-4 flex items-center gap-2">
        <label class="flex items-center gap-2 cursor-pointer">
            <span class="text-sm font-medium">Live Feed</span>
            <input type="checkbox" id="liveToggle" onchange="toggleLiveFeed()" class="w-5 h-5">
        </label>
    </div>

    <!-- DETECTION GRID -->
    <div class="max-w-7xl mx-auto px-4 pb-10">
        <div id="detectionGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($detections as $d)
                <div class="rounded-xl overflow-hidden bg-white shadow hover:shadow-lg transition border">
                    <img src="{{ route('image.proxy', ['path' => $d->file_path]) }}"
                        class="w-full h-48 object-cover bg-gray-200">
                    <div class="p-4 space-y-2">
                        <div class="text-xl font-bold tracking-wider text-gray-900">{{ $d->plate }}</div>
                        <div class="text-sm text-gray-600">Camera: <span class="font-semibold">{{ $d->camera->name }}</span>
                        </div>
                        <div class="text-xs text-gray-500">{{ $d->date_str }} — {{ $d->time_str }}</div>
                    </div>
                    <a href="/api/detections/{{ $d->id }}/download"
                        class="block text-center bg-black text-white py-2 text-sm font-medium hover:bg-gray-800">Download
                        Image</a>
                </div>
            @endforeach
        </div>

        <!-- PAGINATION -->
        <div class="flex justify-between items-center mt-6">
            {{-- <div>
                <!-- Items Per Page Dropdown -->
                <label for="itemsPerPage" class="text-sm text-gray-700">Items Per Page</label>
                <select id="itemsPerPage" onchange="changeItemsPerPage()"
                    class="px-4 py-2 bg-gray-100 rounded-md hover:bg-gray-200">
                    <option value="10" @selected(request('per_page')==10)>10</option>
                    <option value="60" @selected(request('per_page')==60)>60</option>
                    <option value="80" @selected(request('per_page')==80)>80</option>
                    <option value="100" @selected(request('per_page')==100)>100</option>
                </select>
            </div> --}}

            <!-- Pagination Links -->
            <div>
                {{ $detections->links() }}
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

    <!-- SCRIPTS -->
    <script>
        // ---------------- CAMERA FILTER ----------------
        function filterCamera() {
            const cam = document.getElementById('cameraFilter').value;
            window.location.href = "/dashboard-ui?camera=" + cam;
        }

        // ---------------- LIVE FEED ----------------
        let latestId = {{ $detections->first()->id ?? 0 }};
        let live = false;
        let timer = null;
        let displayedPlates = new Set(); // Set to track already displayed plates

        function toggleLiveFeed() {
            live = document.getElementById('liveToggle').checked;

            if (live) {
                displayedPlates.clear(); // Clear displayed plates when live feed is enabled
                timer = setInterval(fetchLiveUpdates, 2000); // Fetch updates every 2 seconds
            } else {
                clearInterval(timer);
            }
        }

        function fetchLiveUpdates() {
            fetch(`/api/live?after_id=${latestId}`)
                .then(res => res.json())
                .then(list => {
                    if (list.length === 0) return; // No new updates

                    list.forEach(det => appendCard(det)); // Add new detections to UI
                    latestId = list[list.length - 1].id; // Update the latest ID
                });
        }

        // ---------------- ADD LIVE CARD TO UI ----------------
        function appendCard(det) {
            const grid = document.getElementById('detectionGrid');

            // If the plate already exists in the set, do not add it again
            if (displayedPlates.has(det.plate)) {
                return;
            }

            // Add the plate to the set
            displayedPlates.add(det.plate);

            const card = `
            <div class="rounded-xl overflow-hidden bg-white shadow hover:shadow-lg transition border">
                <img src="/image-proxy?path=${encodeURIComponent(det.file_path)}"
                     class="w-full h-48 object-cover bg-gray-200">
                <div class="p-4 space-y-2">
                    <div class="text-xl font-bold tracking-wider text-gray-900">${det.plate}</div>
                    <div class="text-sm text-gray-600">
                        Camera: <span class="font-semibold">${det.camera.name}</span>
                    </div>
                    <div class="text-xs text-gray-500">${det.date_str} — ${det.time_str}</div>
                </div>
                <a href="/api/detections/${det.id}/download"
                   class="block text-center bg-black text-white py-2 text-sm font-medium hover:bg-gray-800">
                    Download Image
                </a>
            </div>`;

            grid.insertAdjacentHTML('afterbegin', card); // Insert the card into the grid
        }

        // ---------------- AUTO SEARCH ----------------
        function autoSearch() {
            const query = document.getElementById('search').value;
            if (query.length > 2) {
                window.location.href = "/dashboard-ui?search=" + query; // Redirect to updated search results
            }
        }

        // ---------------- CHANGE ITEMS PER PAGE ----------------
        function changeItemsPerPage() {
            const itemsPerPage = document.getElementById('itemsPerPage').value;
            window.location.href = "/dashboard-ui?per_page=" + itemsPerPage;
        }

        // ---------------- FETCH LOGO AND HEADER TEXT ----------------
        fetch('/settings')
            .then(response => response.json())
            .then(data => {
                // Set the logo and header text
                document.getElementById('logo').src = "/storage/" + data.logo_path;
                document.getElementById('headerText').textContent = data.header_title;
            });

        // ---------------- LOGOUT MODAL ----------------
        function showLogoutModal() {
            document.getElementById('logoutModal').classList.remove('hidden');
        }

        function closeLogoutModal() {
            document.getElementById('logoutModal').classList.add('hidden');
        }

        function logout() {
            // Perform logout action, typically an API call to logout the user
            document.getElementById('logoutModal').classList.add('hidden');
            window.location.href = '/logout'; // Redirect to logout route
        }
    </script>
</x-app-layout>