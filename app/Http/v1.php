<?php
Route::get('api', ['uses' => 'Api@handOutDebug', 'as' => 'api.handOutDebug']);

Route::get('getjssign', ['uses' => 'Api@getJsSign', 'as' => 'api.getJsSign']);

Route::get('menu', ['uses' => 'Api@menu', 'as' => 'api.menu']);


//Route::group(['middleware' => 'manage.login'], function () {

    Route::group(['prefix' => 'user'], function () {
        Route::post('', ['uses' => 'Manage\Common@upload', 'as' => 'manage.common.upload']);
    });

//});

Route::group(['prefix' => 'common'], function () {

    Route::get('schoolwork', ['uses' => 'Common@getSchoolWork']);

    Route::get('teacher/state', ['uses' => 'Common@getTeacherState']);

    Route::get('teacher/worktime', ['uses' => 'Common@getWorkTime']);

    Route::get('subjects', ['uses' => 'Common@getSubjects']);

    Route::post('upload', ['uses' => 'Common@upload']);
});

Route::get('address/lists', ['uses' => 'Address@lists']);

Route::group(['prefix' => 'account'], function () {

    Route::post('login', ['uses' => 'Account@login']);

    Route::post('regist', ['uses' => 'Account@regist']);

    Route::post('sms', ['uses' => 'Account@sms']);

    Route::post('updatepassword', ['uses' => 'Account@updatePassword']);

    Route::post('findpassword', ['uses' => 'Account@findPassword']);

    Route::get('detail', ['uses' => 'Account@detail']);

    Route::post('update', ['uses' => 'Account@update']);
});

Route::group(['prefix' => 'teacher'], function () {

    Route::get('lists', ['uses' => 'Teacher@lists']);

    Route::get('detail/{id}', ['uses' => 'Teacher@detail']);

    Route::get('recommend', ['uses' => 'Teacher@recommend']);

    Route::post('update', ['uses' => 'Teacher@update']);

    Route::get('subjects', ['uses' => 'Teacher@subjects']);

    Route::get('unauth/lists', ['uses' => 'Teacher@unAuthLists']);
    
});

Route::group(['prefix' => 'institution'], function () {

    Route::get('teachers', ['uses' => 'Institution@teachers']);

    Route::get('detail/{id}', ['uses' => 'Institution@detail']);

    Route::post('update', ['uses' => 'Institution@update']);

    Route::post('delteacher', ['uses' => 'Institution@delTeacher']);

    Route::post('addteacher', ['uses' => 'Institution@addTeacher']);

    Route::get('unauth/lists', ['uses' => 'Institution@unAuthLists']);

});

Route::group(['prefix' => 'manage'], function () {

    Route::post('auth/teacher', ['uses' => 'Manage@authTeacher']);

    Route::post('auth/institution', ['uses' => 'Manage@authInstitution']);
});

Route::group(['prefix' => 'student'], function () {

    Route::get('state', ['uses' => 'Student@state']);

    Route::get('order/lists', ['uses' => 'Student@orderLists']);

    Route::get('grades', ['uses' => 'Student@grades']);

});

Route::group(['prefix' => 'order'], function () {

    Route::get('server/lists', ['uses' => 'Order@serverList']);
    
    Route::post('server/add', ['uses' => 'Order@serverAdd']);

    Route::get('server/detail', ['uses' => 'Order@serverDetail']);
    
    
    
    Route::get('server/fee', ['uses' => 'Order@serverFee']);
    
    Route::get('teacher/fee', ['uses' => 'Order@teacherFee']);
    
    

    Route::post('equipment/add', ['uses' => 'Order@equipmentAdd']);

    Route::get('equipment/lists', ['uses' => 'Order@equipmentList']);

    Route::get('equipment/detail', ['uses' => 'Order@equipmentDetail']);

    Route::get('equipment/has', ['uses' => 'Order@hasEquipment']);
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




