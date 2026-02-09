<?php

Route::redirect('/', '/login');
Route::get('/home', function () {
    if (session('status')) {
        return redirect()->route('admin.home')->with('status', session('status'));
    }

    return redirect()->route('admin.home');
});

Auth::routes();

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');

    // Audit Logs
    Route::resource('audit-logs', 'AuditLogsController', ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);

    // Epaper
    Route::delete('epapers/destroy', 'EpaperController@massDestroy')->name('epapers.massDestroy');
    Route::post('epapers/media', 'EpaperController@storeMedia')->name('epapers.storeMedia');
    Route::post('epapers/ckmedia', 'EpaperController@storeCKEditorImages')->name('epapers.storeCKEditorImages');
    Route::post('epapers/parse-csv-import', 'EpaperController@parseCsvImport')->name('epapers.parseCsvImport');
    Route::post('epapers/process-csv-import', 'EpaperController@processCsvImport')->name('epapers.processCsvImport');
    Route::resource('epapers', 'EpaperController');
});
Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth']], function () {
    // Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
        Route::post('password', 'ChangePasswordController@update')->name('password.update');
        Route::post('profile', 'ChangePasswordController@updateProfile')->name('password.updateProfile');
        Route::post('profile/destroy', 'ChangePasswordController@destroy')->name('password.destroyProfile');
    }
});


Route::get('/epaper', [App\Http\Controllers\Custom\EpaperController::class, 'index'])->name('epaper.index');
Route::get('/epaper/{epaper}', [App\Http\Controllers\Custom\EpaperController::class, 'show'])->name('custom.epaper-detail');
Route::get('/pdf-view/{media}', function ($media) {
    $file = \Spatie\MediaLibrary\MediaCollections\Models\Media::findOrFail($media);
    $path = storage_path("app/public/{$file->id}/{$file->file_name}");

    return response()->file($path, [
        'Content-Type' => 'application/pdf',
        'Cross-Origin-Resource-Policy' => 'cross-origin',
    ]);
})->name('pdf.view');
