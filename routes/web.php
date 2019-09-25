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
Route::post('/pc/relist', 'Admin\LoginController@relist');//验证找回密码信息
Route::post('/pc/repwd', 'Admin\LoginController@repwd');//找回密码
Route::post('/pc/VerIns', 'Admin\VerController@VerificationIns');//验证add
Route::post('/pc/Verlist', 'Admin\VerController@VerificationList');//验证list
Route::post('/pc/inf', 'Admin\VerController@information');//信息添加
Route::post('/pc/file', 'Admin\VerController@addfile');//文件添加
Route::post('/pc/download', 'Admin\VerController@downloadfile');//文件xiazai
Route::post('/pc/inflist', 'Admin\VerController@inflist');//信息列表
Route::post('/pc/listimport', 'Admin\ImportController@listimport');//anli列表
Route::post('/pc/importfile', 'Admin\ImportController@importfile');//anli列表 上传文件
Route::post('/pc/importtype', 'Admin\ImportController@importtype');//anli搜索添加
Route::post('/pc/gbhy', 'Admin\ImportController@gbhy');//所属行业
Route::post('/pc/country', 'Admin\ImportController@country');//国家
Route::post('/pc/province', 'Admin\ImportController@province');//省市
Route::post('/pc/typelist', 'Admin\ImportController@typelist');//搜索搜索
Route::post('/pc/aa', 'Admin\ImportController@aa');//搜索搜索

