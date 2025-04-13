<?php

use App\Http\Controllers\Archive\AjuController;
use App\Http\Controllers\Archive\DocumentController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Master\Department\DepartmentController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Warehouse\InventoryController;

// Route::redirect('/', 'login');

Route::middleware(['guest'])->group(function () {
    Route::get('/', [SessionController::class, 'index'])->name('login');
    Route::post('/', [SessionController::class, 'login']);
});
Route::get('/home', function () {
    return redirect('/dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/account/getData', [UserController::class, 'accountGetData'])->name('account.getData');
    Route::get('/users/getList', [UserController::class, 'userGetData'])->name('users.getList');
    Route::get('/users/getMainMenus', [UserController::class, 'usergetMainMenus'])->name('menus.getMainMenus');

    Route::get('/menus/getData', [UserController::class, 'getData'])->name('menus.getData');
    Route::get('/menus/getUserAccess', [UserController::class, 'getUserAccess'])->name('menus.getUserAccess');

    Route::get('/settings/users-management', [UserController::class, 'index'])->name('users-management');

    Route::get('/settings/users-management/new', [UserController::class, 'indexNew'])->name('users-newManagement');
    Route::post('/check-email', [UserController::class, 'checkEmail'])->name('check.email');
    Route::post('/settings/users-management/users', [UserController::class, 'store'])->name('users.store');

    Route::get('/settings/users-management/edit', [UserController::class, 'indexEdit'])->name('users-editManagement');
    Route::put('/settings/users/{user}', [UserController::class, 'updateUsers'])->name('users.update');

    Route::get('/settings/users-management/delete', [UserController::class, 'indexDelete'])->name('users-deleteManagement');
    Route::post('/settings/users/{user}/deactivate', [UserController::class, 'deactivateUser'])->name('users.deactivate');


    Route::get('/settings/users-access-management', [UserController::class, 'indexUserAccessManagement'])->name('users-access-management');
    Route::get('/user-access/edit', [UserController::class, 'indexUserAccessManagement'])->name('user-access.edit');
    Route::get('/user-access/{userId}/permissions', [UserController::class, 'getUserPermissions']);
    Route::post('/user-access/{userId}/update', [UserController::class, 'update']);

    Route::prefix('warehouse')->group(function () {
        Route::get('/inventory', [InventoryController::class, 'index'])->name('index.inventory');

        Route::get('/inventory/new', [InventoryController::class, 'indexNew'])->name('indexNew.inventory');
        Route::get('/inventory/add', [InventoryController::class, 'create'])->name('inventory.create');
        Route::post('/inventory/store', [InventoryController::class, 'store'])->name('inventory.store');

        Route::get('/inventory/edit', [InventoryController::class, 'indexEdit'])->name('indexEdit.inventory');

        Route::get('/inventory/delete', [InventoryController::class, 'indexDelete'])->name('indexDelete.inventory');
        Route::delete('/inventory/delete/{id}', [InventoryController::class, 'destroy'])->name('inventory.destroy');
        Route::put('/inventory/update/{id}', [InventoryController::class, 'update'])->name('inventory.update');
    });

    Route::prefix('archive')->group(function () {

        Route::get('/aju', [AjuController::class, 'index'])->name('index.aju');

        Route::get('/aju/newAju', [AjuController::class, 'indexNewAju'])->name('index.newaju');
        Route::get('/aju/newAju/formNew', [AjuController::class, 'formNew'])->name('index.formNew');
        Route::get('/aju/newAju/formNewGetData', [AjuController::class, 'formNewGetData'])->name('index.formNew.GetData');
        Route::get('/get-sub-departments/{departmentId}', [AjuController::class, 'getSubDepartments']);
        Route::get('/check-aju-number', [AjuController::class, 'checkAjuNumber']);
        Route::post('/suggest-aju-number', [AjuController::class, 'suggestAjuNumber']);
        Route::post('/aju/new/store', [AjuController::class, 'store'])->name('aju.store');
        Route::post('/aju/new/storeModal', [AjuController::class, 'storeModal'])->name('aju.storeModal');
        Route::post('/aju/new/storeModalArchive', [AjuController::class, 'storeModalArchive'])->name('aju.storeModalArchive');
        Route::delete('/aju-detail/{id}', [AjuController::class, 'destroy'])->name('aju-detail.destroy');

        Route::get('/aju/editAju', [AjuController::class, 'indexEditAju'])->name('index.editaju');
        Route::put('/aju/update/{idrec}', [AjuController::class, 'update'])->name('indexAju.update');
        Route::get('/aju/update/formNewGetData', [AjuController::class, 'formUpdateGetData'])->name('index.formUpdate.GetData');
        Route::get('/aju/archive/search', [AjuController::class, 'searchArchives'])->name('aju.archive.search');
        Route::post('/aju/update/storeModalArchive', [AjuController::class, 'storeModalArchiveUpdate'])->name('aju.storeModalArchive.update');

        Route::get('/aju/deleteAju', [AjuController::class, 'indexDeleteAju'])->name('index.deleteaju');
        Route::delete('/aju/delete/{id}', [AjuController::class, 'softDelete'])->name('aju.delete');



        Route::get('/document', [DocumentController::class, 'index'])->name('index.document');

        Route::get('/document/newDocument', [DocumentController::class, 'indexNewDocument'])->name('index.newDocument');
        Route::get('/document/indexForm/{id_aju}', [DocumentController::class, 'indexForm'])->name('index.form');
        Route::get('/check-document-number', [DocumentController::class, 'checkDocumentNumber']);
        Route::post('/suggest-document-number', [DocumentController::class, 'suggestDocumentNumber']);
        Route::post('/document/new/store', [DocumentController::class, 'store'])->name('document.store');

        Route::get('/document/editDocument', [DocumentController::class, 'indexEditDocument'])->name('index.editDocument');
        Route::get('/document/indexForm/edit/{id_aju}', [DocumentController::class, 'indexFormEdit'])->name('index.formEdit');
        Route::put('document/update/{id}', [DocumentController::class, 'update'])->name('index.update');

        Route::get('/document/deleteDocument', [DocumentController::class, 'indexDeleteDocument'])->name('index.DeleteDocument');
        Route::put('/document/indexForm/delete/{id_aju}', [DocumentController::class, 'indexFormDelete'])->name('index.formDelete');
    });

    Route::prefix('master')->group(function () {
        Route::get('/department', [DepartmentController::class, 'index'])->name('index.department');
        Route::post('/department/store', [DepartmentController::class, 'store'])->name('department.store');

        Route::get('/department/new', [DepartmentController::class, 'indexNew'])->name('indexNew.department');
        Route::put('/api/departments/{id}', [DepartmentController::class, 'updateDepartment']);
        Route::put('/api/sub-departments/{id}', [DepartmentController::class, 'updateSubDepartment']);

        Route::get('/department/edit', [DepartmentController::class, 'indexEdit'])->name('indexEdit.department');

        Route::get('/department/delete', [DepartmentController::class, 'indexDelete'])->name('indexDelete.department');
        Route::post('/department/{id}/delete', [DepartmentController::class, 'softDelete'])->name('departments.softDelete');
    });
});
