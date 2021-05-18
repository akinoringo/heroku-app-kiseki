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

# ユーザー登録, ログイン, ログアウト
Auth::routes();

# ゲストユーザーログイン
Route::get('guest', 'Auth\LoginController@guestLogin')->name('login.guest');

# トップページ / 軌跡の一覧表示
Route::get('/', 'EffortController@index')->name('home');

# 目標関連ルーティング
Route::resource('/goals', 'GoalController')->except(['index, show'])->middleware('auth');
Route::resource('/goals', 'GoalController')->only(['show']);
Route::post('/goals/{goal}/clear', 'GoalController@clear')->name('goals.clear');


# 軌跡関連ルーティング
Route::resource('/efforts', 'EffortController')->except(['index, show'])->middleware('auth');
Route::resource('/efforts', 'EffortController')->only(['show']);
Route::prefix('efforts')->name('efforts.')->group(function(){
	Route::put('/{effort}/like', 'EffortController@like')->name('like')->middleware('auth');
	Route::delete('/{effort}/like', 'EffortController@unlike')->name('unlike')->middleware('auth');
});

# フォロー / フォロー取り消し
Route::middleware('auth')->group(function(){
	Route::put('/{name}/follow', 'ProfileController@follow')->name('follow');
	Route::delete('/{name}/follow', 'ProfileController@unfollow')->name('unfollow');

});

# フォロワー / フォロイーの表示
Route::get('/{name}/followings', 'ProfileController@followings')->name('followings');
Route::get('/{name}/followers', 'ProfileController@followers')->name('followers');

# Profileの修正, 更新
Route::get('/mypage/edit/{id}', 'ProfileController@edit')->name('mypage.edit')->middleware('auth');
Route::post('/mypage/update', 'ProfileController@update')->name('mypage.update')->middleware('auth');

# マイページの表示
Route::get('/mypage/{id}', 'ProfileController@show')->name('mypage.show'); // 追記

# 積み上げ時間をグラフで表示
Route::get('{id}/effortgraph', 'EffortGraphController@index');
