<?php
Route::group(['prefix' => 'login'], function () {
    Route::get('index', ['uses' => 'Manage\Login@index', 'as' => 'manage.login.index']);
    
    Route::post('login', ['uses' => 'Manage\Login@login', 'as' => 'manage.login.login']);

    Route::get('loginout', ['uses' => 'Manage\Login@loginout', 'as' => 'manage.login.out']);
});




Route::group(['middleware' => 'manage.login'], function () {
    
Route::group(['prefix' => 'common'], function () {

    Route::post('upload', ['uses' => 'Manage\Common@upload', 'as' => 'manage.common.upload']);

    Route::get('output/{schedule_id}', ['uses' => 'Manage\Common@output', 'as' => 'manage.common.output']);

    Route::get('collecion', ['uses' => 'Manage\Common@collection', 'as' => 'manage.common.collection']);

    Route::get('addresses', ['uses' => 'Manage\Address@lists', 'as' => 'manage.address.lists']);
});

  

Route::group(['prefix' => 'admin'], function () {

	Route::get('index', ['uses' => 'Manage\Admin@index', 'as' => 'manage.admin.index']);

    Route::get('desktop', ['uses' => 'Manage\Admin@desktop', 'as' => 'manage.admin.desktop']);

	Route::get('lists', ['uses' => 'Manage\Admin@lists', 'as' => 'manage.admin.lists']);

	Route::get('add', ['uses' => 'Manage\Admin@add', 'as' => 'manage.admin.add']);

	Route::post('store', ['uses' => 'Manage\Admin@store', 'as' => 'manage.admin.store']);

    Route::post('delete', ['uses' => 'Manage\Admin@delete', 'as' => 'manage.admin.delete']);

	Route::get('detail/{id}', ['uses' => 'Manage\Admin@detail', 'as' => 'manage.admin.detail']);

    Route::post('update', ['uses' => 'Manage\Admin@update', 'as' => 'manage.admin.update']);

    Route::get('auth',['uses' => 'Manage\Admin@auth', 'as' => 'manage.admin.auth']);
});



Route::group(['prefix' => 'news'], function () {

    Route::get('lists',['uses' => 'Manage\News@lists', 'as' => 'manage.news.lists']);

    Route::get('detail/{id}',['uses' => 'Manage\News@detail', 'as' => 'manage.news.detail']);

    Route::get('add',['uses' => 'Manage\News@add', 'as' => 'manage.news.add']);

    Route::post('store',['uses' => 'Manage\News@store', 'as' => 'manage.news.store']);

    Route::post('update',['uses' => 'Manage\News@update', 'as' => 'manage.news.update']);

    Route::get('auth',['uses' => 'Manage\News@auth', 'as' => 'manage.news.auth']);

    Route::post('delete',['uses' => 'Manage\News@delete', 'as' => 'manage.news.delete']);
});
    

Route::group(['prefix' => 'student'], function () {

    Route::get('lists',['uses' => 'Manage\Student@lists', 'as' => 'manage.student.lists']);

    Route::get('detail/{id}',['uses' => 'Manage\Student@detail', 'as' => 'manage.student.detail']);

    Route::get('show/{id}',['uses' => 'Manage\Student@show', 'as' => 'manage.student.show']);

    Route::get('add',['uses' => 'Manage\Student@add', 'as' => 'manage.student.add']);

    Route::post('store',['uses' => 'Manage\Student@store', 'as' => 'manage.student.store']);

    Route::post('update',['uses' => 'Manage\Student@update', 'as' => 'manage.student.update']);

    Route::get('auth',['uses' => 'Manage\Student@auth', 'as' => 'manage.student.auth']);

    Route::get('search',['uses' => 'Manage\Student@search', 'as' => 'manage.student.search']);

    Route::post('sms',['uses' => 'Manage\Student@sms', 'as' => 'manage.student.sms']);
});


Route::group(['prefix' => 'teacher'], function () {

    Route::get('lists',['uses' => 'Manage\Teacher@lists', 'as' => 'manage.teacher.lists']);

    Route::get('detail/{id}',['uses' => 'Manage\Teacher@detail', 'as' => 'manage.teacher.detail']);

    Route::get('show/{id}',['uses' => 'Manage\Teacher@show', 'as' => 'manage.teacher.show']);

    Route::get('add',['uses' => 'Manage\Teacher@add', 'as' => 'manage.teacher.add']);

    Route::post('store',['uses' => 'Manage\Teacher@store', 'as' => 'manage.teacher.store']);

    Route::post('update',['uses' => 'Manage\Teacher@update', 'as' => 'manage.teacher.update']);

    Route::get('auth',['uses' => 'Manage\Teacher@auth', 'as' => 'manage.teacher.auth']);

    Route::get('search',['uses' => 'Manage\Teacher@search', 'as' => 'manage.teacher.search']);

    Route::post('sms',['uses' => 'Manage\Teacher@sms', 'as' => 'manage.teacher.sms']);
});

Route::group(['prefix' => 'institution'], function () {

    Route::get('lists',['uses' => 'Manage\Institution@lists', 'as' => 'manage.institution.lists']);

    Route::get('detail/{id}',['uses' => 'Manage\Institution@detail', 'as' => 'manage.institution.detail']);

    Route::get('show/{id}',['uses' => 'Manage\Institution@show', 'as' => 'manage.institution.show']);

    Route::get('add',['uses' => 'Manage\Institution@add', 'as' => 'manage.institution.add']);

    Route::post('store',['uses' => 'Manage\Institution@store', 'as' => 'manage.institution.store']);

    Route::post('update',['uses' => 'Manage\Institution@update', 'as' => 'manage.institution.update']);

    Route::get('auth',['uses' => 'Manage\Institution@auth', 'as' => 'manage.institution.auth']);

    Route::get('search',['uses' => 'Manage\Institution@search', 'as' => 'manage.institution.search']);

    Route::post('sms',['uses' => 'Manage\Institution@sms', 'as' => 'manage.institution.sms']);
});


Route::group(['prefix' => 'server'], function () {

    Route::get('lists',['uses' => 'Manage\Server@lists', 'as' => 'manage.server.lists']);
    
    Route::get('smsconfirm',['uses' => 'Manage\Server@smsConfirm', 'as' => 'manage.server.smsconfirm']);

    Route::get('auth',['uses' => 'Manage\Server@auth', 'as' => 'manage.server.auth']);

    Route::post('add',['uses' => 'Manage\Server@add', 'as' => 'manage.server.add']);

    Route::post('update',['uses' => 'Manage\Server@update', 'as' => 'manage.server.update']);

    Route::get('detail',['uses' => 'Manage\Server@detail', 'as' => 'manage.server.detail']);

});

Route::group(['prefix' => 'equipment'], function () {

    Route::get('lists',['uses' => 'Manage\Equipment@lists', 'as' => 'manage.equipment.lists']);

    Route::get('auth',['uses' => 'Manage\Equipment@auth', 'as' => 'manage.equipment.auth']);

    Route::get('add',['uses' => 'Manage\Equipment@add', 'as' => 'manage.equipment.add']);

    Route::post('update',['uses' => 'Manage\Equipment@update', 'as' => 'manage.equipment.update']);

    Route::get('detail',['uses' => 'Manage\Equipment@detail', 'as' => 'manage.equipment.detail']);

});


});




