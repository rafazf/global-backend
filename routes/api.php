<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/**CREACION DE LAS RUTAS NECESARIAS */
//Ruta para consultar todos los empleados
Route::get('/employees','App\Http\Controllers\EmployeeController@index');
//Ruta para crear un empleado
Route::post('/employees','App\Http\Controllers\EmployeeController@store');
//Ruta para consultar a un empleado
Route::get('/employees/{id_empleado}','App\Http\Controllers\EmployeeController@show');
//Ruta para editar un empleado
Route::put('/employees/{id_empleado}','App\Http\Controllers\EmployeeController@update');
//Ruta para eliminar un empleado
Route::delete('/employees/{employee}','App\Http\Controllers\EmployeeController@destroy');