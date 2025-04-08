<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <div class="flex items-center justify-between">
            <h2 class="text-3xl font-semibold">AJU Document Management</h2>
            <div x-data="modal()">
                <button
                    class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150"
                    type="button" @click.prevent="modalOpenDetail = true;" aria-controls="feedback-modal1">
                    Add New Document
                </button>
                <!-- Modal backdrop -->
                <div class="fixed inset-0 backdrop-blur bg-opacity-30 z-50 transition-opacity" x-show="modalOpenDetail"
                    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-out duration-100"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" aria-hidden="true"
                    x-cloak></div>
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
                        @click.outside="modalOpenDetail = false" @keydown.escape.window="modalOpenDetail = false">
                        <!-- Modal header -->
                        <div class="px-5 py-3 border-b border-slate-200" id="modalAddLpjDetail">
                            <div class="flex justify-between items-center">
                                <div class="font-semibold text-slate-800">New Document Form</div>
                                <button type="button" class="text-slate-400 hover:text-slate-500"
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

                            <form method="POST" action="{{ route('document.store') }}" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id_aju" id="id_aju" value="{{ $ajuDocs->id_aju }}">
                                <div class="grid md:grid-cols-2 md:gap-6">
                                    <!-- Input Date -->
                                    <div class="relative z-0 w-full mb-5 group">
                                        <input type="date" name="date" id="date" autocomplete="off"
                                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                            required />
                                        <label for="date"
                                            class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                            Select Date
                                        </label>
                                    </div>

                                    <!-- Input AJU Number + Button -->
                                    <div class="relative z-0 w-full mb-5 flex items-center space-x-2">
                                        <div class="w-full">
                                            <input name="no_archive" id="no_archive" autocomplete="off"
                                                class="block py-2.5 px-3 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                placeholder=" " required />
                                            <label for="no_archive"
                                                class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                                Document Number
                                            </label>
                                        </div>
                                        <button type="button" id="suggest_no_archive"
                                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                            Suggest
                                        </button>
                                    </div>
                                </div>
                                <input type="hidden" name="file_names" id="file_names_input" value="">
                                <!-- Dropzone for file uploads -->
                                <div class="relative z-0 w-full mb-5 group border-2 border-gray-300 border-dashed rounded-lg p-6"
                                    id="dropzone" @dragover.prevent @dragenter.prevent @dragleave.prevent
                                    @drop.prevent="handleDrop($event)" x-bind:class="{ 'border-blue-500': isDragging }"
                                    x-show="!fileUploaded">
                                    <input type="file" name="files[]" id="files"
                                        class="absolute inset-0 w-full h-full opacity-0 z-50" multiple
                                        @change="handleFiles($event)" />
                                    <div class="text-center">
                                        <img class="mx-auto h-12 w-12"
                                            src="https://www.svgrepo.com/show/357902/image-upload.svg" alt="">
                                        <h3 class="mt-2 text-sm font-medium text-gray-900">
                                            <label for="files" class="relative cursor-pointer">
                                                <span>Drag and drop</span>
                                                <span class="text-indigo-600"> or browse</span>
                                                <span>to upload</span>
                                            </label>
                                        </h3>
                                        <p class="mt-1 text-xs text-gray-500">
                                            PDF up to 25MB
                                        </p>
                                    </div>
                                </div>
                                <!-- Display selected file names -->
                                <div id="file-names" class="mt-4 text-sm text-gray-700">
                                    <template x-for="(file, index) in files" :key="index">
                                        <div
                                            class="relative w-full flex items-center justify-between rounded-lg bg-[#e3f2fd] p-4 border border-[#90caf9]">

                                            <!-- Icon Folder -->
                                            <svg class="w-8 h-8 text-[#1976d2] mr-4" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 7a2 2 0 012-2h4l2 2h6a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" />
                                            </svg>

                                            <!-- Nama File -->
                                            <span class="truncate text-sm font-medium text-[#07074D] flex-1">
                                                <span x-text="file.name"></span>
                                            </span>

                                            <!-- Tombol Hapus -->
                                            <button class="text-[#d32f2f] hover:text-red-700 ml-4"
                                                @click="removeFile(index)">
                                                <svg width="16" height="16" viewBox="0 0 10 10"
                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M0.279337 0.279338C0.651787 -0.0931121 1.25565 -0.0931121 1.6281 0.279338L9.72066 8.3719C10.0931 8.74435 10.0931 9.34821 9.72066 9.72066C9.34821 10.0931 8.74435 10.0931 8.3719 9.72066L0.279337 1.6281C-0.0931125 1.25565 -0.0931125 0.651788 0.279337 0.279338Z"
                                                        fill="currentColor" />
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M0.279337 9.72066C-0.0931125 9.34821 -0.0931125 8.74435 0.279337 8.3719L8.3719 0.279338C8.74435 -0.0931127 9.34821 -0.0931123 9.72066 0.279338C10.0931 0.651787 10.0931 1.25565 9.72066 1.6281L1.6281 9.72066C1.25565 10.0931 0.651787 10.0931 0.279337 9.72066Z"
                                                        fill="currentColor" />
                                                </svg>
                                            </button>
                                        </div>
                                    </template>
                                </div>


                                <!-- Modal footer -->
                                <div class="px-5 py-3 border-t border-slate-200 flex justify-between mt-4">
                                    <button type="submit"
                                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">Upload</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
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
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @if ($archive->isEmpty())
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">No Data Available.</td>
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
