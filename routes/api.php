<?php

use App\Models\User;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Register
Route::get('/register',function(Request $request){
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
    ]);
        return $user;
});


// Login
Route::post('/login', function(Request $request){
    $credentials = $request->only('email', 'password');
    if(! Auth::attempt($credentials)){
        throw ValidationException::withMessages([
            'email' => 'Invalid Credentials',
        ]);
    }

    // generate an authenticated session
    $request->session()->regenerate();
    return response()->json(null, 201);
});

// Logout
Route::post('logout', function(Request $request){
    //auth('web')->logout();
    Auth::guard('web')->logout();

    // Invalidated the previous session
    $request->session()->invalidate();
    // Now create a new session tokern
    $request->session()->regenerateToken();

    return response()->json(null,200);
});
