<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Warehouse\InventoryController;
use App\Http\Controllers\Archive\UploadController;

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
    Route::get('/settings/users-management', [UserController::class, 'index'])->name('users-management');
    Route::get('/account/getData', [UserController::class, 'accountGetData'])->name('account.getData');
    Route::get('/users/getList', [UserController::class, 'userGetData'])->name('users.getList');
    Route::get('/users/getMainMenus', [UserController::class, 'usergetMainMenus'])->name('menus.getMainMenus');

    Route::get('/menus/getData', [UserController::class, 'getData'])->name('menus.getData');
    Route::get('/menus/getUserAccess', [UserController::class, 'getUserAccess'])->name('menus.getUserAccess');


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
        Route::get('/upload', [UploadController::class, 'index'])->name('index.upload');
        Route::get('/archives/{archive}/pdf', [UploadController::class, 'showPdf'])->name('archives.pdf');

        Route::get('/upload/newarchive', [UploadController::class, 'indexNewArchive'])->name('index.newarchive');
        Route::post('/upload/new', [UploadController::class, 'store'])->name('archives.store');
        Route::get('/generate-document-number', [UploadController::class, 'generateDocumentNumber']);

        Route::get('/upload/editarchive', [UploadController::class, 'indexEditArchive'])->name('index.editarchive');
        Route::put('/archive/{archive}', [UploadController::class, 'update'])->name('archive.update');

        Route::get('/upload/deletearchive', [UploadController::class, 'indexDeleteArchive'])->name('index.deletearchive');
        Route::delete('/upload/delete/{id}', [UploadController::class, 'destroy'])->name('upload.destroy');
    });
});








// Route::middleware(['auth:sanctum', 'verified'])->group(function () {

//     // Route for the getting the data feed
//     Route::get('/json-data-feed', [DataFeedController::class, 'getDataFeed'])->name('json_data_feed');


   
//     Route::get('/ecommerce/shop', function () {
//         return view('pages/ecommerce/shop');
//     })->name('shop');
//     Route::get('/ecommerce/shop-2', function () {
//         return view('pages/ecommerce/shop-2');
//     })->name('shop-2');
//     Route::get('/ecommerce/product', function () {
//         return view('pages/ecommerce/product');
//     })->name('product');
//     Route::get('/ecommerce/cart', function () {
//         return view('pages/ecommerce/cart');
//     })->name('cart');
//     Route::get('/ecommerce/cart-2', function () {
//         return view('pages/ecommerce/cart-2');
//     })->name('cart-2');
//     Route::get('/ecommerce/cart-3', function () {
//         return view('pages/ecommerce/cart-3');
//     })->name('cart-3');
//     Route::get('/ecommerce/pay', function () {
//         return view('pages/ecommerce/pay');
//     })->name('pay');
    
//     Route::get('/community/profile', function () {
//         return view('pages/community/profile');
//     })->name('profile');
//     Route::get('/community/feed', function () {
//         return view('pages/community/feed');
//     })->name('feed');
//     Route::get('/community/forum', function () {
//         return view('pages/community/forum');
//     })->name('forum');
//     Route::get('/community/forum-post', function () {
//         return view('pages/community/forum-post');
//     })->name('forum-post');
//     Route::get('/community/meetups', function () {
//         return view('pages/community/meetups');
//     })->name('meetups');
//     Route::get('/community/meetups-post', function () {
//         return view('pages/community/meetups-post');
//     })->name('meetups-post');
//     Route::get('/finance/cards', function () {
//         return view('pages/finance/credit-cards');
//     })->name('credit-cards');
//     Route::get('/job/job-post', function () {
//         return view('pages/job/job-post');
//     })->name('job-post');
//     Route::get('/job/company-profile', function () {
//         return view('pages/job/company-profile');
//     })->name('company-profile');
//     Route::get('/messages', function () {
//         return view('pages/messages');
//     })->name('messages');
//     Route::get('/tasks/kanban', function () {
//         return view('pages/tasks/tasks-kanban');
//     })->name('tasks-kanban');
//     Route::get('/tasks/list', function () {
//         return view('pages/tasks/tasks-list');
//     })->name('tasks-list');
//     Route::get('/inbox', function () {
//         return view('pages/inbox');
//     })->name('inbox');
//     Route::get('/calendar', function () {
//         return view('pages/calendar');
//     })->name('calendar');
//     Route::get('/settings/account', function () {
//         return view('pages/settings/account');
//     })->name('account');
//     Route::get('/settings/notifications', function () {
//         return view('pages/settings/notifications');
//     })->name('notifications');
//     Route::get('/settings/apps', function () {
//         return view('pages/settings/apps');
//     })->name('apps');
//     Route::get('/settings/plans', function () {
//         return view('pages/settings/plans');
//     })->name('plans');
//     Route::get('/settings/billing', function () {
//         return view('pages/settings/billing');
//     })->name('billing');
//     Route::get('/settings/feedback', function () {
//         return view('pages/settings/feedback');
//     })->name('feedback');
//     Route::get('/utility/changelog', function () {
//         return view('pages/utility/changelog');
//     })->name('changelog');
//     Route::get('/utility/roadmap', function () {
//         return view('pages/utility/roadmap');
//     })->name('roadmap');
//     Route::get('/utility/faqs', function () {
//         return view('pages/utility/faqs');
//     })->name('faqs');
//     Route::get('/utility/empty-state', function () {
//         return view('pages/utility/empty-state');
//     })->name('empty-state');
//     Route::get('/utility/404', function () {
//         return view('pages/utility/404');
//     })->name('404');
//     Route::get('/utility/knowledge-base', function () {
//         return view('pages/utility/knowledge-base');
//     })->name('knowledge-base');
//     Route::get('/onboarding-01', function () {
//         return view('pages/onboarding-01');
//     })->name('onboarding-01');
//     Route::get('/onboarding-02', function () {
//         return view('pages/onboarding-02');
//     })->name('onboarding-02');
//     Route::get('/onboarding-03', function () {
//         return view('pages/onboarding-03');
//     })->name('onboarding-03');
//     Route::get('/onboarding-04', function () {
//         return view('pages/onboarding-04');
//     })->name('onboarding-04');
//     Route::get('/component/button', function () {
//         return view('pages/component/button-page');
//     })->name('button-page');
//     Route::get('/component/form', function () {
//         return view('pages/component/form-page');
//     })->name('form-page');
//     Route::get('/component/dropdown', function () {
//         return view('pages/component/dropdown-page');
//     })->name('dropdown-page');
//     Route::get('/component/alert', function () {
//         return view('pages/component/alert-page');
//     })->name('alert-page');
//     Route::get('/component/modal', function () {
//         return view('pages/component/modal-page');
//     })->name('modal-page');
//     Route::get('/component/pagination', function () {
//         return view('pages/component/pagination-page');
//     })->name('pagination-page');
//     Route::get('/component/tabs', function () {
//         return view('pages/component/tabs-page');
//     })->name('tabs-page');
//     Route::get('/component/breadcrumb', function () {
//         return view('pages/component/breadcrumb-page');
//     })->name('breadcrumb-page');
//     Route::get('/component/badge', function () {
//         return view('pages/component/badge-page');
//     })->name('badge-page');
//     Route::get('/component/avatar', function () {
//         return view('pages/component/avatar-page');
//     })->name('avatar-page');
//     Route::get('/component/tooltip', function () {
//         return view('pages/component/tooltip-page');
//     })->name('tooltip-page');
//     Route::get('/component/accordion', function () {
//         return view('pages/component/accordion-page');
//     })->name('accordion-page');
//     Route::get('/component/icons', function () {
//         return view('pages/component/icons-page');
//     })->name('icons-page');
//     Route::fallback(function () {
//         return view('pages/utility/404');
//     });
// });
