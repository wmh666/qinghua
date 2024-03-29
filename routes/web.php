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
Route::GET('/pc/download', 'Admin\VerController@downloadfile');//文件xiazai
Route::post('/pc/inflist', 'Admin\VerController@inflist');//信息列表
Route::post('/pc/listimport', 'Admin\ImportController@listimport');//anli列表
Route::post('/pc/importfile', 'Admin\ImportController@importfile');//anli列表 上传文件
Route::post('/pc/importtype', 'Admin\ImportController@importtype');//anli搜索添加
Route::post('/pc/gbhy', 'Admin\ImportController@gbhy');//所属行业 门类
Route::post('/pc/country', 'Admin\ImportController@country');//国家
Route::post('/pc/province', 'Admin\ImportController@province');//省市
Route::post('/pc/typelist', 'Admin\ImportController@typelist');//搜索搜索
Route::get('/pc/aa', 'Admin\ImportController@aa');//搜索搜索
Route::post('/pc/map', 'Admin\ImportController@map');//map
Route::post('/pc/mapHisory', 'Admin\ImportController@mapHisory');//maplist
Route::post('/pc/mappic', 'Admin\ImportController@mapPic');//mappic  生成图片上传
Route::post('/pc/export', 'Admin\ImportController@export');//export 导出
Route::post('/pc/qfd', 'Admin\ImportController@qfd');//QDF
Route::post('/pc/qfdover', 'Admin\ImportController@qfdover');//QDF 交叉关系
Route::post('/pc/qfdlist', 'Admin\ImportController@qfdlist');//QDF list
Route::post('/pc/gbhydata', 'Admin\ImportController@gbhydata');// 所属行业 大类，中类
Route::post('/pc/casecount', 'Admin\ImportController@casecount');// 案例统计
Route::post('/pc/principle', 'Admin\ImportController@principle');// 各种原理
Route::post('/pc/principlelist', 'Admin\ImportController@principlelist');// 各种原理详情
Route::GET('/pc/principleExport', 'Admin\ImportController@principleExport');// 各种原理导出
Route::post('/pc/upcaseImg', 'Admin\ImportController@upcase_img');// 修改图片
Route::GET('/pc/exportcount', 'Admin\ImportController@exportcount');// 案例统计导出
Route::GET('/pc/test', 'Admin\ImportController@test');// 案例统计导出

Route::POST('/pc/a', 'Admin\ImportController@a');// 案例统计导出
Route::POST('/pc/mt', 'Admin\ImportController@mt');// mt
Route::POST('/pc/htmldata', 'Admin\ImportController@htmldata');// htmldata
Route::POST('/pc/htmllist', 'Admin\ImportController@htmldatalist');// htmllist

Route::GET('/pc/email', 'Admin\ImportController@email');// email
Route::POST('/pc/delimg', 'Admin\ImportController@delimg');// delimg
Route::GET('/pc/yuemail', 'Admin\LoginController@email');// delimg

