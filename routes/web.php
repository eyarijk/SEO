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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/contact', 'Ajax\ContactController@show');

Route::get('/rules','StatisController@rules');

Route::get('/profile','ProfileController@show')->middleware('auth');

Route::post('/avatar','ProfileController@avatar')->middleware('auth');

Route::post('/profile/edit','ProfileController@update')->middleware('auth');

Route::post('/contact/send', 'Ajax\ContactController@send');

Route::post('/context/redirect', 'ContextController@redirect');

Route::resource('/tasks','TasksController')->middleware('auth');

Route::resource('/message','MessageController')->middleware('auth');

Route::resource('/category','CategoryController')->middleware('auth');

Route::resource('/favorite','FavoriteController')->middleware('auth');

Route::resource('/deleting', 'DeletingController')->middleware('auth');

Route::resource('/worktask', 'WorktaskController')->middleware('auth');

Route::resource('/notification', 'NotificationController')->middleware('auth');

Route::resource('/comments', 'CommentsController')->middleware('auth');

Route::resource('/surfing', 'SurfingController')->middleware('auth');

Route::prefix('manage')->middleware('auth')->group(function () {
    //Task
    Route::get('/tasks','ManagetaskController@task');
    Route::get('/reportTask/{id}','ManagetaskController@report')->name('taskreport');
    Route::put('/task/status/', 'ManagetaskController@status');
    Route::put('/task/success/', 'ManagetaskController@success');
    Route::put('/task/danger/', 'ManagetaskController@danger');
    Route::get('/task/pay/{id}', 'ManagetaskController@pay')->name('taskpay');
    Route::put('/task/buy/', 'ManagetaskController@buy');
    //Context
    Route::resource('/contexts', 'ContextController')->middleware('auth');
    Route::put('/context/status/', 'ContextController@status');
    Route::get('/context/pay/{id}', 'ContextController@pay')->name('contextpay');
    Route::put('/context/buy/', 'ContextController@buy');
    //Message
    Route::get('/message','MessageController@manage');
    Route::get('/message/pay/{id}', 'MessageController@pay')->name('messagepay');
    Route::post('/message/status/', 'MessageController@status');
    Route::post('/message/buy/', 'MessageController@buy');
    Route::post('/message/work', 'MessageController@work');

});

Route::get('/notifications/clear', 'NotificationController@clear')->name('clear')->middleware('auth');

Route::get('/new', 'TasksController@new_tasks')->middleware('auth');

Route::get('/reusable', 'TasksController@reusable')->middleware('auth');

Route::get('/paid', 'TasksController@paid')->middleware('auth');

Route::get('/rejected', 'TasksController@rejected')->middleware('auth');

Route::get('/task/category/{slug}', 'TasksController@category')->name('taskcategory')->middleware('auth');

Route::get('/comments/{slug}', 'TasksController@comments')->name('taskcomments')->middleware('auth');

Route::get('/message/comments/{slug}', 'MessageController@comments')->name('messagecomments')->middleware('auth');

Route::post('/tasks/id','TasksController@searchid')->middleware('auth');

Route::post('/tasks/user_id','TasksController@userid')->middleware('auth');

Route::post('/tasks/url','TasksController@url')->middleware('auth');

Route::post('/tasks/comment/create', 'TasksController@createcomment')->middleware('auth');

Route::post('/message/comment/create', 'MessageController@createcomment')->middleware('auth');

Route::get('/cron/limit','CronController@limittask');
