<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

// * Сделать что бы все переменные приложение из базы сразу подгружались в storage приложения

$router->put('/', function () {
  return [
    'Header' => 'Hello, it\'s test API for Playlist app.',
    'API version' => 0.1,
    'description' => 'To auth you must login through "/user/login" route.'
  ];
});

// $router->get('/sms/test', function () {
//   (new \App\SMSProvider())->sendSMS('Thanks ', '050-6862828');
// });

// $router->get('/pay/test', function () {
//   return [
//     Illuminate\Support\Facades\Crypt::encrypt(json_encode([
//       'token' => 'EfMnxPT4fhoMF2UFd4iGUlv9FrYowrDQXHL7q1TaKGxXUsdrwx9lp7fqNsALSwQk',
//       'id_facebook' => '2151566774937556',
//     ], JSON_UNESCAPED_UNICODE))
//   ];
// });

$router->post('/debug', function (\Illuminate\Http\Request $request) {
  file_put_contents('error.log', json_encode($request->all()) . "\n ------------ \n", FILE_APPEND);
});

// {
//
// }

$router->get('/subscribe/callback', 'SubscribeController@callback');
$router->get('/funkit/callback', 'FunkitController@buyCallback');

$router->get('/', function () {
  readfile('../resources/views/app.html');
});

$router->group([ 'prefix' => '/user' ], function () use ($router) {
  $router->post('/registrate', 'UserController@registrate');
  $router->post('/login', 'UserController@stayLogged');
  $router->post('/login-by-phone', 'UserController@loginByPhone');
  $router->post('/check-login-by-phone', 'UserController@checkLoginByPhone');
  $router->post('/recover', 'UserController@recoverPass');
  $router->post('/registrateByPhone', 'UserController@registrateByPhone');
  $router->post('/confirmByPhone', 'UserController@confirmByPhone');
  $router->get('/recover[/{code}]', 'UserController@checkRecoverCode');
  $router->post('/recover/new-pass', 'UserController@changePassKey');
  $router->get('/verify/{code}', 'UserController@verify');
  $router->get('/info/{id_user}', 'UserController@getUserInfo');

  $router->group([ 'prefix' => '/facebook' ], function () use ($router) {
    $router->post('/enter', 'FacebookController@enter');
  });
});

$router->group([ 'middleware' => 'user' ], function () use ($router) {

  $router->post('/file', 'FileController@attachFile');
  $router->post('/contact', 'OtherController@sendContact');
  $router->post('/gift/buy', 'OtherController@buyGift');
  $router->get('/variable/{name}', 'VariableController@get');
  $router->get('/activity/{id_essence}/{type}', 'OtherController@addActivity');

  $router->get('/transaction/{id_transaction}', function (\Illuminate\Http\Request $request, $id_transaction) {
    return [
      'ready' => app()->mdb->has('transaction', [
        'id_transaction' => $id_transaction,
        'id_user' => $request->attributes['user']['id_user'],
        'completed' => true,
      ])
    ];
  });


  // user routes
  $router->group([ 'prefix' => '/user' ], function () use ($router) {
    $router->post('/image/permanent', 'UserController@setImage');
    $router->put('/edit', 'UserController@saveProfile');
    $router->get('/playlist/done', 'PlaylistController@getUserPoints');
    $router->get('/info', 'UserController@getMyInfo');
    $router->post('/search', 'UserController@searchUser');
    $router->get('/played', 'UserController@getPlayedCount');
  });


  // shootlist routes
  $router->group([ 'prefix' => '/shootlist' ], function () use ($router) {
    $router->get('/requests/top', 'ShootlistController@getRequestsStats');
    $router->post('/', 'ShootlistController@store');
    $router->post('/random', 'ShootlistController@getRandom');
    $router->post('/full', 'ShootlistController@selectFull');
    $router->get('/liked', 'ShootlistController@getLiked');
    $router->put('/{id_shootlist}/like', 'ShootlistController@like');
    $router->delete('/{id_shootlist}/dislike', 'ShootlistController@disLike');
    $router->delete('/{id_shootlist}', 'ShootlistController@delete');
    $router->get('/{id_shootlist}', 'ShootlistController@get');
    $router->post('/request', 'ShootlistController@addRequest');
    $router->post('/filter-state/get', 'ShootlistController@getFilterState');
    $router->group([ 'middleware' => 'admin' ], function () use ($router) {
      $router->put('/{id_shootlist}', 'ShootlistController@update');
      $router->get('/{id_shootlist}/full', 'ShootlistController@getFull');
    });
    $router->put('/{id_shootlist}/activity/{type}', 'ShootlistController@addActivity');
  });

  $router->group([ 'prefix' => '/lessons' ], function () use ($router) {
    $router->get('/', 'TeacherController@index');
    $router->get('/groups', 'TeacherController@groups');
    $router->get('/{id}', 'TeacherController@lesson');
    $router->post('/category', 'TeacherController@addCategory');
    $router->put('/category/{id}', 'TeacherController@updateCategory');
    $router->delete('/category/{id}', 'TeacherController@removeCategory');
  });

  $router->group([ 'prefix' => '/teachers' ], function () use ($router) {
    $router->get('/', 'TeacherController@index');
    $router->get('/categories', 'TeacherController@categories');
    $router->get('/games', 'TeacherController@games');
    $router->post('/', 'TeacherController@create');
    $router->post('/{id}/set-icon', 'TeacherController@icon');
    $router->put('/{id}', 'TeacherController@update');
    $router->delete('/{id}', 'TeacherController@remove');
    $router->get('/lesson-games/{lessonId}', 'TeacherController@lessonGames');
  });

  // playlist routes
  $router->group([ 'prefix' => '/playlist' ], function () use ($router) {
    $router->get('/requests/top', 'PlaylistController@getRequestsStats');
    $router->post('/prizers', 'PlaylistController@getPrizers');
    $router->post('/', 'PlaylistController@store');
    $router->get('/', 'PlaylistController@index');
    $router->delete('/{id_playlist}', 'PlaylistController@delete');
    $router->put('/{id_playlist}/like', 'PlaylistController@like');
    $router->delete('/{id_playlist}/dislike', 'PlaylistController@disLike');
    $router->get('/liked', 'PlaylistController@getLiked');
    $router->post('/random', 'PlaylistController@getRandom');
    $router->post('/{id_playlist}/image', 'PlaylistController@setImage');
    $router->post('/{id_playlist}/audio', 'PlaylistController@setAudio');
    $router->delete('/{id_playlist}/audio', 'PlaylistController@removeAudio');
    $router->get('/categories', 'PlaylistController@getCategories');
    $router->get('/category/sections', 'PlaylistController@getCategorySections');
    $router->put('/category/{id_category}', 'PlaylistController@updateCategory');
    $router->post('/category/{id_category}/set-image', 'PlaylistController@setCategorySVG');
    $router->post('/search', 'PlaylistController@search');
    $router->post('/request', 'PlaylistController@addGameRequest');
    $router->get('/user/selected', 'PlaylistController@getUserSelected');
    $router->get('/settings/{type}', 'PlaylistSettingsController@select');
    $router->post('/filter-state/get', 'PlaylistController@getFilterState');
    $router->group([ 'middleware' => 'admin' ], function () use ($router) {
      $router->post('/category', 'PlaylistController@addCategory');
      $router->get('/{id_playlist}/full', 'PlaylistController@getFull');
      $router->post('/full', 'PlaylistController@selectFull');
      $router->put('/{id_playlist}', 'PlaylistController@update');
      $router->delete('/category/{id_category}', 'PlaylistController@deleteCategory');
      $router->group([ 'prefix' => '/settings' ], function () use ($router) {
        $router->get('/', 'PlaylistSettingsController@index');
        $router->delete('/{id_setting}', 'PlaylistSettingsController@delete');
        $router->post('/', 'PlaylistSettingsController@store');
        $router->put('/{id_setting}', 'PlaylistSettingsController@update');
        $router->post('/{id_setting}/set-image', 'PlaylistSettingsController@setImage');
      });
      $router->group([ 'prefix' => '/section' ], function () use ($router) {
        $router->get('/', 'PlaylistSectionController@index');
        $router->post('/', 'PlaylistSectionController@store');
        $router->put('/{id_section}', 'PlaylistSectionController@update');
        $router->delete('/{id_section}', 'PlaylistSectionController@delete');
      });
    });
    $router->group([ 'middleware' => 'parent' ], function () use ($router) {
      $router->post('/user/category', 'PlaylistController@setUserSections');
      $router->get('/category/sections/full', 'PlaylistController@getCategoryFull');
    });
    $router->get('/{id_playlist}', 'PlaylistController@get');
    $router->put('/{id_playlist}/activity/{type}', 'PlaylistController@addActivity');
    $router->post('/promo-apply', 'UserController@applyPromo');
  });


  // parent routes
  $router->group([ 'prefix' => '/parent' ], function () use ($router) {
    $router->post('/set-pass', 'ParentController@newParentCode');
    $router->post('/login', 'ParentController@parentLogin');
    $router->post('/has-pin', 'ParentController@hasPin');
    $router->put('/code/first', 'ParentController@sendFirstCode');
    $router->put('/code/second', 'ParentController@sendSecondCode');
    $router->get('/get-stats', 'PlaylistController@getCategoriesCounter');
    $router->group([ 'middleware' => 'parent' ], function () use ($router) {
      $router->post('/get-activity', 'PlaylistController@getLatestActivity');
      $router->post('/user/category', 'PlaylistController@setUserSections');
      $router->get('/category/sections/full', 'PlaylistController@getCategoryFull');
    });
  });


  // workshop routes
  $router->group([ 'prefix' => '/workshop' ], function () use ($router) {
    $router->get('/select', 'WorkshopController@select');
    $router->post('/', 'WorkshopController@store');
    $router->get('/{id_workshop}', 'WorkshopController@get');
    $router->post('/{id_workshop}/form', 'WorkshopController@form');
    $router->group([ 'middleware' => 'admin' ], function () use ($router) {
      $router->get('/', 'WorkshopController@index');
      $router->delete('/{id_workshop}', 'WorkshopController@delete');
      $router->post('/{id_workshop}/image', 'WorkshopController@setImage');
      $router->put('/{id_workshop}', 'WorkshopController@update');
    });
  });


  // funkit routes
  $router->group([ 'prefix' => '/funkit' ], function () use ($router) {
    $router->get('/select', 'FunkitController@select');
    $router->post('/', 'FunkitController@store');
    $router->get('/{id_funkit}', 'FunkitController@get');
    $router->post('/{id_funkit}/buy', 'FunkitController@buy');
    $router->group([ 'middleware' => 'admin' ], function () use ($router) {
      $router->get('/', 'FunkitController@index');
      $router->delete('/{id_funkit}', 'FunkitController@delete');
      $router->post('/{id_funkit}/image', 'FunkitController@setImage');
      $router->put('/{id_funkit}', 'FunkitController@update');
    });
  });


  // Events routes
  $router->group([ 'prefix' => '/event' ], function () use ($router) {
    $router->post('/', 'EventController@store');
    $router->get('/categories', 'EventController@getCategories');
    $router->get('/past', 'EventController@selectPastLast');
    $router->get('/{id_event}', 'EventController@get');
    $router->post('/select', 'EventController@select');
    $router->put('/{id_event}/join', 'EventController@join');
    $router->put('/{id_event}/cancel', 'EventController@cancel');
    $router->post('/{id_event}/conclusion', 'EventController@sendConclusion');
    $router->group([ 'middleware' => 'admin' ], function () use ($router) {
      $router->delete('/category/{id_category}', 'EventController@removeCategory');
      $router->post('/category', 'EventController@addCategory');
      $router->put('/category/{id_category}', 'EventController@updateCategory');
    });
  });


  $router->group([ 'prefix' => '/notification', 'middleware' => 'parent' ], function () use ($router) {
    $router->get('/', 'NotificationsController@get');
    $router->put('/', 'NotificationsController@update');
  });


  $router->group([ 'prefix' => '/subscribe' ], function () use ($router) {
    $router->get('/url/{type}', 'SubscribeController@getUrl');
    $router->post('/promo', 'SubscribeController@usePromo');
    $router->get('/ping/{id_transaction}', 'SubscribeController@pingTransaction');
  });


  $router->group([ 'prefix' => '/school' ], function () use ($router) {
    $router->get('/', 'SchoolController@select');
    $router->get('/prizers', 'SchoolController@getSchoolPrizers');
    $router->post('/search', 'SchoolController@search');
    $router->group([ 'middleware' => 'admin' ], function () use ($router) {
      $router->post('/', 'SchoolController@store');
      $router->put('/{id_school}', 'SchoolController@update');
      $router->delete('/{id_school}', 'SchoolController@delete');
    });
  });


  $router->group([ 'prefix' => '/admin', 'middleware' => 'admin' ], function () use ($router) {

    $router->group([ 'prefix' => '/variable' ], function () use ($router) {
      $router->put('/{id_variable}', 'Admin\VariableController@update');
      $router->get('/', 'Admin\VariableController@index');
    });

    $router->group([ 'prefix' => '/promo' ], function () use ($router) {
      $router->put('/{id_promo}', 'Admin\PromoController@update');
      $router->delete('/{id_promo}', 'Admin\PromoController@delete');
      $router->post('/', 'Admin\PromoController@store');
      $router->get('/', 'Admin\PromoController@index');
    });

    $router->put('/prices', 'Admin\PricesController@update');
    $router->get('/prices', 'Admin\PricesController@get');

    $router->group([ 'prefix' => '/user' ], function () use ($router) {
      $router->get('/', 'Admin\UserController@index');
      $router->put('/{id_user}', 'Admin\UserController@update');
      $router->get('/{id_user}', 'Admin\UserController@get');
      $router->put('/{id_user}/notifications', 'Admin\UserController@updateNotifications');
      $router->delete('/{id_user}', 'Admin\UserController@delete');
    });

    $router->group([ 'prefix' => '/parent' ], function () use ($router) {
      $router->put('/{id_user}/code', 'Admin\ParentController@sendPass');
      $router->get('/', 'Admin\ParentController@index');
    });

    $router->group([ 'prefix' => '/funzone' ], function () use ($router) {
      $router->put('/{id_user}', 'Admin\FunZoneController@update');
      $router->get('/', 'Admin\FunZoneController@index');
      $router->get('/{id_user}', 'Admin\FunZoneController@get');
      $router->delete('/{id_user}', 'Admin\FunZoneController@delete');
    });
  });

});
