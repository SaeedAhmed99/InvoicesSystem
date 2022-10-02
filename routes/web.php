<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoicesDetialsController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\InvoiceAttachmentsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\CustomersReportController;
use App\Http\Controllers\InvoicesReportController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes(['register' => false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::group(['prefix' => 'invoices'], function(){
    Route::get('', [InvoiceController::class, 'index'])->name('invoices');
    Route::get('paid', [InvoiceController::class, 'invoicesPaid'])->name('invoices.paid');
    Route::get('unpaid', [InvoiceController::class, 'invoicesUnPaid'])->name('invoices.unpaid');
    Route::get('partial', [InvoiceController::class, 'invoicesPartial'])->name('invoices.partial');
    Route::get('create', [InvoiceController::class, 'create'])->name('invoices.create');
    Route::get('edit/{id}', [InvoiceController::class, 'edit'])->name('invoices.edit');
    Route::post('edit/update', [InvoiceController::class, 'update'])->name('invoices.update');
    Route::get('getproducts/{id}', [InvoiceController::class, 'getproducts'])->name('invoices.getproducts');
    Route::post('store', [InvoiceController::class, 'store'])->name('invoices.store');
    Route::get('details/{id}', [InvoicesDetialsController::class, 'show'])->name('invoice.details.show');
    Route::get('attachments/{attachment_name}', [InvoicesDetialsController::class, 'show_attachment'])->name('attachment.show');
    Route::get('attachments/{attachment_name}/download', [InvoicesDetialsController::class, 'download_attachment'])->name('attachment.download');
    Route::post('attachments/delete', [InvoicesDetialsController::class, 'delete_attachment'])->name('attachment.delete');
    Route::post('attachments/add/new', [InvoiceAttachmentsController::class, 'store'])->name('add.new.attachment');
    Route::post('delete', [InvoiceController::class, 'destroy'])->name('invoice.soft.delete');
    Route::get('trashed/', [InvoiceController::class, 'trashed_invoices'])->name('invoice.trashed');
    Route::post('trashed/force', [InvoiceController::class, 'forceDeleteInvoice'])->name('invoice.trashed.force');
    Route::post('trashed/recovery', [InvoiceController::class, 'recoveryInvoice'])->name('invoice.trashed.recovery');
    Route::get('status/edit/{id}', [InvoiceController::class, 'statusEditShow'])->name('status.edit.show');
    Route::post('status/update/', [InvoiceController::class, 'statusUpdateShow'])->name('status.update.show');
    Route::get('print/{id}/', [InvoiceController::class, 'printInvoice'])->name('print.invoice');
    Route::get('export/all', [InvoiceController::class, 'export'])->name('export');
    Route::get('export/paid', [InvoiceController::class, 'exportPaid'])->name('export.paid');
    Route::get('export/unpaid', [InvoiceController::class, 'exportUnPaid'])->name('export.unpaid');
    Route::get('export/partial', [InvoiceController::class, 'InvoicePartialExport'])->name('export.partial');
    Route::get('markAsReadAll', [InvoiceController::class, 'markAsReadAll'])->name('markAsReadAll');
});


Route::group(['prefix' => 'sections'], function(){
    Route::get('', [SectionController::class, 'index'])->name('sections');
    Route::post('store', [SectionController::class, 'store'])->name('sections.store');
    route::post('update', [SectionController::class, 'update'])->name('sections.update');
    route::post('destroy', [SectionController::class, 'destroy'])->name('sections.destroy');    
});


Route::group(['prefix' => 'products'], function(){
    Route::get('', [ProductsController::class, 'index'])->name('products');
    Route::post('store', [ProductsController::class, 'store'])->name('products.store');
    Route::post('destroy', [ProductsController::class, 'destroy'])->name('product.destroy');
    Route::post('update', [ProductsController::class, 'update'])->name('product.update');
});


Route::group(['prefix' => 'reports'], function(){
    Route::get('customers', [CustomersReportController::class, 'index'])->name('customers.reports');
    Route::post('invoices/search_customers', [CustomersReportController::class, 'search_customers'])->name('customers.search.reports');
    Route::get('invoices', [InvoicesReportController::class, 'index'])->name('invoices.reports');
    Route::post('invoices/search_invoices', [InvoicesReportController::class, 'search_invoices'])->name('invoices.search.reports');
});



Route::group(['middleware' => ['auth']], function() {
    Route::resource('roles','App\Http\Controllers\RoleController');
    Route::resource('users','App\Http\Controllers\UserController');
    Route::post('users/destroy', [UserController::class, 'destroy'])->name('destroy.user');
    });

Route::get('/{page}', [AdminController::class, 'index']);
