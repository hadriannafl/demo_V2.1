<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <div class="flex items-center justify-between">
            <h2 class="text-3xl font-semibold">AJU Management</h2>
        </div>

        <div id="containerAccount" class="bg-white shadow-md rounded-lg overflow-hidden mt-8">
            <div class="flex justify-between items-center px-6 py-4 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">AJU List</h2>
                <div class="flex items-center">
                    <div class="relative">
                        <form method="GET" action="{{ route('index.deleteaju') }}">
                            <input type="text" name="search" id="searchInput" value="{{ request('search') }}"
                                placeholder="Search..."
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
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                No</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Department</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Document Number</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Description</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Created By</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Created At</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($ajus as $index => $aju)
                            <tr class="hover:bg-gray-100 odd:bg-gray-100 even:bg-white">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ ($ajus->currentPage() - 1) * $ajus->perPage() + $index + 1 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $aju->department->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $aju->no_docs }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $aju->description }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $aju->createdByUser->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($aju->created_at)->format('Y-m-d') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div class="inline-flex space-x-2">
                                        @if (optional($aju->archives)->where('pdfblob', '!=', null)->isNotEmpty())
                                            <div x-data="modal()">
                                                <button
                                                    class="bg-blue-600 text-white px-3 py-1 rounded-md hover:bg-blue-700 flex items-center justify-center gap-2 transition duration-150 cursor-pointer"
                                                    type="button"
                                                    @click.prevent="modalOpenDetail = true; loadDetails(@js($aju->details))"
                                                    aria-controls="feedback-modal1">
                                                    View
                                                </button>
                                                <!-- Modal backdrop -->
                                                <div class="fixed inset-0 backdrop-blur bg-opacity-30 z-50 transition-opacity"
                                                    x-show="modalOpenDetail"
                                                    x-transition:enter="transition ease-out duration-200"
                                                    x-transition:enter-start="opacity-0"
                                                    x-transition:enter-end="opacity-100"
                                                    x-transition:leave="transition ease-out duration-100"
                                                    x-transition:leave-start="opacity-100"
                                                    x-transition:leave-end="opacity-0" aria-hidden="true" x-cloak></div>
                                                <!-- Modal dialog -->
                                                <div id="feedback-modal1"
                                                    class="fixed inset-0 z-50 overflow-hidden flex items-center my-4 justify-center px-4 sm:px-6"
                                                    role="dialog" aria-modal="true" x-show="modalOpenDetail"
                                                    x-transition:enter="transition ease-in-out duration-200"
                                                    x-transition:enter-start="opacity-0 translate-y-4"
                                                    x-transition:enter-end="opacity-100 translate-y-0"
                                                    x-transition:leave="transition ease-in-out duration-200"
                                                    x-transition:leave-start="opacity-100 translate-y-0"
                                                    x-transition:leave-end="opacity-0 translate-y-4" x-cloak>
                                                    <div class="bg-white rounded shadow-lg overflow-auto w-3/4 max-h-full"
                                                        @click.outside="modalOpenDetail = false"
                                                        @keydown.escape.window="modalOpenDetail = false">
                                                        <!-- Modal header -->
                                                        <div class="px-5 py-3 border-b border-slate-200"
                                                            id="modalAddLpjDetail">
                                                            <div class="flex justify-between items-center">
                                                                <div class="font-semibold text-slate-800">View AJU
                                                                    Document
                                                                    {{ $aju->no_docs }}</div>
                                                                <button type="button"
                                                                    class="text-slate-400 hover:text-slate-500 cursor-pointer"
                                                                    @click="modalOpenDetail = false">
                                                                    <div class="sr-only">Close</div>
                                                                    <svg class="w-4 h-4 fill-current">
                                                                        <path
                                                                            d="M7.95 6.536l4.242-4.243a1 1 0 111.415 1.414L9.364 7.95l4.243 4.242a1 1 0 11-1.415 1.415L7.95 9.364l-4.243 4.243a1 1 0 01-1.414-1.415L6.536 7.95 2.293 3.707a1 1 0 011.414-1.414L7.95 6.536z" />
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <!-- Modal content -->
                                                        <div class="modal-content text-xs px-5 py-4">
                                                            <!-- Display file names -->
                                                            <div id="file-names" class="mt-4 text-sm text-gray-700">
                                                                <template x-for="(detail, index) in details"
                                                                    :key="index">
                                                                    <template x-if="detail.archive">
                                                                        <div
                                                                            class="relative w-full flex items-center justify-between rounded-lg bg-[#e3f2fd] p-4 border border-[#90caf9] mb-4">
                                                                            <!-- Icon Folder -->
                                                                            <svg class="w-8 h-8 text-[#1976d2] mr-4"
                                                                                xmlns="http://www.w3.org/2000/svg"
                                                                                fill="none" viewBox="0 0 24 24"
                                                                                stroke="currentColor">
                                                                                <path stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    stroke-width="2"
                                                                                    d="M3 7a2 2 0 012-2h4l2 2h6a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" />
                                                                            </svg>
                                                                            <!-- Nama File -->
                                                                            <span
                                                                                class="truncate text-sm font-medium text-[#07074D] flex-1">
                                                                                <span
                                                                                    x-text="detail.archive.file_name"></span>
                                                                            </span>
                                                                            <!-- Tombol View PDF -->
                                                                            <button
                                                                                class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 cursor-pointer"
                                                                                @click="openPdfInNewTab(detail.archive.pdfblob)">
                                                                                View File
                                                                            </button>
                                                                        </div>
                                                                    </template>
                                                                </template>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <button
                                                class="bg-red-600 text-white px-3 py-1 rounded-md hover:bg-red-700 flex items-center justify-center gap-2 transition duration-150 cursor-pointer"
                                                type="button"
                                                onclick="confirmDelete('{{ route('aju.delete', ['id' => $aju->id_aju]) }}')">
                                                Delete
                                            </button>
                                        @else
                                            <a
                                                class="bg-red-50 text-red-800 px-2 py-1 rounded text-sm hover:bg-red-100 transition duration-150">
                                                No File available
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="bg-gray-50 rounded p-4">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <form method="GET" action="{{ route('index.deleteaju') }}">
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
                    {{ $ajus->links() }}
                </div>
            </div>

        </div>
    </div>
    @if (session('success'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Toastify({
                    text: "{{ session('success') }}",
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    style: {
                        background: "linear-gradient(to right, #00b09b, #96c93d)"
                    },
                    stopOnFocus: true,
                }).showToast();
            });
        </script>
    @endif

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('modal', () => ({
                modalOpenDetail: false,
                details: [],

                loadDetails(details) {
                    // Filter hanya detail yang memiliki archive dengan pdfblob
                    this.details = details.filter(detail =>
                        detail.archive &&
                        detail.archive.pdfblob &&
                        detail.archive.pdfblob !== ''
                    );

                    console.log('Loaded details:', this.details); // Untuk debugging
                },

                openPdfInNewTab(base64Data) {
                    if (base64Data) {
                        const byteCharacters = atob(base64Data);
                        const byteNumbers = new Array(byteCharacters.length);
                        for (let i = 0; i < byteCharacters.length; i++) {
                            byteNumbers[i] = byteCharacters.charCodeAt(i);
                        }
                        const byteArray = new Uint8Array(byteNumbers);
                        const blob = new Blob([byteArray], {
                            type: 'application/pdf'
                        });

                        const blobUrl = URL.createObjectURL(blob);
                        window.open(blobUrl, '_blank');

                        URL.revokeObjectURL(blobUrl);
                    } else {
                        Toastify({
                            text: "PDF data is missing or invalid.",
                            duration: 3000,
                            gravity: "top",
                            position: "right",
                            style: {
                                background: "#ff4d4d",
                                color: "#fff",
                                borderRadius: "5px",
                                padding: "10px"
                            }
                        }).showToast();
                    }
                }
            }));
        });

        function openPdfInNewTab(base64Data) {
            if (base64Data) {

                const byteCharacters = atob(base64Data);
                const byteNumbers = new Array(byteCharacters.length);
                for (let i = 0; i < byteCharacters.length; i++) {
                    byteNumbers[i] = byteCharacters.charCodeAt(i);
                }
                const byteArray = new Uint8Array(byteNumbers);
                const blob = new Blob([byteArray], {
                    type: 'application/pdf'
                });


                const blobUrl = URL.createObjectURL(blob);
                window.open(blobUrl, '_blank');


                URL.revokeObjectURL(blobUrl);
            } else {

                Toastify({
                    text: "PDF data is missing or invalid.",
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    style: {
                        background: "#ff4d4d",
                        color: "#fff",
                        borderRadius: "5px",
                        padding: "10px"
                    }
                }).showToast();
            }
        }

        function confirmDelete(url) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create a form dynamically
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;

                    // Add CSRF token
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);

                    // Add method spoofing for DELETE
                    const method = document.createElement('input');
                    method.type = 'hidden';
                    method.name = '_method';
                    method.value = 'DELETE';
                    form.appendChild(method);

                    // Submit the form
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // SweetAlert for success/error messages
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session('error') }}',
                timer: 3000,
                showConfirmButton: false
            });
        @endif
    </script>
</x-app-layout>
