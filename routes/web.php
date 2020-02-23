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

Auth::routes(['verify' => true]);

Route::get('/', 'HomeController@index')->name('home');
//Route::get('/jwt_gate/{jwt}', 'JwtGateController@process');

Route::get('/slots/generator/step_1', 'SlotGeneratorController@step1');
Route::post('/slots/generator/step_1', 'SlotGeneratorController@storeStep1');
Route::get('/slots/generator/step_2', 'SlotGeneratorController@step2');

Route::get('/bookings/add', 'BookingController@addIndex');
Route::get('/bookings/ma/{booking_id}/{hash}', 'BookingController@fastAccess');

Route::resource('aircraft', 'AircraftController');
Route::get('/aircraft/{id}/delete', 'AircraftController@prepareDestroy');

Route::get('/slots/{id}/delete', 'SlotController@prepareDestroy');
Route::get('/users/{id}/delete', 'UserController@prepareDestroy');
Route::get('/bookings/{id}/delete', 'BookingController@prepareDestroy');

Route::resource('users', 'UserController');
Route::resource('slots', 'SlotController');
Route::resource('bookings', 'BookingController');


Route::get('/board', function(Request $request) {
    return view(
        'board/board'
    );
});

Route::get('/verify/mobile/{user}', function(Request $request, $userId) {
    $user = App\User::findOrFail($userId);

    if($user->mobile_verified_at !== null) {
        abort(403);
    }

    $user->mobile_verified_at = now();
    $user->save();

    return view(
        'mobile/alert',
        [
            'title' => 'Mobile validated',
            'message' => 'Your mobile phone has been successfully validated',
            'type' => 'success'
        ]
    );
})->name('verify_mobile')->middleware('signed');

Route::get('/verify/email/{user}', function(Request $request, $userId) {
    $user = App\User::findOrFail($userId);

    if($user->email_verified_at === null) {
        $user->mobile_verified_at = now();
        $user->save();
    }

    if($user->password === null) {
        //redirect
    }

    abort(403);
})->name('verify_email')->middleware('signed');
