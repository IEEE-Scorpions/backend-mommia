<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

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


Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'auth', 'localeViewPath']
    ], function(){


        Route::prefix("dashboard")->name("dashboard.")->group(function(){

            // Redirects Routes

            Route::get('500', function(){
                abort(500);
            });

            // Dashboard Routes

            Route::get('/', "DashboardController@index")->name('index');


            // Admins Routes

            Route::resource('admins', 'AdminController');



            // Profile Routes

            Route::get('profile', 'ProfileController@index')->name("profile.index");

            Route::get('profile/{profile}', 'ProfileController@index');

            Route::put('profile/{profile?}', 'ProfileController@update')->name('profile.update');

            // Tourists Routes

            Route::resource('tourists', 'TouristController')->except('show');
            Route::get('tourists/import', 'TouristController@import_page')->name('tourists.import');
            Route::post('tourists/import/send', 'TouristController@import')->name('tourists.import.send');
            Route::get('tourists/export', 'TouristController@export')->name('tourists.export');

            // TourGuides Routes

            Route::resource('tourguides', 'TourGuideController')->except('show');
            Route::get('tourguides/import', 'TourGuideController@import_page')->name('tourguides.import');
            Route::post('tourguides/import/send', 'TourGuideController@import')->name('tourguides.import.send');
            Route::get('tourguides/export', 'TourGuideController@export')->name('tourguides.export');

              // Packages Routes

              Route::resource('packages', 'PackageController')->except('show');
              Route::get('packages/import', 'PackageController@import_page')->name('packages.import');
              Route::post('packages/import/send', 'PackageController@import')->name('packages.import.send');
              Route::get('packages/export', 'PackageController@export')->name('packages.export');

            // Settings Routes

            Route::get('settings', 'SettingsController@index')->name('settings.index');
            Route::put('settings/{setting}', 'SettingsController@update')->name('settings.update');

        });

});



Route::get('/', function(){
    if(auth()->check()){
        return redirect("/dashboard");
    }else{
        return redirect()->route('login');
    }
});
