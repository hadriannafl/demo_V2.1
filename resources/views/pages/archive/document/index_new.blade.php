<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <div class="flex items-center justify-between">
            <h2 class="text-3xl font-semibold">Document Management</h2>
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
                                <div class="font-semibold text-slate-800">Add New Document</div>
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
                            <form id="documentForm" method="POST" action="{{ route('document.store') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="grid md:grid-cols-1 md:gap-6">
                                    <div class="grid md:grid-cols-2 md:gap-6">
                                        <!-- Input Date -->
                                        <div class="relative z-0 w-full mb-5 group">
                                            <input type="date" name="date_modal" id="date_modal" required
                                                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" />
                                            <label for="date_modal"
                                                class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                                Select Date
                                            </label>
                                        </div>
                                        <div class="relative z-0 w-full mb-5 flex items-center space-x-2">
                                            <div class="w-full">
                                                <input name="id_document" id="id_document" autocomplete="off"
                                                    class="block py-2.5 px-3 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                    placeholder=" " required onkeypress="return event.key !== ' '"
                                                    oninput="this.value = this.value.replace(/\s/g, '')" />
                                                <label for="id_document"
                                                    class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                                    Document Number
                                                </label>
                                            </div>
                                            <button type="button" id="suggest_id_document"
                                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 cursor-pointer">
                                                #
                                            </button>
                                        </div>
                                    </div>

                                    <div class="grid md:grid-cols-4 md:gap-6">
                                        <!-- Department -->
                                        <div class="relative z-0 w-full mb-5 group">
                                            <select name="dep" id="dep"
                                                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                required @if (!empty($aju)) disabled @endif>
                                                <option value="" disabled selected>Select Department</option>
                                                @foreach ($deps as $department)
                                                    <option value="{{ $department->id }}"
                                                        @if (isset($aju) && $department->id == $aju->department->pid) selected @endif>
                                                        {{ $department->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <label for="dep"
                                                class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                                Department
                                            </label>
                                        </div>

                                        <!-- Sub Department -->
                                        <div class="relative z-0 w-full mb-5 group">
                                            <select name="sub_dep" id="sub_dep"
                                                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                required>
                                                <option value="" disabled selected>Select Sub Department</option>
                                                @if (isset($aju) && isset($subDeps))
                                                    @foreach ($subDeps as $subDep)
                                                        <option value="{{ $subDep->id }}"
                                                            @if ($aju->id_department == $subDep->id) selected @endif>
                                                            {{ $subDep->name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <label for="sub_dep"
                                                class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                                Sub Department
                                            </label>
                                        </div>

                                        <div class="relative z-0 w-full mb-5 group">
                                            <select name="type_docs_modal" id="type_docs_modal"
                                                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                required>
                                                <option value="" data-code="">Select Document Type</option>
                                                @foreach ($documentTypes as $doc)
                                                    <option value="{{ $doc->name }}" data-code="{{ $doc->code }}">
                                                        {{ $doc->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <label for="type_docs_modal"
                                                class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                                Type Document
                                            </label>
                                        </div>

                                        <div class="relative z-0 w-full mb-5 group">
                                            <select name="user_email" id="user_email"
                                                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                required>
                                                <option value="" disabled {{ !isset($aju) ? 'selected' : '' }}>
                                                    Select User Email
                                                </option>
                                                @if (isset($users))
                                                    @foreach ($users as $user)
                                                        <option value="{{ $user->id }}"
                                                            @if ((isset($aju) && $aju->created_by == $user->id) || (!isset($aju) && Auth::id() == $user->id)) selected @endif>
                                                            {{ $user->name }} ({{ $user->email }})
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <label for="user_email"
                                                class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                                User Email
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2 w-full mb-5">
                                    <div class="relative z-0 w-full group">
                                        <input name="description_modal" id="description_modal" autocomplete="off"
                                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                            placeholder=" " required />
                                        <label for="description_modal"
                                            class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                            Description
                                        </label>
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
                                        @change="handleFiles($event)"
                                        accept="application/pdf, image/jpeg, image/jpg" />
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
                                            <svg class="w-8 h-8 text-[#1976d2] mr-4"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
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
                                <div class="px-5 py-3 border-t border-slate-200 flex justify-end mt-4">
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
                <h2 class="text-lg font-semibold text-gray-900">Document List</h2>
                <div class="flex items-center">
                    <div class="relative">
                        <form method="GET" action="{{ route('index.newDocument') }}">
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
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                No</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Document Type</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Description</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Document Number</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Department</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Sub Department</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                File Name</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Created by</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($archives as $index => $archive)
                            <tr class="hover:bg-gray-100 odd:bg-gray-100 even:bg-white">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $index + $archives->firstItem() }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $archive->doc_type ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $archive->date ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $archive->description ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $archive->no_document ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $archive->subDepartment->parent->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $archive->subDepartment->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <a onclick="openPdfInNewTab('{{ $archive->pdfblob }}')"
                                        class="text-indigo-600 hover:text-indigo-900 cursor-pointer">{{ $archive->file_name ?? '-' }}</a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $archive->createdByUser->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center align-middle">
                                    <a href="#" onclick="openPdfInNewTab('{{ $archive->pdfblob }}')"
                                        class="bg-blue-600 text-white px-3 py-1 rounded-md hover:bg-blue-700 flex items-center justify-center gap-2 transition duration-150">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                                    No archives found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="bg-gray-50 rounded p-4">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <form method="GET" action="{{ route('index.newDocument') }}">
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
        document.addEventListener('alpine:init', () => {
            Alpine.data('modal', () => ({
                modalOpenDetail: false,
                files: [],
                fileUploaded: false,
                isDragging: false,

                loadFiles(archives) {
                    this.files = [];
                    archives.forEach(archive => {
                        if (archive.pdfblob) {
                            this.files.push({
                                name: archive.file_name,
                                type: archive.file_extension === 'pdf' ?
                                    'application/pdf' : 'image/jpeg',
                                url: archive.pdfblob
                            });
                        }
                    });
                },

                handleDrop(event) {
                    event.preventDefault();
                    this.isDragging = false;
                    this.processFiles(event.dataTransfer.files);
                },

                handleFiles(event) {
                    this.processFiles(event.target.files);
                    event.target.value = ''; // Reset input to allow same file re-upload
                },

                processFiles(fileList) {
                    const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg'];
                    const maxSize = 25 * 1024 * 1024; // 25MB

                    Array.from(fileList).forEach(file => {
                        if (allowedTypes.includes(file.type) && file.size <= maxSize) {
                            // For new uploads
                            this.files.push(file);
                            this.fileUploaded = true;

                            Toastify({
                                text: `${file.name} uploaded successfully!`,
                                duration: 3000,
                                gravity: "top",
                                position: "right",
                                style: {
                                    background: "#4CAF50"
                                }
                            }).showToast();
                        } else {
                            Toastify({
                                text: `Invalid file: ${file.name}. Only PDF/JPG under 25MB allowed.`,
                                duration: 3000,
                                gravity: "top",
                                position: "right",
                                style: {
                                    background: "#FF5733"
                                }
                            }).showToast();
                        }
                    });

                    this.updateFileNames();
                },

                removeFile(index) {
                    const removedFile = this.files.splice(index, 1)[0];
                    this.fileUploaded = this.files.length > 0;

                    Toastify({
                        text: `Removed: ${removedFile.name || removedFile.file_name}`,
                        duration: 3000,
                        gravity: "top",
                        position: "right",
                        style: {
                            background: "#607D8B"
                        }
                    }).showToast();

                    this.updateFileNames();
                },

                updateFileNames() {
                    const fileNamesInput = document.getElementById('file_names_input');
                    if (fileNamesInput) {
                        fileNamesInput.value = this.files.map(file => file.name).join(',');
                    }
                },

                // Drag and drop helpers
                ['@dragover.prevent']() {
                    this.isDragging = true;
                },
                ['@dragleave.prevent']() {
                    this.isDragging = false;
                }
            }));
        });

        document.addEventListener('DOMContentLoaded', function() {
            const departmentSelect = document.getElementById('dep');
            const subDepartmentSelect = document.getElementById('sub_dep');

            departmentSelect.addEventListener('change', function() {
                const departmentId = this.value;

                // Clear existing options
                subDepartmentSelect.innerHTML =
                    '<option value="" disabled selected>Select Sub Department</option>';

                if (departmentId) {
                    fetch(`/archive/get-sub-departments/${departmentId}`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(subDep => {
                                const option = document.createElement('option');
                                option.value = subDep.id;
                                option.textContent = subDep.name;
                                subDepartmentSelect.appendChild(option);
                            });
                        })
                        .catch(error => {
                            console.error('Error fetching sub-departments:', error);
                        });
                }
            });
        });

        document.getElementById('id_document').addEventListener('input', function() {
            const docNumber = this.value.trim();
            const docTypeSelect = document.getElementById('type_docs_modal');
            const selectedDocType = docTypeSelect.options[docTypeSelect.selectedIndex].value;

            // Only check if document number is not empty and document type is selected
            if (docNumber && selectedDocType) {
                // Show loading indicator (optional)
                this.classList.add('checking-number');

                fetch(
                        `/archive/check-document-number?id_document=${encodeURIComponent(docNumber)}&doc_type=${encodeURIComponent(selectedDocType)}`
                    )
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.exists) {
                            Toastify({
                                text: "Document number already exists for this document type! Please use a different number.",
                                duration: 3000,
                                gravity: "top",
                                position: "right",
                                backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
                                stopOnFocus: true,
                            }).showToast();

                            // Optionally highlight the field
                            this.classList.add('border-red-500');
                        } else {
                            this.classList.remove('border-red-500');
                        }
                    })
                    .catch(error => {
                        console.error('Error checking document number:', error);
                        Toastify({
                            text: "Error checking document number availability",
                            duration: 3000,
                            gravity: "top",
                            position: "right",
                            backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
                            stopOnFocus: true,
                        }).showToast();
                    })
                    .finally(() => {
                        this.classList.remove('checking-number');
                    });
            }
        });

        document.getElementById('suggest_id_document')?.addEventListener('click', function() {
            // Get date input value and selected document type
            const dateInput = document.getElementById('date_modal').value;
            const docTypeSelect = document.getElementById('type_docs_modal');
            const docType = docTypeSelect.options[docTypeSelect.selectedIndex].value;

            // Disable button during request to prevent multiple clicks
            const suggestButton = this;
            suggestButton.disabled = true;
            suggestButton.innerHTML = 'Generating...';

            // Send request to backend
            fetch('/archive/suggest-document-number', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        date: dateInput,
                        doc_type: docType
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.suggested_id_document) {
                        document.getElementById('id_document').value = data.suggested_id_document;
                    } else {
                        throw new Error('No suggestion received from server');
                    }
                })
                .catch(error => {
                    console.error('Error suggesting Document Number:', error);
                    Toastify({
                        text: "Failed to generate Document number. Please try again or enter manually.",
                        duration: 3000,
                        gravity: "top",
                        position: "right",
                        backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
                        stopOnFocus: true,
                    }).showToast();
                })
                .finally(() => {
                    // Re-enable button regardless of success/failure
                    suggestButton.disabled = false;
                    suggestButton.innerHTML = '#';
                });
        });

        document.addEventListener("DOMContentLoaded", function() {
            let today = new Date();
            let formattedDate = today.toISOString().split('T')[0];
            document.getElementById("date_modal").value = formattedDate;
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
    </script>
</x-app-layout>
