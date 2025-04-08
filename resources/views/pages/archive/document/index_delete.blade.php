<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <!-- Header Section -->
        <div class="flex items-center justify-between">
            <h2 class="text-3xl font-semibold">Document Management</h2>
        </div>

        <!-- Container for Document List -->
        <div id="containerAccount" class="bg-white shadow-md rounded-lg overflow-hidden mt-8">
            <!-- Table Header -->
            <div class="flex justify-between items-center px-6 py-4 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">Document List</h2>
                <div class="flex items-center">
                    <form method="GET" action="{{ route('index.editDocument') }}">
                        <label for="search" class="mr-2">Search:</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                            class="border border-gray-300 rounded px-4 py-2 w-48 mr-2" placeholder="Search...">
                    </form>
                </div>
            </div>

            <!-- Table Body -->
            <div class="table-responsive">
                <table id="documentTable" class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                No</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Document Type</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Description</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                File Name</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Created At</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($archives as $index => $archive)
                            <tr class="hover:bg-gray-100 odd:bg-gray-100 even:bg-white">
                            <tr class="hover:bg-gray-100 odd:bg-gray-100 even:bg-white">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $index + $archives->firstItem() }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $archive->date ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $archive->doc_type ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $archive->description ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <a onclick="openPdfInNewTab('{{ $archive->pdfblob }}')"
                                        class="text-indigo-600 hover:text-indigo-900 cursor-pointer">{{ $archive->file_name ?? '-' }}</a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $archive->created_at->format('Y-m-d') }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 flex justify-center space-x-2">
                                    <form method="POST" action="{{ route('index.formDelete', $archive->idrec) }}">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit"
                                            onclick="return confirm('Are you sure you want to delete this document?')"
                                            class="bg-red-600 text-white px-3 py-1 rounded-md hover:bg-red-800 flex items-center">
                                            Delete
                                        </button>
                                    </form>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div class="bg-gray-50 rounded p-4">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <form method="GET" action="{{ route('index.editDocument') }}">
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
                    {{ $archives->links() }}
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
        function openPdfInNewTab(base64Data) {
            if (base64Data) {
                // Convert base64 to a blob
                const byteCharacters = atob(base64Data);
                const byteNumbers = new Array(byteCharacters.length);
                for (let i = 0; i < byteCharacters.length; i++) {
                    byteNumbers[i] = byteCharacters.charCodeAt(i);
                }
                const byteArray = new Uint8Array(byteNumbers);
                const blob = new Blob([byteArray], {
                    type: 'application/pdf'
                });

                // Create a blob URL and open it in a new tab
                const blobUrl = URL.createObjectURL(blob);
                window.open(blobUrl, '_blank');

                // Revoke the blob URL after opening to free up memory
                URL.revokeObjectURL(blobUrl);
            } else {
                // Show an error message if the PDF data is missing or invalid
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
    </script>
</x-app-layout>
