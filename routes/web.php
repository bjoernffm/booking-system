<?php

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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/jwt_gate/{jwt}', 'JwtGateController@process');

Route::get('/bookings/add', 'BookingController@addIndex');
Route::get('/bookings/ma/{booking_id}/{hash}', 'BookingController@fastAccess');

Route::resource('aircraft', 'AircraftController');
Route::get('/aircraft/{id}/delete', 'AircraftController@prepareDestroy');

Route::get('/slots/{id}/delete', 'SlotController@prepareDestroy');
Route::get('/bookings/{id}/delete', 'BookingController@prepareDestroy');

Route::resource('users', 'UserController');
Route::resource('slots', 'SlotController');
Route::resource('bookings', 'BookingController');

Route::get('/verify/mobile/{user}', function (Request $request, $userId) {
    $user = App\User::findOrFail($userId);

    if($user->mobile_verified_at === null) {
        $user->mobile_verified_at = now();
        $user->save();
    }

    return 'mobile verified';
})->name('verify_mobile')->middleware('signed');;
