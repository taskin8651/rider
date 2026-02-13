<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\RideController;
use App\Http\Controllers\Driver\RideController as DriverRideController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Driver\PayoutController as DriverPayoutController;
use App\Http\Controllers\Admin\PayoutController as AdminPayoutController;

/*
|--------------------------------------------------------------------------
| Redirect & Auth
|--------------------------------------------------------------------------
*/
Route::redirect('/', '/login');

Route::get('/home', function () {
    if (session('status')) {
        return redirect()->route('admin.home')->with('status', session('status'));
    }
    return redirect()->route('admin.home');
});

Auth::routes();

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::group([
    'prefix' => 'admin',
    'as' => 'admin.',
    'namespace' => 'Admin',
    'middleware' => ['auth']
], function () {

    Route::get('/', 'HomeController@index')->name('home');

    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')
        ->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')
        ->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')
        ->name('users.massDestroy');
    Route::resource('users', 'UsersController');

    // Audit Logs
    Route::resource('audit-logs', 'AuditLogsController', [
        'except' => ['create', 'store', 'edit', 'update', 'destroy']
    ]);

    // 🚖 RIDES (ADMIN → ALL)
    Route::get('rides', 'RideController@index')
        ->middleware('can:manage_rides')
        ->name('rides.index');

      Route::get('settings/commission',
    [SettingController::class, 'editCommission']
)->middleware(['auth', 'can:manage_rides'])
 ->name('settings.commission');

Route::post('settings/commission',
    [SettingController::class, 'updateCommission']
)->middleware(['auth', 'can:manage_rides']);


    Route::get('payouts',
        [AdminPayoutController::class, 'index']
    )->name('payouts.index');

    Route::post('payouts/{payout}/approve',
        [AdminPayoutController::class, 'approve']
    )->name('payouts.approve');

    Route::post('payouts/{payout}/reject',
        [AdminPayoutController::class, 'reject']
    )->name('payouts.reject');


});

/*
|--------------------------------------------------------------------------
| CUSTOMER ROUTES
|--------------------------------------------------------------------------
*/
Route::group([
    'middleware' => ['auth']
], function () {

    Route::get('/rides', [RideController::class, 'index'])
        ->name('rides.index');

    Route::post('/rides', [RideController::class, 'store'])
        ->middleware('can:book_ride')
        ->name('rides.store');

    Route::post('/rides/{ride}/cancel',
        [RideController::class, 'cancel']
    )->middleware(['auth', 'can:book_ride'])
     ->name('rides.cancel');

    Route::get('rides/{ride}/driver-location',
        [RideController::class, 'driverLocation']
    )->middleware('auth')
     ->name('rides.driver.location');

});


 


 

/*
|--------------------------------------------------------------------------
| DRIVER ROUTES
|--------------------------------------------------------------------------
*/
Route::group([
    'prefix' => 'driver',
    'as' => 'driver.',
    'middleware' => ['auth', 'can:accept_ride']
], function () {

    Route::get('rides', [DriverRideController::class, 'index'])
        ->name('rides.index');

    Route::post('rides/{ride}/accept', [DriverRideController::class, 'accept'])
        ->name('rides.accept');

    Route::post('driver/rides/{ride}/complete',
    [DriverRideController::class, 'complete']
)->middleware(['auth','can:accept_ride'])
 ->name('rides.complete');


    Route::post('rides/{ride}/cancel', [DriverRideController::class, 'cancel'])
        ->name('rides.cancel');

        
    Route::get('payouts',
        [DriverPayoutController::class, 'index']
    )->name('payouts.index');

    Route::post('payouts',
        [DriverPayoutController::class, 'store']
    )->name('payouts.store');

     Route::post('/location/update', 
        [DriverRideController::class, 'updateLocation']
    )->name('location.update');
    

});


/*
|--------------------------------------------------------------------------
| PROFILE ROUTES
|--------------------------------------------------------------------------
*/
Route::group([
    'prefix' => 'profile',
    'as' => 'profile.',
    'namespace' => 'Auth',
    'middleware' => ['auth']
], function () {

    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', 'ChangePasswordController@edit')
            ->name('password.edit');

        Route::post('password', 'ChangePasswordController@update')
            ->name('password.update');

        Route::post('profile', 'ChangePasswordController@updateProfile')
            ->name('password.updateProfile');

        Route::post('profile/destroy', 'ChangePasswordController@destroy')
            ->name('password.destroyProfile');
    }
});
