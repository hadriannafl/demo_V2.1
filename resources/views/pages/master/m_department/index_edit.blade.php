<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <div class="flex items-center justify-between">
            <h2 class="text-3xl font-semibold">Department Management</h2>

        </div>

        <div id="containerAccount" class="bg-white shadow-md rounded-lg overflow-hidden mt-8">
            <div class="flex justify-between items-center px-6 py-4 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">Department List</h2>
                <div class="flex items-center">
                    <form method="GET" action="{{ route('index.department') }}">
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                            class="border border-gray-300 rounded px-4 py-2 w-48 mr-2"
                            placeholder="Search Departments...">
                    </form>
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
                                Main Department</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Sub Department</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($subDepartments as $index => $subDept)
                            <tr class="hover:bg-gray-100 odd:bg-gray-100 even:bg-white">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $subDepartments->firstItem() + $index }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $mainDepartments->firstWhere('id', $subDept->pid)->name ?? '' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $subDept->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $subDept->status === 'Active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $subDept->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div x-data="editModal({{ json_encode($subDept) }}, {{ json_encode($mainDepartments) }})">
                                        <button
                                            class="inline-flex items-center px-3 py-1 bg-yellow-400 text-white text-xs font-semibold rounded hover:bg-yellow-500 transition duration-150"
                                            type="button" @click.prevent="openModal()">
                                            Edit
                                        </button>

                                        <!-- Modal Edit -->
                                        <div class="fixed inset-0 z-50 overflow-y-auto" x-show="isOpen" x-cloak>
                                            <div
                                                class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                                <!-- Background overlay -->
                                                <div class="fixed inset-0 transition-opacity" aria-hidden="true"
                                                    @click="closeModal()">
                                                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                                                </div>

                                                <!-- Modal content -->
                                                <div class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-0"
                                                    x-show="isOpen" x-transition:enter="ease-out duration-300"
                                                    x-transition:enter-start="opacity-0"
                                                    x-transition:enter-end="opacity-100"
                                                    x-transition:leave="ease-in duration-200"
                                                    x-transition:leave-start="opacity-100"
                                                    x-transition:leave-end="opacity-0"
                                                    style="background-color: rgba(255, 255, 255, 0.3); backdrop-filter: blur(5px); -webkit-backdrop-filter: blur(5px); display: none;"
                                                    x-cloak @click.away="closeModal()">

                                                    <div class="inline-block align-middle bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full"
                                                        x-transition:enter="ease-out duration-300"
                                                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                                        x-transition:leave="ease-in duration-200"
                                                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                                                        <!-- Modal header with close button -->
                                                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 relative">
                                                            <button @click="closeModal()"
                                                                class="absolute top-4 right-4 text-gray-400 hover:text-gray-500 focus:outline-none">
                                                                <span class="sr-only">Close</span>
                                                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                                                    stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                            </button>

                                                            <h3
                                                                class="font-semibold text-slate-800 border-b border-slate-200 pb-2 mb-4">
                                                                Edit Department/Sub Department
                                                            </h3>

                                                            <form @submit.prevent="submitForm">
                                                                <input type="hidden" name="id"
                                                                    x-model="formData.id">

                                                                <!-- Edit Type Dropdown -->
                                                                <div class="mb-4 mt-4">
                                                                    <label
                                                                        class="block text-sm font-medium text-gray-700 mb-2">Edit
                                                                        Type</label>
                                                                    <select x-model="editType"
                                                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                                        <option value="department">Department</option>
                                                                        <option value="subdepartment">Sub Department
                                                                        </option>
                                                                    </select>
                                                                </div>

                                                                <!-- Department Edit Form -->
                                                                <div x-show="editType === 'department'"
                                                                    class="space-y-4">
                                                                    <div>
                                                                        <label for="departmentName"
                                                                            class="block text-sm font-medium text-gray-700">Department
                                                                            Name</label>
                                                                        <input type="text" id="departmentName"
                                                                            name="departmentName"
                                                                            x-model="formData.departmentName"
                                                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                                    </div>
                                                                </div>

                                                                <!-- Sub Department Edit Form -->
                                                                <div x-show="editType === 'subdepartment'"
                                                                    class="space-y-4">
                                                                    <div>
                                                                        <label for="parentDepartment"
                                                                            class="block text-sm font-medium text-gray-700">Parent
                                                                            Department</label>
                                                                        <select id="parentDepartment"
                                                                            name="parentDepartment"
                                                                            x-model="formData.parentId"
                                                                            @change="updateDepartmentName()"
                                                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                                            <option value="">Select Department
                                                                            </option>
                                                                            <template x-for="dept in mainDepartments"
                                                                                :key="dept.id">
                                                                                <option :value="dept.id"
                                                                                    :selected="dept.id == formData.parentId"
                                                                                    x-text="dept.name"></option>
                                                                            </template>
                                                                        </select>
                                                                    </div>

                                                                    <div>
                                                                        <label for="subDepartmentName"
                                                                            class="block text-sm font-medium text-gray-700">Sub
                                                                            Department Name</label>
                                                                        <input type="text" id="subDepartmentName"
                                                                            name="subDepartmentName"
                                                                            x-model="formData.subDepartmentName"
                                                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>

                                                        <!-- Modal footer -->
                                                        <div
                                                            class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                            <button type="button" @click="submitForm()"
                                                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                                                                Save Changes
                                                            </button>
                                                            <button type="button" @click="closeModal()"
                                                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                                                Cancel Edit
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="bg-gray-50 rounded p-4">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <form method="GET" action="{{ route('index.department') }}">
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
                    {{ $subDepartments->links() }}
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('editModal', (subDept, mainDepartments) => ({
                isOpen: false,
                editType: subDept.isDepartment ? 'department' : 'subdepartment',
                formData: {
                    id: subDept.id,
                    departmentName: subDept.departmentName || subDept.name || '',
                    subDepartmentName: subDept.name || '',
                    parentId: subDept.pid || null,
                    status: subDept.status || 'Active'
                },
                mainDepartments: mainDepartments,
                selectedDepartment: null,

                init() {
                    if (this.formData.parentId) {
                        this.selectedDepartment = this.mainDepartments.find(
                            dept => dept.id == this.formData.parentId
                        );
                        if (this.selectedDepartment) {
                            this.formData.departmentName = this.selectedDepartment.name;
                        }
                    }

                    if (this.editType === 'department') {
                        this.formData.departmentName = subDept.name;
                    }
                },

                openModal() {
                    this.isOpen = true;
                },

                closeModal() {
                    this.isOpen = false;
                },

                async submitForm() {
                    try {
                        let url, data;

                        if (this.editType === 'department') {
                            url = '/master/api/departments/' + this.formData.id;
                            data = {
                                name: this.formData.departmentName,
                                status: this.formData.status
                            };
                        } else {
                            if (!this.formData.parentId) {
                                Toastify({
                                    text: "Please select a parent department",
                                    duration: 3000,
                                    close: true,
                                    gravity: "top",
                                    position: "right",
                                    backgroundColor: "#ef4444",
                                }).showToast();
                                return;
                            }
                            if (!this.formData.subDepartmentName.trim()) {
                                Toastify({
                                    text: "Please enter a sub-department name",
                                    duration: 3000,
                                    close: true,
                                    gravity: "top",
                                    position: "right",
                                    backgroundColor: "#ef4444",
                                }).showToast();
                                return;
                            }

                            url = '/master/api/sub-departments/' + this.formData.id;
                            data = {
                                name: this.formData.subDepartmentName,
                                pid: this.formData.parentId,
                                status: this.formData.status
                            };
                        }

                        const response = await fetch(url, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify(data)
                        });

                        const responseData = await response.json();

                        if (response.ok) {
                            // Tampilkan toast sukses
                            Toastify({
                                text: responseData.message || "Data updated successfully!",
                                duration: 3000,
                                close: true,
                                gravity: "top",
                                position: "right",
                                backgroundColor: "#10b981",
                            }).showToast();

                            // Tutup modal dan refresh data setelah 1 detik
                            setTimeout(() => {
                                this.closeModal();
                                window.location.reload();
                            }, 1000);
                        } else {
                            // Tampilkan toast error
                            Toastify({
                                text: responseData.message || "Error updating data",
                                duration: 3000,
                                close: true,
                                gravity: "top",
                                position: "right",
                                backgroundColor: "#ef4444",
                            }).showToast();
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        Toastify({
                            text: "An unexpected error occurred",
                            duration: 3000,
                            close: true,
                            gravity: "top",
                            position: "right",
                            backgroundColor: "#ef4444",
                        }).showToast();
                    }
                },

                updateDepartmentName() {
                    if (this.formData.parentId) {
                        this.selectedDepartment = this.mainDepartments.find(
                            dept => dept.id == this.formData.parentId
                        );
                        if (this.selectedDepartment) {
                            this.formData.departmentName = this.selectedDepartment.name;
                        }
                    } else {
                        this.formData.departmentName = '';
                        this.selectedDepartment = null;
                    }
                }
            }));
        });
    </script>
</x-app-layout>
