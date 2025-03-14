<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <div class="flex items-center justify-between">
            <h2 class="text-3xl font-semibold">Archive Management</h2>
        </div>

        <div id="containerAccount" class="bg-white shadow-md rounded-lg overflow-hidden mt-8">
            <div class="flex justify-between items-center px-6 py-4 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">Archive List</h2>
                <div class="flex items-center">
                    <form method="GET" action="{{ route('index.upload') }}">
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
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Document Type</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Document Number</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($archives as $index => $archive)
                            <tr class="hover:bg-gray-100 odd:bg-gray-100 even:bg-white">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $archive->department->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $archive->tipe_docs }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $archive->no_docs }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ \Carbon\Carbon::parse($archive->created_at)->format('Y-m-d') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                                        type="button" @click.prevent="openEditModal({{ $archive }})" aria-controls="edit-modal">
                                        Edit
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="bg-gray-50 rounded p-4">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <form method="GET" action="{{ route('index.upload') }}">
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

        <!-- Edit Archive Modal -->
        <div x-data="modal()">
            <div class="fixed inset-0 backdrop-blur bg-opacity-30 z-50 transition-opacity" x-show="modalOpenEdit"
                x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="transition ease-out duration-100"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" aria-hidden="true"
                x-cloak></div>
            <div id="edit-modal"
                class="fixed inset-0 z-50 overflow-hidden flex items-center my-4 justify-center px-4 sm:px-6"
                role="dialog" aria-modal="true" x-show="modalOpenEdit"
                x-transition:enter="transition ease-in-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in-out duration-200"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 translate-y-4" x-cloak>
                <div class="bg-white rounded shadow-lg overflow-auto w-3/4 max-h-full"
                    @click.outside="modalOpenEdit = false" @keydown.escape.window="modalOpenEdit = false">
                    <div class="px-5 py-3 border-b border-slate-200">
                        <div class="flex justify-between items-center">
                            <div class="font-semibold text-slate-800">Edit Document</div>
                            <button type="button" class="text-slate-400 hover:text-slate-500"
                                @click="modalOpenEdit = false">
                                <div class="sr-only">Close</div>
                                <svg class="w-4 h-4 fill-current">
                                    <path
                                        d="M7.95 6.536l4.242-4.243a1 1 0 111.415 1.414L9.364 7.95l4.243 4.242a1 1 0 11-1.415 1.415L7.95 9.364l-4.243 4.243a1 1 0 01-1.414-1.415L6.536 7.95 2.293 3.707a1 1 0 011.414-1.414L7.95 6.536z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="modal-content text-xs px-5 py-4">
                        <form method="POST" :action="editFormAction" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="grid md:grid-cols-2 md:gap-6">
                                <div class="relative z-0 w-full mb-5 group">
                                    <input type="date" name="date" id="edit-date" autocomplete="off"
                                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                        required x-model="archive.date" />
                                    <label for="edit-date"
                                        class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                        Select Date
                                    </label>
                                </div>
                                <div class="relative z-0 w-full mb-5 group">
                                    <input name="id_docs" id="edit-id_docs" autocomplete="off"
                                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                        required x-model="archive.id_docs" readonly/>
                                    <label for="edit-id_docs"
                                        class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                        Document Number
                                    </label>
                                </div>
                            </div>
                            <div class="grid md:grid-cols-3 md:gap-6 mb-5">
                                <div class="relative z-0 w-full group">
                                    <select name="dep" id="edit-dep"
                                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                        required x-model="archive.dep_id">
                                        <option value="" disabled selected>Select Department</option>
                                        @foreach ($deps as $department)
                                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="edit-dep"
                                        class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Department</label>
                                </div>
                                <div class="relative z-0 w-full group">
                                    <select name="sub_dep" id="edit-sub_dep"
                                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                        required x-model="archive.sub_dep_id">
                                        <option value="" disabled selected>Select Sub Department</option>
                                        @foreach ($subDeps as $subs)
                                            <option value="{{ $subs->id }}">{{ $subs->name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="edit-sub_dep"
                                        class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Sub
                                        Department</label>
                                </div>
                                <div class="relative z-0 w-full mb-5 group">
                                    <select name="type_docs_modal" id="edit-type_docs_modal"
                                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                        required x-model="archive.type_docs">
                                        <option value="" data-code="">Select Document Type</option>
                                        <option value="Invoice" data-code="INV">Invoice</option>
                                        <option value="Purchase Order" data-code="PO">Purchase Order</option>
                                        <option value="Delivery Order" data-code="DO">Delivery Order</option>
                                        <option value="Contract" data-code="CTR">Contract</option>
                                        <option value="Proposal" data-code="PRP">Proposal</option>
                                        <option value="Report" data-code="RPT">Report</option>
                                        <option value="Memo" data-code="MMO">Memo</option>
                                        <option value="Agreement" data-code="AGR">Agreement</option>
                                        <option value="Receipt" data-code="RCT">Receipt</option>
                                        <option value="Manual Guide" data-code="MGD">Manual Guide</option>
                                        <option value="Policy Document" data-code="PLD">Policy Document</option>
                                        <option value="Technical Specification" data-code="TSP">Technical
                                            Specification</option>
                                        <option value="Meeting Minutes" data-code="MMT">Meeting Minutes</option>
                                        <option value="Certification" data-code="CRT">Certification</option>
                                        <option value="Legal Document" data-code="LGD">Legal Document</option>
                                    </select>
                                    <label for="edit-type_docs_modal"
                                        class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                        Type Document
                                    </label>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2 w-full mb-5">
                                <div class="relative z-0 w-full group">
                                    <input name="description" id="edit-description" autocomplete="off"
                                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                        placeholder=" " required x-model="archive.description" />
                                    <label for="edit-description"
                                        class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                        Description
                                    </label>
                                </div>
                            </div>
                            <input type="hidden" name="file_names" id="edit-file_names_input" x-model="archive.file_names">
                            <!-- Dropzone for file uploads -->
                            <div class="relative z-0 w-full mb-5 group border-2 border-gray-300 border-dashed rounded-lg p-6"
                                id="edit-dropzone" @dragover.prevent @dragenter.prevent @dragleave.prevent
                                @drop.prevent="handleDrop($event)" x-bind:class="{ 'border-blue-500': isDragging }"
                                x-show="!fileUploaded">
                                <input type="file" name="files[]" id="edit-files"
                                    class="absolute inset-0 w-full h-full opacity-0 z-50" multiple
                                    @change="handleFiles($event)" />
                                <div class="text-center">
                                    <img class="mx-auto h-12 w-12"
                                        src="https://www.svgrepo.com/show/357902/image-upload.svg" alt="">
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">
                                        <label for="edit-files" class="relative cursor-pointer">
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
                            <div id="edit-file-names" class="mt-4 text-sm text-gray-700">
                                <template x-for="(file, index) in files" :key="index">
                                    <div
                                        class="relative w-24 h-24 flex flex-col items-center justify-center rounded-lg bg-[#e3f2fd] p-4 border border-[#90caf9]">
                                        <button class="absolute top-1 right-1 text-[#d32f2f] hover:text-red-700"
                                            @click="removeFile(index)">
                                            <svg width="14" height="14" viewBox="0 0 10 10"
                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M0.279337 0.279338C0.651787 -0.0931121 1.25565 -0.0931121 1.6281 0.279338L9.72066 8.3719C10.0931 8.74435 10.0931 9.34821 9.72066 9.72066C9.34821 10.0931 8.74435 10.0931 8.3719 9.72066L0.279337 1.6281C-0.0931125 1.25565 -0.0931125 0.651788 0.279337 0.279338Z"
                                                    fill="currentColor" />
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M0.279337 9.72066C-0.0931125 9.34821 -0.0931125 8.74435 0.279337 8.3719L8.3719 0.279338C8.74435 -0.0931127 9.34821 -0.0931123 9.72066 0.279338C10.0931 0.651787 10.0931 1.25565 9.72066 1.6281L1.6281 9.72066C1.25565 10.0931 0.651787 10.0931 0.279337 9.72066Z"
                                                    fill="currentColor" />
                                            </svg>
                                        </button>
                                        <svg class="w-8 h-8 text-[#1976d2] mt-2"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 7a2 2 0 012-2h4l2 2h6a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" />
                                        </svg>
                                        <span class="truncate text-xs font-medium text-[#07074D] text-center mt-2">
                                            <span x-text="file.name"></span>
                                        </span>
                                    </div>
                                </template>
                            </div>
                            <div class="px-5 py-3 border-t border-slate-200 flex justify-between mt-4">
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">Update</button>
                            </div>
                        </form>
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
                        backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
                        stopOnFocus: true,
                    }).showToast();
                });
            </script>
        @endif
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('modal', () => ({
                modalOpenEdit: false,
                files: [],
                isDragging: false,
                fileUploaded: false,
                archive: {
                    id: null,
                    date: '',
                    id_docs: '',
                    dep_id: '',
                    sub_dep_id: '',
                    type_docs: '',
                    description: '',
                    file_names: ''
                },

                openEditModal(archive) {
                    this.modalOpenEdit = true;
                    this.archive = {
                        id: archive.id,
                        date: archive.date,
                        id_docs: archive.no_docs,
                        dep_id: archive.dep_id,
                        sub_dep_id: archive.sub_dep_id,
                        type_docs: archive.tipe_docs,
                        description: archive.description,
                        file_names: archive.file_names
                    };
                },

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
                            if (file.type === 'application/pdf' && file.size <= 25 * 1024 * 1024) {
                                this.files.push(file);
                                this.fileUploaded = true;
                                this.updateFileNames();
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
                    this.updateFileNames();
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
                    document.getElementById('edit-file_names_input').value = fileNames;
                }
            }));
        });
    </script>
</x-app-layout>
