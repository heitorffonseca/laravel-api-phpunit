<?php

use Illuminate\Support\Facades\Route;

Route::group(array('prefix' => 'api'), function()
{

    Route::get('/', function () {
        return response()->json(['message' => 'Jobs API', 'status' => 'Connected']);;
    });

    Route::resource('jobs', \App\Http\Controllers\JobsController::class);
    Route::resource('companies', \App\Http\Controllers\CompaniesController::class);
});

Route::get('/', function () {
    return redirect('api');
});
