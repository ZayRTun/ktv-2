<?php

use App\Http\Livewire\ExpenseType;
use App\Http\Livewire\LiveCustomerView;
use App\Http\Livewire\LiveDashboard;
use App\Http\Livewire\LiveExpenseDetailReport;
use App\Http\Livewire\LiveExpenseIndex;
use App\Http\Livewire\LiveExpenseSummaryReport;
use App\Http\Livewire\LiveFbMenuView;
use App\Http\Livewire\LiveItemIndex;
use App\Http\Livewire\LiveProfitSummaryReport;
use App\Http\Livewire\LivePurchaseDetailReport;
use App\Http\Livewire\LivePurchaseIndex;
use App\Http\Livewire\LivePurchaseSummaryReport;
use App\Http\Livewire\LiveRoomInfo;
use App\Http\Livewire\LiveRoomView;
use App\Http\Livewire\LiveSalesDetailReport;
use App\Http\Livewire\LiveSalesSummaryReport;
use App\Http\Livewire\LiveServiceStaffCommissionDetailReport;
use App\Http\Livewire\LiveServiceStaffCommissionSummaryReport;
use App\Http\Livewire\LiveServiceStaffView;
use App\Http\Livewire\LiveStockOutIndex;
use App\Http\Livewire\LiveSupplierIndex;
use App\Http\Livewire\LiveUserPasswordChange;
use App\Http\Livewire\LiveUserRolesAndPermissionView;
use App\Http\Livewire\LiveUserView;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/', LiveRoomInfo::class)->name('home')->middleware('permission:view inhouses');

    Route::get('/food-and-beverage/setting', LiveFbMenuView::class)->name('fnb.menu-view')->middleware('permission:view food and beverages');

    Route::get('/rooms', LiveRoomView::class)->name('room.index')->middleware('permission:view food and beverages');

    Route::get('/service-staff', LiveServiceStaffView::class)->name('service-staff.index')->middleware('permission:view service staffs');

    Route::get('/customer', LiveCustomerView::class)->name('customer.index')->middleware('permission:view customers');

    Route::get('/items', LiveItemIndex::class)->name('item.index')->middleware('permission:view items');

    Route::get('/suppliers', LiveSupplierIndex::class)->name('supplier.index')->middleware('permission:view suppliers');

    Route::get('/purchase', LivePurchaseIndex::class)->name('purchase.index')->middleware('permission:view purchases');

    Route::get('/stockout', LiveStockOutIndex::class)->name('stockout.index')->middleware('permission:view stockouts');

    Route::group(['middleware' => ['permission:view reports']], function () {
        Route::get('/reports', LiveDashboard::class)->name('report.dashboard');
        Route::get('/reports/sales-details', LiveSalesDetailReport::class)->name('report.sales-detail');
        Route::get('/reports/sales-summary', LiveSalesSummaryReport::class)->name('report.sales-summary');
        Route::get('/reports/purchase-details', LivePurchaseDetailReport::class)->name('report.purchase-details');
        Route::get('/reports/purchase-summary', LivePurchaseSummaryReport::class)->name('report.purchase-summary');
        Route::get('/reports/commission-details', LiveServiceStaffCommissionDetailReport::class)->name('report.commission-details');
        Route::get('/reports/commission-summary', LiveServiceStaffCommissionSummaryReport::class)->name('report.commission-summary');
        Route::get('/reports/expense-detail', LiveExpenseDetailReport::class)->name('report.expense-details');
        Route::get('/reports/expense-summary', LiveExpenseSummaryReport::class)->name('report.expense-summary');
        Route::get('/reports/profit-summary', LiveProfitSummaryReport::class)->name('report.profit-summary');
    });

    Route::get('/expenses', LiveExpenseIndex::class)->name('expense.index')->middleware('permission:view expenses');
    Route::get('/expenses/types', ExpenseType\Index::class)->name('expense.type.index')->middleware('permission:view expenses');


    Route::group(['middleware' => ['permission:view any users']], function () {
        Route::get('/users', LiveUserView::class)->name('users');
        Route::get('/users/roles-and-permission', LiveUserRolesAndPermissionView::class)->name('users.roles-permission');
    });

    Route::group(['middleware' => ['permission:view user']], function () {
        Route::get('/profile/{user}/change-password', LiveUserPasswordChange::class)->name('profile.change-password');
    });

    Route::get('/download/{file}', function ($file) {
        $path = storage_path("app/$file");

        if (file_exists($path)) {
            return response()->file($path)->deleteFileAfterSend();
        } else {
            abort(404);
        }
    })->name('download');
});

Route::get('/test', function () {
    // For Testing Stuffs
});


require __DIR__.'/auth.php';
