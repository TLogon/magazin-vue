<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\AdminController;
use App\Http\Controllers\Api\v1\ProductsController;
use App\Http\Controllers\Api\v1\CountriesController;
use App\Http\Controllers\Api\v1\BrandsController;
use App\Http\Controllers\Api\v1\Product_photosController;
use App\Http\Controllers\Api\v1\MagazinindexController;
//use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Api\v1\UsersController;
use App\Http\Controllers\Api\v1\Buy_productController;
use App\Http\Controllers\Api\v1\Country_codesController;
use App\Http\Controllers\Api\v1\SizesController;
use App\Http\Controllers\Api\v1\Product_sizesController;
use App\Http\Controllers\Api\v1\BuyAdminController;

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



Route::resource('BuyProduct', Buy_productController::class);
Route::resource('magazin', MagazinindexController::class);
Route::resource('CountryCodes', Country_codesController::class);
Route::resource('Sizes', SizesController::class);
Route::resource('ProductSizes', Product_sizesController::class);
Route::get('MagazinMenu', [MagazinindexController::class, 'MagazinMenu']);
Route::get('SelectCountries', [MagazinindexController::class, 'SelectCountries']);
Route::get('SelectBrands', [MagazinindexController::class, 'SelectBrands']);
Route::post('LoadFavourites', [MagazinindexController::class, 'LoadFavourites']);
Route::post('LoadCart', [MagazinindexController::class, 'LoadCart']);
Route::get('LoadProduct/{id}', [MagazinindexController::class, 'LoadProduct']);
Route::post('GetSizes', [SizesController::class, 'GetSizes']);
Route::post('AvailableSizes', [Product_sizesController::class, 'AvailableSizes']);
Route::get('GetBuyers', [AdminController::class, 'GetBuyers']);
Route::get('GetSections/{id}', [AdminController::class, 'GetSections']);
Route::get('GetSubSections/{id}', [AdminController::class, 'GetSubSections']);




Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(
    [
        'middleware' => 'auth:sanctum'
    ],
    function () {
        Route::resource('BuyAdmin', BuyAdminController::class);
        Route::resource('users', UsersController::class);
        Route::get('users', [UsersController::class, 'index']);
        Route::post('register', [UsersController::class, 'register']);
        Route::resource('products', ProductsController::class);
        Route::resource('countries', CountriesController::class);
        Route::put('countries/delete/{id}', [CountriesController::class, 'updateDelete']);
        Route::resource('brands', BrandsController::class);
        Route::put('brands/delete/{id}', [BrandsController::class, 'updateDelete']);
        Route::resource('ProductPhotos', Product_photosController::class);
        Route::post('products/update', [ProductsController::class, 'update']);
        Route::put('products/avatar/{id}', [ProductsController::class, 'avatar']);
        Route::put('BuyAdmin/delete/{id}', [BuyAdminController::class, 'updateDelete']);
        Route::put('BuyAdmin/Processed/{id}', [BuyAdminController::class, 'Processed']);
    }
);
