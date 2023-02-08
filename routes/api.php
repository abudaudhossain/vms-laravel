<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\UserController;

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
    return response()->json(['message'=>"welcome to vms by laravel"]);
});


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:api-visitor')->get('/visitor', function (Request $request) {
    return $request->user();
});

/*------------------------------------------
--------------------------------------------
Create Organization api
--------------------------------------------
--------------------------------------------*/
route::post('/create/organization', [OrganizationController::class, 'create_organization']);

route::post('/login', [LoginController::class, 'login']);

route::get("/all/organizations", [OrganizationController::class, 'get_organization']);

/*------------------------------------------
--------------------------------------------
All Visitor Routes List
--------------------------------------------
--------------------------------------------*/
route::post("/get-otp", [LoginController::class, 'getOTP']);
route::post('/otp-validation', [LoginController::class, 'OTPValidation']);

// Route::middleware([ 'user-access:user'])->group(function () {
  
//     Route::get('/home', function(){
//         return response()->json(['message'=>"user"]);
//     })->name('home');
// });
  
/*------------------------------------------
--------------------------------------------
All Admin Routes List
--------------------------------------------
--------------------------------------------*/
Route::middleware(['auth:api', 'user-access:admin'])->group(function () {
  
    Route::get('/admin/home',function(){
        return response()->json(['message'=>"Admin"]);
    });

    Route::post("/admin/create/new/employee", [UserController::class, 'create_employee']);
    Route::get("/all/employees", [UserController::class, 'get_all_employees']);
    Route::get("/employee/{id}", [UserController::class, 'get_employee_by_id']);
});
  
/*------------------------------------------
--------------------------------------------
All Manager Routes List
--------------------------------------------
--------------------------------------------*/
// Route::middleware(['auth', 'user-access:manager'])->group(function () {
  
//     Route::get('/manager/home',function(){
//         return response()->json(['message'=>"manager"]);
//     })->name('manager.home');
// });


