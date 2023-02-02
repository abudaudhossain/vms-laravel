<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OrganizationController;

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


route::get("/", function(){
    return response()->json(['message'=>"welcome"]);
});
// route::post('/login', [LoginController::class, 'login']);
route::post('/login', [LoginController::class, 'login']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*------------------------------------------
--------------------------------------------
Create Organization api
--------------------------------------------
--------------------------------------------*/
route::post('/create/organization', [OrganizationController::class, 'create_organization']);


/*------------------------------------------
--------------------------------------------
All Normal Users Routes List
--------------------------------------------
--------------------------------------------*/
Route::middleware([ 'user-access:user'])->group(function () {
  
    Route::get('/home', function(){
        return response()->json(['message'=>"user"]);
    })->name('home');
});
  
/*------------------------------------------
--------------------------------------------
All Admin Routes List
--------------------------------------------
--------------------------------------------*/
Route::middleware(['auth', 'user-access:admin'])->group(function () {
  
    Route::get('/admin/home',function(){
        return response()->json(['message'=>"Admin"]);
    });
});
  
/*------------------------------------------
--------------------------------------------
All Manager Routes List
--------------------------------------------
--------------------------------------------*/
Route::middleware(['auth', 'user-access:manager'])->group(function () {
  
    Route::get('/manager/home',function(){
        return response()->json(['message'=>"manager"]);
    })->name('manager.home');
});


