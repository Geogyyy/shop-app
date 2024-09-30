<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PictureController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::post("/login", [AuthController::class, "loginUser"])->name("login");
Route::post("/register", [AuthController::class, "createUser"])->name("register");
Route::middleware("auth:sanctum")->group(function () {

    Route::post("/logout", [AuthController::class, "logoutUser"])->name("logout");
    Route::get("/user", [UserController::class, "show"]);

    Route::controller(CategoryController::class)->group(function () {
        Route::get("/categories", "index");
        Route::get("/tree", "getTree");
        Route::post("/category/add", "store");
        Route::get("/category/{id}", "show");
        Route::get("/category/{id}/subcategories", "findSubcategories");
        Route::post("/category/update/{id}", "update");
        Route::delete("/category/delete/{id}", "delete");
    });

    Route::controller(ProductController::class)->group(function () {
        Route::get("/products", "index");
        Route::get("/search", "searchProducts");
        Route::post("/product/add", "store");
        Route::get("/product/{id}", "show");
        Route::post("/product/update/{id}", "update");
        Route::delete("/product/delete/{id}", "delete");
    });


    Route::prefix("/product/{id}")->group(function () {
        Route::controller(PictureController::class)->group(function () {
            Route::get("/images", "index");
            Route::post("/image/add", action: "store");
            Route::get("/image/{id2}", "show");
            Route::post("/image/update/{id2}", "update");
            Route::delete("/image/delete/{id2}", "delete");
        });
    });

    Route::controller(OrderController::class)->group(function () {
        Route::get("/orders", "index");
        Route::post("/order/add", "store");
        Route::get("/order/{id}", "show");
        Route::post("/order/update/{id}", "update");
        Route::delete("/order/delete/{id}", "delete");
    });
});