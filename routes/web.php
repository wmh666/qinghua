<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::post('/pc/import', 'Admin\ImportController@import');//导入
Route::post('/pc/login', 'Admin\LoginController@login');//登录
Route::post('/pc/userlist', 'Admin\LoginController@userlist');//用户列表
Route::post('/pc/uprole', 'Admin\LoginController@uprole');//修改权限
Route::post('/pc/rolelist', 'Admin\LoginController@rolelist');//权限list
Route::post('/pc/ins', 'Admin\LoginController@useradd');//用户添加
