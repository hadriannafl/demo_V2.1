<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <div class="flex items-center justify-between">
            <h2 class="text-3xl font-semibold">Account Management</h2>
        </div>

        <div id="containerAccount" class="bg-white shadow-md rounded-lg overflow-hidden mt-8">
            <div class="flex justify-between items-center px-6 py-4 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">Account List</h2>
                <div class="flex items-center">
                    <div class="relative">
                        <form method="GET" action="{{ route('users-management') }}" id="searchForm">
                            <input type="text" name="search" id="searchInput" value="{{ request('search') }}"
                                placeholder="Search users..."
                                class="pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 w-48">
                            <div class="absolute left-3 top-2.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table id="documentTable" class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Created At</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Updated By</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($users as $user)
                            <tr class="hover:bg-gray-100 odd:bg-gray-100 even:bg-white">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->role->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                        {{ $user->status === 'Active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $user->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($user->crated_at)->format('Y-m-d') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($user->updated_at)->format('Y-m-d') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    @if ($user->status === 'Active')
                                        <form action="{{ route('users.deactivate', $user->id) }}" method="POST"
                                            class="deactivate-form" style="display:inline;">
                                            @csrf
                                            <button type="button" onclick="confirmDeactivate(this)"
                                                class="bg-red-400 hover:bg-red-500 text-white font-semibold py-1 px-3 rounded cursor-pointer">
                                                Deactivate
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-gray-500">User is inactive</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
            <div class="bg-gray-50 rounded p-4">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <form method="GET" action="{{ route('users-management') }}">
                        <div class="flex items-center">
                            <label for="per_page" class="mr-2">Show:</label>
                            <select name="per_page" id="per_page" onchange="this.form.submit()"
                                class="border border-gray-300 rounded px-4 py-2 w-32">
                                <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
                                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                <option value="15" {{ $perPage == 15 ? 'selected' : '' }}>15</option>
                                <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                            </select>
                        </div>
                    </form>
                </div>

                <div class="px-6 py-4">
                    {{ $users->links() }}
                </div>
            </div>

        </div>
    </div>
    <script>
        // Submit search form when user stops typing (with 500ms delay)
        document.getElementById('searchInput').addEventListener('keyup', function(e) {
            clearTimeout(this.timer);
            this.timer = setTimeout(function() {
                document.getElementById('searchForm').submit();
            }, 500);
        });

        // Also submit when pressing Enter
        document.getElementById('searchInput').addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                document.getElementById('searchForm').submit();
            }
        });

        function confirmDeactivate(button) {
            if (confirm('Are you sure you want to deactivate this user?')) {
                const form = button.closest('form');

                // Using fetch API for AJAX submission
                fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({})
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Show success message
                            alert(data.toast.message);
                            // Reload the page to reflect changes
                            window.location.reload();
                        } else {
                            alert(data.toast.details);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while deactivating the user');
                    });
            }
        }

        // Alternative: If you want to use a nicer toast notification
        function showToast(type, message, details) {
            // Implement your preferred toast notification here
            // Example with SweetAlert:
            Swal.fire({
                icon: type,
                title: message,
                text: details,
                timer: 3000
            });
        }
    </script>
</x-app-layout>
