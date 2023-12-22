<?php

use Illuminate\Support\Facades\Route;


Route::middleware(["superban:5,2,1440"])->group(function() {
    Route::get('/', function () {
        return view('welcome');
    });
});

Route::middleware(["superban"])->group(function() {
    Route::get("/example1", function(){
        return view("welcome");
    });
});

Route::get("/example2", function(){
    return view("welcome");
})->middleware("superban:5,2,1440");
