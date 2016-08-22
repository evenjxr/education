<?php
Route::get('api', ['uses' => 'Api@handOutDebug', 'as' => 'api.handOutDebug']);

Route::get('getjssign', ['uses' => 'Api@getJsSign', 'as' => 'api.getJsSign']);

Route::get('menu', ['uses' => 'Api@menu', 'as' => 'api.menu']);


//Route::group(['middleware' => 'manage.login'], function () {

    Route::group(['prefix' => 'user'], function () {
        Route::post('', ['uses' => 'Manage\Common@upload', 'as' => 'manage.common.upload']);
    });

//});


Route::get('city/lists', ['uses' => 'User@cityLists', 'as' => 'api.user.city.lists']);

Route::group(['prefix' => 'account'], function () {

    Route::post('login', ['uses' => 'Account@login']);

    Route::post('regist', ['uses' => 'Account@regist']);

    Route::post('sms', ['uses' => 'Account@sms']);

    Route::post('updatepassword', ['uses' => 'Account@updatepassword']);

    Route::get('detail', ['uses' => 'Account@detail']);

    Route::post('update', ['uses' => 'Account@update']);
});

Route::group(['prefix' => 'teacher'], function () {

    Route::get('lists', ['uses' => 'Teacher@lists']);

    Route::get('detail/{id}', ['uses' => 'Teacher@detail']);

    Route::get('recommend', ['uses' => 'Teacher@recommend']);

    Route::post('update', ['uses' => 'Teacher@update']);

    Route::get('subjects', ['uses' => 'Teacher@subjects']);
    
});

Route::group(['prefix' => 'institution'], function () {

    Route::get('teachers', ['uses' => 'Institution@teachers']);

    Route::get('detail/{id}', ['uses' => 'Institution@detail']);

    Route::post('update', ['uses' => 'Institution@update']);

    Route::post('delteacher', ['uses' => 'Institution@delteacher']);
});

Route::group(['prefix' => 'manage'], function () {

    Route::post('auth/teacher', ['uses' => 'Manage@authTeacher']);

    Route::post('auth/institution', ['uses' => 'Manage@authInstitution']);
});

Route::group(['prefix' => 'student'], function () {

    Route::get('state', ['uses' => 'Student@state']);

    Route::get('grades', ['uses' => 'Student@grades']);

});

Route::group(['prefix' => 'order'], function () {
    
    Route::post('server/add', ['uses' => 'Order@serverAdd']);

    Route::post('equipment/add', ['uses' => 'Order@equipmentAdd']);
});


Route::group(['prefix' => 'inviterecord'], function () {

    Route::get('lists', ['uses' => 'InviteRecord@lists']);
    
});











Route::group(['prefix' => 'user'], function () {
    
    Route::get('auth', ['uses' => 'User@auth', 'as' => 'api.user.auth']);
    
    Route::get('login', ['uses' => 'User@login', 'as' => 'api.user.login']);
    
    Route::post('sms', ['uses' => 'User@sms', 'as' => 'api.user.sms']);

    Route::get('detail', ['uses' => 'User@detail', 'as' => 'api.user.detail']);

    Route::post('update', ['uses' => 'User@update', 'as' => 'api.user.update']);

});




Route::group(['prefix' => 'news'], function () {

    Route::get('lists', ['uses' => 'News@lists', 'as' => 'api.news.lists']);

    Route::get('detail/{id}', ['uses' => 'News@detail', 'as' => 'api.news.detail']);
});

Route::group(['prefix' => 'video'], function () {

    Route::get('lists', ['uses' => 'Video@lists', 'as' => 'api.video.lists']);

});

Route::group(['prefix' => 'figure'], function () {

    Route::get('lists', ['uses' => 'Figure@lists', 'as' => 'api.figure.lists']);

    Route::get('detail/{id}', ['uses' => 'Figure@detail', 'as' => 'api.figure.detail']);

});

Route::group(['prefix' => 'active'], function () {

    Route::get('lists', ['uses' => 'Active@lists', 'as' => 'api.active.lists']);

    Route::get('lsitsbychannel/{channel?}', ['uses' => 'Active@getListByChannel', 'as' => 'api.active.bychannel']);

    Route::get('detail/{id}', ['uses' => 'Active@detail', 'as' => 'api.active.detail']);

    Route::get('theme/detail', ['uses' => 'Active@themeDetail', 'as' => 'api.active.theme']);

});

Route::group(['prefix' => 'schedule'], function () {

    Route::get('lists/{time?}', ['uses' => 'Schedule@lists', 'as' => 'api.schedule.lists']);

    Route::get('theme/detail', ['uses' => 'Schedule@themeDetail', 'as' => 'api.schedule.theme']);

});


Route::group(['prefix' => 'team'], function () {

    Route::post('store', ['uses' => 'Team@store', 'as' => 'api.team.store']);

    Route::get('detail/{id?}', ['uses' => 'Team@detail', 'as' => 'api.team.detail']);

    Route::any('delete', ['uses' => 'Team@delete', 'as' => 'api.team.delete']);

    Route::any('addplayer', ['uses' => 'Team@addPlayer', 'as' => 'api.add.player']);

    Route::any('deleteplayer', ['uses' => 'Team@deletePlayer', 'as' => 'api.delete.player']);
});


Route::group(['prefix' => 'collection'], function () {

    Route::get('lists', ['uses' => 'Collection@lists', 'as' => 'api.collection.lists']);

    Route::post('add', ['uses' => 'Collection@add', 'as' => 'api.collection.add']);

    Route::post('delete', ['uses' => 'Collection@delete', 'as' => 'api.collection.delete']);
});

Route::group(['prefix' => 'tournament'], function () {

    Route::get('lists', ['uses' => 'Tournament@lists', 'as' => 'api.tournament.lists']);

    Route::get('signup/{id}', ['uses' => 'Tournament@add', 'as' => 'api.tournament.signup']);

    Route::post('dosignup', ['uses' => 'Tournament@store', 'as' => 'api.tournament.dosignup']);

    Route::get('cancel/{id}', ['uses' => 'Tournament@delete', 'as' => 'api.tournament.delete']);
});
