<?php

use Illuminate\Support\Facades\Route;

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
Route::namespace('App\Http\Controllers')->group(function() {
    
    Route::get('login', 'LoginController@index')->name('login');
    Route::post('login', 'LoginController@authenticate')->name('doLogin');
    Route::get('logout', 'LoginController@logout')->name('logout');

    Route::group(['middleware' => ['auth', 'can:fnc1']], function () {
        Route::get('/', 'HomeController@index')->name('home');
    });

    Route::group(['middleware' => ['auth', 'can:fnc2']], function () {
        Route::group(['prefix' => 'users'], function () {
            Route::get('', 'UserController@index')->name('users');
            Route::get('/download-users', 'UserController@downloadUsers')->name('downloadUsers');
            Route::get('/{id}', 'UserController@show')->name('showUser');
            Route::post('/{id}/download-logs', 'UserController@downloadLogs')->name('downloadUserLogs');

            Route::post('/{id}/points', 'UserController@updatePoint')->name('updateUserPoint');
            Route::post('/{id}/pointTolerance', 'UserController@updateUserPointTolerance')->name('updateUserPointTolerance');

            Route::post('/{id}/status', 'UserController@changeStatus')->name('changeUserStatus');
        });
    });

    Route::group(['middleware' => ['auth', 'can:fnc3']], function () {
        Route::group(['prefix' => 'appraisers'], function () {
            Route::get('', 'AppraiserController@index')->name('appraisers');
            Route::get('/download-appraisers', 'AppraiserController@downloadAppraisers')->name('downloadAppraisers');
            Route::get('/{id}', 'AppraiserController@show')->name('showAppraiser');
            Route::post('/{id}/download-logs', 'AppraiserController@downloadLogs')->name('downloadAppraiserLogs');
            Route::post('/{id}/points', 'AppraiserController@updatePoint')->name('updateAppraiserPoint');
            Route::post('/{id}/profile', 'AppraiserController@updateProfile')->name('updateAppraiserProfile');
            Route::post('/{id}/status', 'AppraiserController@changeStatus')->name('changeAppraiserStatus');
        });
    });

/*
    Route::group(['middleware' => ['auth', 'can:fnc4']], function () {
        Route::group(['prefix' => 'messages'], function () {
            Route::get('', 'MessageController@index')->name('messages');
            Route::post('/change-all-status', 'MessageController@changeAllStatus')->name('messages.changeAllStatus');
            Route::post('/change-each-status', 'MessageController@changeEachStatus')->name('messages.changeEachStatus');
        });
    });

    Route::group(['middleware' => ['auth', 'can:fnc5']], function () {
        Route::group(['prefix' => 'blogs'], function () {
            Route::get('', 'BlogController@index')->name('blogs');
            Route::post('/change-all-status', 'BlogController@changeAllStatus')->name('blogs.changeAllStatus');
            Route::post('/change-each-status', 'BlogController@changeEachStatus')->name('blogs.changeEachStatus');
        });
    });

    Route::group(['middleware' => ['auth', 'can:fnc6']], function () {
        Route::group(['prefix' => 'adcodes'], function () {
            Route::get('', 'AdcodeController@index')->name('adcodes');
            Route::post('/change-all-status', 'AdcodeController@changeAllStatus')->name('adcodes.changeAllStatus');
            Route::post('/change-each-status', 'AdcodeController@changeEachStatus')->name('adcodes.changeEachStatus');
        });
    });

    Route::group(['middleware' => ['auth', 'can:fnc7']], function () {
        Route::group(['prefix' => 'businesses'], function () {
            Route::get('', 'BusinessController@index')->name('businesses');
            Route::get('/download-csv', 'BusinessController@downloadCsv')->name('businesses.downloadCsv');
        });
    });
*/
    Route::group(['middleware' => ['auth', 'can:fnc4']], function () {
        Route::group(['prefix' => 'disporder'], function () {
            Route::get('', 'AppraiserDispOrderController@index')->name('disporder');
            Route::get('/select', 'AppraiserDispOrderController@selection')->name('disporder.select');
            Route::post('/changeorder', 'AppraiserDispOrderController@changeOrder')->name('disporder.changeorder');
        });
    });

    Route::group(['middleware' => ['auth', 'can:fnc5']], function () {
        Route::group(['prefix' => 'messages'], function () {
            Route::get('', 'MessageController@index')->name('messages');
            Route::post('/change-all-status', 'MessageController@changeAllStatus')->name('messages.changeAllStatus');
            Route::post('/change-each-status', 'MessageController@changeEachStatus')->name('messages.changeEachStatus');
        });
    });

    Route::group(['middleware' => ['auth', 'can:fnc6']], function () {
        Route::group(['prefix' => 'blogs'], function () {
            Route::get('', 'BlogController@index')->name('blogs');
            Route::post('/change-all-status', 'BlogController@changeAllStatus')->name('blogs.changeAllStatus');
            Route::post('/change-each-status', 'BlogController@changeEachStatus')->name('blogs.changeEachStatus');
        });
    });

    Route::group(['middleware' => ['auth', 'can:fnc7']], function () {
        Route::group(['prefix' => 'adcodes'], function () {
            Route::get('', 'AdcodeController@index')->name('adcodes');
            Route::post('/change-all-status', 'AdcodeController@changeAllStatus')->name('adcodes.changeAllStatus');
            Route::post('/change-each-status', 'AdcodeController@changeEachStatus')->name('adcodes.changeEachStatus');
        });

        Route::group(['prefix' => 'businesses'], function () {
            Route::get('', 'BusinessController@index')->name('businesses');
            Route::get('/download-csv', 'BusinessController@downloadCsv')->name('businesses.downloadCsv');
        });
    });

/*
    Route::group(['middleware' => ['auth', 'can:fnc8']], function () {
        Route::group(['prefix' => 'sites'], function () {
            Route::get('', 'SitesController@index')->name('sites');
            Route::get('/download-csv', 'SitesController@downloadCsv')->name('sites.downloadCsv');
        });
    });
*/
    Route::group(['middleware' => ['auth', 'can:fnc8']], function () {
        Route::group(['prefix' => 'reviews'], function () {
            Route::get('', 'ReviewController@index')->name('reviews');
            Route::post('/change-all-status', 'ReviewController@changeAllStatus')->name('reviews.changeAllStatus');
            Route::post('/change-each-status', 'ReviewController@changeEachStatus')->name('reviews.changeEachStatus');
        });
    });

    Route::group(['middleware' => ['auth', 'can:fnc9']], function () {
        Route::group(['prefix' => 'numerics'], function () {
            Route::get('', 'NumericsController@index')->name('numerics');
            Route::get('/select', 'NumericsController@selection')->name('numerics.select');
            Route::get('/advertiseSearch', 'NumericsController@dispAdManageSearch')->name('numerics.advertiseSearch');
            Route::get('/appraiserSearch', 'NumericsController@dispAppraiserSearch')->name('numerics.appraiserSearch');
            Route::get('/download-csv', 'NumericsController@downloadCsv')->name('numerics.downloadCsv');
        });
    });

    Route::group(['middleware' => ['auth', 'can:fnc10']], function () {
       Route::group(['prefix' => 'rankings'], function () {
            Route::get('', 'RankingController@index')->name('rankings');
            Route::get('/select', 'RankingController@selection')->name('rankings.select');

            Route::get('/dayofweek', 'RankingController@dayofweek')->name('rankings.dayofweek');

            Route::get('/weekly', 'RankingController@weekly')->name('rankings.weekly');
            Route::get('/weeklyUpdate', 'RankingController@weeklyUpdate')->name('rankings.weeklyUpdate');

            Route::get('/monthly', 'RankingController@monthly')->name('rankings.monthly');
            Route::get('/monthlyUpdate', 'RankingController@monthlyUpdate')->name('rankings.monthlyUpdate');

            Route::get('/recommended', 'RankingController@recommended')->name('rankings.recommended');
            Route::get('/recommendedUpdate', 'RankingController@recommendedUpdate')->name('rankings.recommendedUpdate');
        });

        Route::group(['prefix' => 'mediacapture'], function () {
            Route::get('', 'MediacaptureController@index')->name('mediacaptures');
            // Route::post('/change-all-status', 'MediacapturesController@changeAllStatus')->name('mediacaptures.changeAllStatus');
            // Route::post('/change-each-status', 'MediacapturesController@changeEachStatus')->name('mediacaptures.changeEachStatus');
        });
    });
});
