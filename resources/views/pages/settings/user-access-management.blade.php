<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-5xl mx-auto">

        <div class="flex items-center justify-between mb-4">
            <h2 class="text-3xl font-semibold">Menu Access Management</h2>
            <div class="flex gap-x-4">
                <!-- Select User -->
                <label for="userSelect" class="text-lg font-medium">User Name :</label>
                <select id="userSelect" class="border border-gray-300 rounded-md text-lg px-2 py-0.5 w-64">
                    <option value="">Select User</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden mt-8">
            <div class="flex items-center justify-between px-6 py-4 bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-900">Permissions</h3>
                <div class="flex gap-2"> <!-- Wrapper flex untuk tombol -->
                    <button id="updateAccess" class="bg-blue-500 text-white px-4 py-2 rounded-md">Update Access</button>
                    <button id="cancelAccess" class="bg-red-500 text-white px-4 py-2 rounded-md hidden">Cancel</button>
                </div>
            </div>


            <div id="permissionsContainer" class="px-6 py-4 hidden">
                <div class="flex items-center mb-2">
                    <label for="mainMenuSelect" class="text-xs font-medium text-gray-500 mr-2">Main Menu:</label>
                    <select id="mainMenuSelect" class="border border-gray-300 rounded-md text-xs px-2 py-1 w-64">
                        <option value="">Select All</option>
                        @foreach ($sidebarItems->whereNull('parent_id')->whereNull('route') as $menu)
                            <option value="{{ $menu->id }}">{{ $menu->name }}</option>
                        @endforeach
                    </select>
                </div>
                <table class="min-w-full max-w-2xl border border-gray-300 divide-y divide-gray-300 mt-4">
                    <thead class="bg-gray-50">
                        <tr>
                            <th
                                class="border border-gray-300 px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase w-1/12">
                                Access</th>
                            <th
                                class="border border-gray-300 px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase w-1/12">
                                Main Menu</th>
                            <th
                                class="border border-gray-300 px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase w-1/12">
                                Sub Menu</th>
                            <th
                                class="border border-gray-300 px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase w-1/12">
                                List Menu</th>
                        </tr>
                    </thead>
                    <tbody id="permissionsTableBody">
                        @foreach ($sidebarItems as $item)
                            <tr class="odd:bg-gray-100 menu-item" data-main-menu="{{ $item->parent_id ?? $item->id }}">
                                <td class="border border-gray-300 px-2 py-2 text-sm text-gray-900 text-center w-1/12">
                                    <input type="checkbox" class="form-checkbox" value="{{ $item->id }}">
                                </td>
                                <td class="border border-gray-300 px-2 py-2 text-sm text-gray-900 w-1/12">
                                    @if (is_null($item->route) && is_null($item->parent_id))
                                        {{ $item->name }}
                                    @endif
                                </td>
                                <td class="border border-gray-300 px-2 py-2 text-sm text-gray-900 w-1/12">
                                    @if (is_null($item->route) && !is_null($item->parent_id))
                                        {{ $item->name }}
                                    @endif
                                </td>
                                <td class="border border-gray-300 px-2 py-2 text-sm text-gray-900 w-1/12">
                                    @if (!is_null($item->route) && !is_null($item->parent_id))
                                        {{ $item->name }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        const userSelect = document.getElementById('userSelect');
        const updateAccessButton = document.getElementById('updateAccess');
        const cancelAccessButton = document.getElementById('cancelAccess');
        const permissionsContainer = document.getElementById('permissionsContainer');

        userSelect.addEventListener('change', function() {
            const userId = this.value;

            if (userId) {
                // Lock the combobox
                userSelect.disabled = true;
                cancelAccessButton.classList.remove('hidden');

                // Fetch user permissions via AJAX
                fetch(`/user-access/${userId}/permissions`)
                    .then(response => response.json())
                    .then(data => {
                        permissionsContainer.classList.remove('hidden');

                        // Check the appropriate permissions
                        document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                            checkbox.checked = data.permissions.includes(parseInt(checkbox.value));
                        });
                    });
            } else {
                // Unlock the combobox
                userSelect.disabled = false;
                cancelAccessButton.classList.add('hidden');
                permissionsContainer.classList.add('hidden');
            }
        });

        updateAccessButton.addEventListener('click', function() {
            const userId = userSelect.value;
            const checkboxes = document.querySelectorAll('input[type="checkbox"]');
            const selectedPermissions = [];

            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    selectedPermissions.push(checkbox.value);
                }
            });

            fetch(`/user-access/${userId}/update`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        permissions: selectedPermissions
                    })
                })
                .then(response => response.json())
                .then(data => {

                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                    Toastify({
                        text: data.message,
                        duration: 3000,
                        close: true,
                        gravity: "bottom",
                        position: "left",
                        backgroundColor: "#4CAF50",
                        stopOnFocus: true,
                    }).showToast();


                })
                .catch(error => {
                    console.error('Error:', error);
                    Toastify({
                        text: "Gagal memperbarui akses!",
                        duration: 3000,
                        close: true,
                        gravity: "bottom",
                        position: "left",
                        backgroundColor: "#FF5733",
                    }).showToast();
                });
        });

        cancelAccessButton.addEventListener('click', function() {
            // Unlock the combobox
            userSelect.disabled = false;
            cancelAccessButton.classList.add('hidden');
            permissionsContainer.classList.add('hidden');
            userSelect.value = '';

            // Uncheck all checkboxes
            document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                checkbox.checked = false;
            });
        });

        document.getElementById('mainMenuSelect').addEventListener('change', function() {
            const selectedMainMenu = this.value.toLowerCase(); // Ambil nilai dan ubah ke lowercase

            document.querySelectorAll('.menu-item').forEach(row => {
                const menuGroup = row.getAttribute('data-main-menu');
                const menuCategory = row.getAttribute('data-category') ? row.getAttribute('data-category')
                    .toLowerCase() : '';

                if (selectedMainMenu === "" || menuGroup === selectedMainMenu || menuCategory.includes(
                        selectedMainMenu)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        });

        document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const menuId = this.value;
                const isChecked = this.checked;

                // If the checkbox is a main menu
                if (this.closest('tr').querySelector('td:nth-child(2)').textContent.trim() !== '') {
                    // Main Menu: Check/Uncheck all sub and list menus
                    document.querySelectorAll(
                            `.menu-item[data-main-menu='${menuId}'] input[type="checkbox"]`)
                        .forEach(childCheckbox => {
                            childCheckbox.checked = isChecked;
                        });
                } else {
                    // If the checkbox is a sub menu or list menu
                    const mainMenuId = this.closest('.menu-item').getAttribute('data-main-menu');

                    // Update the main menu checkbox based on sub-menu checks
                    if (mainMenuId) {
                        const mainMenuCheckbox = document.querySelector(
                            `input[type="checkbox"][value='${mainMenuId}']`);
                        if (mainMenuCheckbox) {
                            const allSubMenusUnchecked = document.querySelectorAll(
                                `.menu-item[data-main-menu='${mainMenuId}'] input[type="checkbox"]:checked`
                            ).length === 0;

                            mainMenuCheckbox.checked = !allSubMenusUnchecked;
                        }
                    }

                    // If it's a sub menu, check/uncheck all list menus
                    if (this.closest('tr').querySelector('td:nth-child(3)').textContent.trim() !== '') {
                        const parentMenuId = this.closest('.menu-item').getAttribute('data-main-menu');
                        document.querySelectorAll(
                                `.menu-item[data-main-menu='${parentMenuId}'] input[type="checkbox"]`)
                            .forEach(listCheckbox => {
                                listCheckbox.checked = isChecked; // Check/Uncheck all list menus
                            });
                    }

                    // If it's a list menu, check if all list menus are unchecked
                    if (this.closest('tr').querySelector('td:nth-child(4)').textContent.trim() !== '') {
                        const parentMenuId = this.closest('.menu-item').getAttribute('data-main-menu');
                        const allListMenusUnchecked = document.querySelectorAll(
                            `.menu-item[data-main-menu='${parentMenuId}'] input[type="checkbox"]:checked`
                        ).length === 0;

                        // If all list menus are unchecked, uncheck the sub menu
                        if (allListMenusUnchecked) {
                            const subMenus = document.querySelectorAll(
                                `.menu-item[data-main-menu='${parentMenuId}']`);
                            subMenus.forEach(subMenu => {
                                const subMenuCheckbox = subMenu.querySelector(
                                    'input[type="checkbox"]');
                                if (subMenuCheckbox) {
                                    subMenuCheckbox.checked = false; // Uncheck sub menus
                                }
                            });

                            // Check if all sub menus are unchecked to uncheck the main menu
                            const allSubMenusUnchecked = document.querySelectorAll(
                                `.menu-item[data-main-menu='${mainMenuId}'] input[type="checkbox"]:checked`
                            ).length === 0;

                            const mainMenuCheckbox = document.querySelector(
                                `input[type="checkbox"][value='${mainMenuId}']`);
                            if (mainMenuCheckbox) {
                                mainMenuCheckbox.checked = !
                                    allSubMenusUnchecked; // Uncheck main menu if all sub menus are unchecked
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
