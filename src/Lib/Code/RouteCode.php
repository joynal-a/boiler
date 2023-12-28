<?php

namespace Abedin\Boiler\Lib\Code; 

class RouteCode {
public static function webAuth(): string
{
return "\nRoute::controller(AuthController::class)->group(function(){
    Route::get('/login', 'index')->name('login');
    Route::post('/login', 'login')->name('login');
    Route::post('/logout', 'logout')->name('logout')->middleware('auth');
});";
}

public static function apiAuth(): string
{
return "\nRoute::controller(AuthController::class)->group(function(){
    Route::post('/signin', 'signin');
    Route::post('/signout', 'signout')->middleware('auth:api');
});";
}
}