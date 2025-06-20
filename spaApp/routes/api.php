<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($request->only('email', 'password'))) {
        return response()->json([
            'message' => 'Login successful',
            'user' => Auth::user()
        ]);
    }

    throw ValidationException::withMessages([
        'error' => ['The provided credentials are incorrect.'],
    ]);
});

Route::post('/logout', function (Request $request) {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('students', 'StudentController@index');
Route::get('students/{student}', 'StudentController@show');
Route::get('student-statistics', 'StudentController@statistics');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('students', 'StudentController@store');
    Route::put('students/{student}', 'StudentController@update');
    Route::delete('students/{student}', 'StudentController@destroy');
});
