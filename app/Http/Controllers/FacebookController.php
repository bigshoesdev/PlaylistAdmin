<?php

namespace App\Http\Controllers;

use InvalidArgumentException;
use RuntimeException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;


// the security hole - hacker can registrate infinite times :)
class FacebookController extends Controller
{


  private function verify($id_user, $token)
  {
    $res = @json_decode(file_get_contents("https://graph.facebook.com/$id_user/access_token=$token"), TRUE);
    return !isset($res['error']);
  }


  public function login($id_facebook, $token)
  {
    $user = app()->mdb->get('user', '*', [
      'id_facebook' => $id_facebook,
      'token' => $token,
    ]);

    if(empty($user)) {
      return false;
    } else {
      return $user;
    }
  }


  public function enter(Request $request)
  {

    $id_facebook = $request->input('id_facebook');

    if(app()->mdb->has('user', [
      'id_facebook' => $id_facebook,
    ])) {

      $user = app()->mdb->get('user', [
        'token',
        'id_facebook',
      ], [
        'id_facebook' => $id_facebook,
      ]);

      // if(!$this->verify($id_facebook, $access_token))
      //   throw new \InvalidArgumentException('Wrong access token', 401);

      return [
        'auth' => Crypt::encrypt(json_encode($user, JSON_UNESCAPED_UNICODE)),
      ];
    } else {
      $data = app()->mdb->action(function() use($request, $id_facebook) {

        $name = $request->input('name');
        $surname = $request->input('surname');
        $email = $request->input('email');
        // $gender = $request->input('gender');
        $access_token = $request->input('access_token');
        $img = $request->input('img');
        $date = time();

        // if(!$this->verify($id_facebook, $access_token))
        //   throw new \InvalidArgumentException('Wrong access token', 401);

        if(app()->mdb->has('user', [
          'email' => $email,
        ]))
          throw new \InvalidArgumentException(
            'החשבון עבור דוא"ל זה כבר רשום',
            401
          );

        $token = str_random(64);

        $start_games = app()->mdb->get('variable', 'value', [
          'name' => 'start_games',
        ]);
        
        app()->mdb->insert('user', [
          'name' => $name,
          'surname' => $surname,
          'email' => $email,
          // 'gender' => $gender,
          'date' => $date,
          'verified' => 1,
          'token' => $token,
          'id_facebook' => $id_facebook,
          'img' => $img,
          'full_img' => $img,
          'subscribe_total_games' => $start_games,
          'subscribe_games' => $start_games,
        ]);

        $id_user = app()->mdb->id();

        $categories = app()->mdb->select('category_playlist', [
          'id_category',
        ]);

        $categories = array_map(function ($n) use ($id_user) {
          $n['id_user'] = $id_user;
          return $n;
        }, $categories);

        app()->mdb->insert('user_category', $categories);

        app()->mdb->insert('user_notification', [
          'id_user' => $id_user
        ]);

        return [
          'auth' => Crypt::encrypt(json_encode([
            'token' => $token,
            'id_facebook' => $id_facebook,
          ], JSON_UNESCAPED_UNICODE)),
        ];

      });

      if(!empty($data)) {
        return $data;
      }
    }
  }
}
