<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <div class="flex items-center justify-between">
            <h2 class="text-3xl font-semibold">AJU Document Management</h2>
        </div>

        <div id="containerAccount" class="bg-white shadow-md rounded-lg overflow-hidden mt-8">
            <div class="flex justify-between items-center px-6 py-4 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">AJU Number : {{ $ajuDocs->no_docs }}</h2>
                <div class="flex items-center">
                    <form method="GET" action="{{ route('index.newaju') }}">
                        <label for="search" class="mr-2">Search:</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                            class="border border-gray-300 rounded px-4 py-2 w-48 mr-2" placeholder="Search...">
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table id="documentTable" class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Document Number
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">File</th>
                            <th class="px-6 py-3 text-Center text-xs font-medium text-gray-500 uppercase">Actions Delete</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @if ($archive->isEmpty())
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">No Data Available.</td>
                            </tr>
                        @else
                            @foreach ($archive as $index)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index->date }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index->no_archive }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if (!empty($index->pdf_jpg))
                                            <a onclick="openPdfInNewTab('{{ $index->pdf_jpg }}')"
                                                class="text-indigo-600 hover:text-indigo-900 cursor-pointer">{{ $index->file_name }}</a>
                                        @else
                                            <span class="text-gray-400">No File</span>
                                        @endif
                                    </td>
                                    <form action="{{ route('documents.destroy', $index->idrec) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        @method('POST')
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 flex justify-center">
                                            <button
                                                class="px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150 cursor-pointer"
                                                type="submit">                                                
                                                Delete
                                            </button>
                                        </td>
                                    </form>

                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
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
                files: [],
                isDragging: false,
                fileUploaded: false,

                handleFiles(event) {
                    const files = event.target.files;
                    this.processFiles(files);
                },

                handleDrop(event) {
                    event.preventDefault();
                    const files = event.dataTransfer.files;
                    this.processFiles(files);
                    this.isDragging = false;
                },

                processFiles(files) {
                    if (this.files.length === 0) {
                        for (let file of files) {
                            if (file.type === 'application/pdf' && file.size <= 25 * 1024 *
                                1024) { // Max 25MB
                                this.files.push(file);
                                this.fileUploaded = true;

                                // Update hidden input with file names
                                this.updateFileNames();

                                // Success notification
                                Toastify({
                                    text: "File uploaded successfully!",
                                    duration: 3000,
                                    close: true,
                                    gravity: "top",
                                    position: "right",
                                    style: {
                                        background: "#4CAF50"
                                    }
                                }).showToast();

                                break;
                            } else {
                                // Error notification
                                Toastify({
                                    text: `File ${file.name} invalid or exceeds 25MB.`,
                                    duration: 3000,
                                    close: true,
                                    gravity: "top",
                                    position: "right",
                                    style: {
                                        background: "#FF5733"
                                    }
                                }).showToast();
                            }
                        }
                    } else {
                        // Error notification for multiple files
                        Toastify({
                            text: "Only one file can be uploaded.",
                            duration: 3000,
                            close: true,
                            gravity: "top",
                            position: "right",
                            style: {
                                background: "#FFC107"
                            }
                        }).showToast();
                    }
                },

                removeFile(index) {
                    this.files.splice(index, 1);
                    if (this.files.length === 0) {
                        this.fileUploaded = false;
                    }

                    // Update hidden input with file names
                    this.updateFileNames();

                    // Notification for file deletion
                    Toastify({
                        text: "File deleted.",
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        style: {
                            background: "#607D8B"
                        }
                    }).showToast();
                },

                updateFileNames() {
                    const fileNames = this.files.map(file => file.name).join(',');
                    document.getElementById('file_names_input').value = fileNames;
                }
            }));
        });

        document.addEventListener("DOMContentLoaded", function() {
            let today = new Date();
            let formattedDate = today.toISOString().split('T')[0];
            document.getElementById("date").value = formattedDate;
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

        document.getElementById('no_archive').addEventListener('input', function() {
            const ajuNumber = this.value;

            if (ajuNumber) {
                fetch(`/archive/check-document-number?id_aju=${ajuNumber}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.exists) {

                            Toastify({
                                text: "AJU Number already exists! Please use a different number.",
                                duration: 3000,
                                gravity: "top",
                                position: "right",
                                backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
                                stopOnFocus: true,
                            }).showToast();
                        }
                    })
                    .catch(error => {
                        console.error('Error checking AJU Number:', error);
                    });
            }
        });

        document.getElementById('suggest_no_archive').addEventListener('click', function() {
            const dateInput = document.getElementById('date').value;
            fetch('/archive/suggest-document-number', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        date: dateInput
                    })
                })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('no_archive').value = data.suggested_id_document;
                })
                .catch(error => {
                    console.error('Error suggesting AJU Number:', error);
                });
        });
    </script>
</x-app-layout>
