<?php
use \App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;

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

// with UI
Route::resource('booking', 'BookingController');

// TEST Functions
Route::get('bookingtest', function(){

    $ret = App::make('\App\Http\Controllers\BookingController')->bookSeats('J1', 4);

    print_r( $ret );


});
