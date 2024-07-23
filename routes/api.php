<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\RateController;
use App\Http\Controllers\Api\StepController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PhotoController;
use App\Http\Controllers\Api\RecipeController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\FavRecipeController;
use App\Http\Controllers\Api\IngredientsController;
use App\Http\Controllers\Api\AdvertisementController;
use App\Http\Controllers\ChangeRoles;
use App\Http\Controllers\Api\RecipeIngredientsController;
use App\Http\Controllers\EmailController;
use App\Models\Recipe;

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

Route::get('/test', function()
{
    dd('hello online');
});


Route::middleware('auth:api')->group(function () {
   
    Route::put('updateProfile', [AuthController::class, 'updateProfile']);

});


Route::middleware(['user','auth:api'])->group(function () {
    Route::get('logout', [AuthController::class, 'logout']);
    //////////////

    Route::prefix('recipes/{recipe}/comments')->group(function () {
        Route::get('/', [CommentController::class, 'index']);
        Route::post('/', [CommentController::class, 'store']);
        Route::get('/{id}', [CommentController::class, 'show']);
        Route::post('/{id}', [CommentController::class, 'update']);
        Route::delete('/{id}', [CommentController::class, 'destroy']);
    });

    Route::prefix('recipes/{recipe}/rates')->group(function () {
        Route::get('/', [RateController::class, 'index']);
        Route::post('/{id?}', [RateController::class, 'store']);
        //Route::get('/{id}', [RateController::class, 'show']);
        //Route::post('/{id}', [RateController::class, 'update']);
        Route::delete('/', [RateController::class, 'destroy']);
    });

    Route::prefix('recipes/{recipe}/reports')->group(function () {
        Route::get('/', [ReportController::class, 'index']);
        Route::post('/', [ReportController::class, 'store']);
        Route::get('/{id}', [ReportController::class, 'show']);
        Route::post('/{id}', [ReportController::class, 'update']);
        //Route::delete('/{id}', [ReportController::class, 'destroy']);
    });

    Route::prefix('recipes/{recipe}/likes')->group(function () {
        Route::get('/', [LikeController::class, 'index']);
        Route::post('/', [LikeController::class, 'store']);
        //Route::get('/{id}', [LikeController::class, 'show']);
        //Route::post('/{id}', [LikeController::class, 'update']);
        //Route::delete('/{id}', [LikeController::class, 'destroy']);
    });
    ///fav//
    Route::get('fav', [FavRecipeController::class, 'index']);
    Route::post('recipes/{recipe}/fav', [FavRecipeController::class, 'store']);
    ////////الوصفات
    Route::prefix('recipes')->group(function () {
        Route::get('/', [RecipeController::class, 'index']);
        Route::get('/{recipe}', [RecipeController::class, 'show']);
    });
    /////المكونات
    Route::prefix('ingredients')->group(function () {
        Route::get('/', [IngredientsController::class, 'index']);
        Route::get('/{ingredients}', [IngredientsController::class, 'show']);
    });
    /////التصنيف
    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::get('/{category}', [CategoryController::class, 'show']);
    });
    /////الاعلانات
    Route::prefix('advertisements')->middleware(['auth:api'])->group(function () {
        Route::get('/', [AdvertisementController::class, 'index']);
        Route::get('/{advertisement}', [AdvertisementController::class, 'show']);
    });
});
Route::post('signup', [AuthController::class, 'signup']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware(['chef', 'auth:api'])->group(function () {

    Route::prefix('recipes')->group(function () {
        Route::post('/', [RecipeController::class, 'store']);
        Route::post('/{recipe}', [RecipeController::class, 'update']);
        Route::delete('/{recipe}', [RecipeController::class, 'destroy']);
    });

    Route::prefix('ingredients')->group(function () {
        Route::post('/', [IngredientsController::class, 'store']);
        Route::post('/{ingredients}', [IngredientsController::class, 'update']);
        Route::delete('/{ingredients}', [IngredientsController::class, 'destroy']);
    });

    Route::apiResource('steps', StepController::class);
});

Route::middleware(['admin', 'auth:api'])->group(function () {

    Route::prefix('categories')->group(function () {
        Route::post('/', [CategoryController::class, 'store']);
        Route::post('/{category}', [CategoryController::class, 'update']);
        Route::delete('/{category}', [CategoryController::class, 'destroy']);
    });


    Route::prefix('advertisements')->middleware(['auth:api'])->group(function () {
        Route::post('/', [AdvertisementController::class, 'store']);
        Route::post('/{advertisement}', [AdvertisementController::class, 'update']);
        Route::delete('/{advertisement}', [AdvertisementController::class, 'destroy']);
    });
    Route::get('send/{id}', [AdvertisementController::class,'sendEmails']);

    //   Users
    Route::post('/change-permissions', [ChangeRoles::class, 'changePermissions']);
    Route::post('/chefcreate', [ChangeRoles::class, 'chefcreate']);
    Route::get('/users', [ChangeRoles::class, 'getUsers']);
});
