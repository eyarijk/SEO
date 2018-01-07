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
    if(!auth()->id())
        return view('welcome');
    else
        return redirect()->route('tasks.index');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/contact', 'Ajax\ContactController@show')->name('contact');

Route::get('/rules','StatisController@rules')->name('rules');

Route::get('/news','PostController@news')->name('news');

Route::get('/news/{slug}', 'PostController@post')->name('post');

Route::get('/profile','ProfileController@show')->name('profile')->middleware('auth');

Route::get('/chat','ChatController@show')->name('chat')->middleware('auth');

Route::post('/chat/send','ChatController@send')->middleware('auth');

Route::post('/news/comment/create','PostController@createcomment')->name('post.comment');

Route::post('/posts/like','PostController@like')->middleware('auth');

Route::post('/avatar','ProfileController@avatar')->middleware('auth');

Route::post('/profile/edit','ProfileController@update')->middleware('auth');

Route::post('/contact/send', 'Ajax\ContactController@send');

Route::post('/context/redirect', 'ContextController@redirect');

Route::resource('/tasks','TasksController')->middleware('auth');

Route::resource('/message','MessageController')->middleware('auth');

Route::resource('/favorite','FavoriteController')->middleware('auth');

Route::resource('/deleting', 'DeletingController')->middleware('auth');

Route::resource('/worktask', 'WorktaskController')->middleware('auth');

Route::resource('/notification', 'NotificationController')->middleware('auth');

Route::resource('/comments', 'CommentsController')->middleware('auth');

Route::resource('/surfing', 'SurfingController')->middleware('auth');

Route::resource('/banner', 'BannerController')->middleware('auth');

Route::prefix('manage')->middleware('auth')->group(function () {
    //Task
    Route::get('/tasks','ManagetaskController@task')->name('managetask');
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
    Route::get('/message','MessageController@manage')->name('managemessage');
    Route::get('/message/pay/{id}', 'MessageController@pay')->name('messagepay');
    Route::post('/message/status/', 'MessageController@status');
    Route::post('/message/buy/', 'MessageController@buy');
    Route::post('/message/work', 'MessageController@work');
    //Surfing
    Route::get('/surfing','SurfingController@manage')->name('surfingmanage');
    Route::get('/surfing/pay/{id}', 'SurfingController@pay')->name('surfingpay');
    Route::post('/surfing/status/', 'SurfingController@status');
    Route::post('/surfing/buy/', 'SurfingController@buy');
    //Banner
    Route::get('/banner/','BannerController@manage')->name('bannermanage')->middleware('auth');
    Route::get('/banner/pay/{id}', 'BannerController@pay')->name('bannerpay');
    Route::post('/banner/status/', 'BannerController@status');
    Route::post('/banner/buy/', 'BannerController@buy');
});

Route::get('/notifications/clear', 'NotificationController@clear')->name('clear')->middleware('auth');

Route::get('/new', 'TasksController@new_tasks')->middleware('auth');

Route::get('/reusable', 'TasksController@reusable')->middleware('auth');

Route::get('/paid', 'TasksController@paid')->middleware('auth');

Route::get('/rejected', 'TasksController@rejected')->middleware('auth');

Route::get('/task/category/{slug}', 'TasksController@category')->name('taskcategory')->middleware('auth');

Route::get('/tasks/comments/{slug}', 'TasksController@comments')->name('taskcomments')->middleware('auth');

Route::get('/message/comments/{slug}', 'MessageController@comments')->name('messagecomments')->middleware('auth');

Route::get('/banner/redirect/{id}','BannerController@redirectbanner')->middleware('auth');

Route::post('/tasks/id','TasksController@searchid')->middleware('auth');

Route::post('/tasks/user_id','TasksController@userid')->middleware('auth');

Route::post('/tasks/url','TasksController@url')->middleware('auth');

Route::post('/tasks/comment/create', 'TasksController@createcomment')->middleware('auth');

Route::post('/message/comment/create', 'MessageController@createcomment')->middleware('auth');

Route::post('/surfing/valid', 'SurfingController@valid')->middleware('auth');

Route::get('/cron/limit','CronController@limittask');


//Admin
Route::prefix('admin')->middleware('role:superadministrator|administrator|editor|supporter')->group(function () {
    Route::get('/','ManageController@index')->name('manage.index');
    Route::get('/dashboard','ManageController@dashboard')->name('manage.dashboard');
    Route::get('/contact','Ajax\ContactController@admin')->name('contact.admin');
    Route::get('/contact/{id}','Ajax\ContactController@details')->name('contact.details');
    Route::post('/contact/answer','Ajax\ContactController@answer');
    Route::post('/contact/delete','Ajax\ContactController@delete');
    Route::post('/posts/status','PostController@status')->middleware('role:superadministrator|administrator|editor');
    Route::resource('/users','UserController')->middleware('role:superadministrator|administrator');
    Route::resource('/permissions','PermissionsController',['except' => 'destroy'])->middleware('role:superadministrator');
    Route::resource('/roles','RoleController',['except' => 'destroy'])->middleware('role:superadministrator');
    Route::resource('/posts','PostController')->middleware('role:superadministrator|administrator|editor');
    Route::resource('/category','CategoryController',['except' => 'destroy'])->middleware('role:superadministrator');
});

//API
Route::get('/email/unique','UserController@apiCheckUnique')->name('api.email.unique');
