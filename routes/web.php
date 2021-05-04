<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\OrderController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\WorkerController;
use App\Http\Controllers\WorkController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\MaterialController;
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
    return view('auth/login');
});

Auth::routes();

Route::group(['middleware' => ['auth']], function() {
    Route::get('/',function (){
        return view('welcome');
    });
    Route::group(['middleware'=>['permission:order-list']],function(){
        Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
        Route::post('orders', [OrderController::class, 'create']);

        Route::post('orders/cars', [CustomerController::class, 'getCar']);
        Route::post('customer/create', [CustomerController::class, 'jsonCreate']);
        Route::post('orders/workers', [WorkController::class, 'getWorker']);

        Route::post('materials', [MaterialController::class, 'create']);
    });

    Route::group(['middleware'=>['permission:customer-list']],function(){
        Route::get('customers', [CustomerController::class,'index'])->name('customers.index');
        Route::post('customers', [CustomerController::class,'create']);
        Route::patch('customers', [CustomerController::class,'update']);
        Route::delete('customers', [CustomerController::class,'destroy']);

        Route::get('cars', [CarController::class,'index'])->name('cars.index');
        Route::post('cars', [CarController::class,'create']);
        Route::post('models', [CarController::class,'createModel']);
        Route::post('firms', [CarController::class,'createFirm']);
        Route::patch('cars', [CarController::class,'update']);
        Route::delete('cars', [CarController::class,'destroy']);
    });

    Route::group(['middleware'=>['permission:worker-list']],function(){
        Route::get('workers', [WorkerController::class,'index'])->name('workers.index');
        Route::put('workers', [WorkerController::class,'download']);
        Route::post('workers', [WorkerController::class,'create']);
        Route::patch('workers', [WorkerController::class,'update']);
        Route::delete('workers', [WorkerController::class,'destroy']);

        Route::group(['middleware'=>['permission:work-list']],function(){
            Route::get('works', [WorkController::class,'index'])->name('workers.works');
            Route::post('works-posts', [WorkController::class,'createWorkPost']);
            Route::patch('works-posts', [WorkController::class,'updateWorkPost']);
            Route::post('works', [WorkController::class,'create']);
            Route::patch('works', [WorkController::class,'update']);
            Route::delete('works', [WorkController::class,'destroy']);

            Route::post('posts', [PostController::class,'create']);
            Route::delete('posts', [PostController::class,'destroy']);
        });
    });

    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
});
