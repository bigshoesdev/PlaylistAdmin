<?php

namespace App\Http\Controllers;

use App\Mailer;
use InvalidArgumentException;
use RuntimeException;
use Gregwar\Image\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Twilio\Rest\Client;
class UserController extends Controller
{

    const PHONE_PREFIX = '972';
    private $per_page = 12;


    public function index (Request $request)
    {
        return app()->mdb->select('user', '*');
    }


    public function get ($id_user)
    {
        return app()->mdb->get('user', '*', [
            'id_user' => $id_user
        ]);
    }


    public function delete ($id_user)
    {
      app()->mdb->delete('user', [
          'id_user' => $id_user
      ]);
    }


    private function validateMail()
    {

    }


    private function validatePass($pass)
    {

        if(mb_strlen($pass) < 6)
            throw new InvalidArgumentException('אורך הסיסמה חייב להיות יותר מ -6 תווים');
        if(mb_strlen($pass) > 32)
            throw new InvalidArgumentException('על הסיסמה להיות קטנה מ -32 תווים');
        if(preg_match("/'\{\}\[\]\(\)\`\"/", $pass))
            throw new InvalidArgumentException('תווים מסוימים אינם חוקיים');
    }


    public function getPlayedCount(Request $request)
    {

      app()->mdb->count('playlist_counter', [
        'type' => 'ans',
        'id_user' => $request->attributes['user']['id_user'],
      ]);
    }


    private function validateLogin($login)
    {
      if(preg_match("/'\{\}\[\]\(\)\`\"/", $login))
        throw new InvalidArgumentException('תווים מסוימים אינם חוקיים');
      if(mb_strlen($login) < 4)
        throw new InvalidArgumentException('אורך ההתחברות חייב להיות יותר מ -4 תווים');
      if(mb_strlen($login) > 32)
        throw new InvalidArgumentException('אורך ההתחברות חייב להיות קטן מ -32 תווים');
    }


    public function saveProfile(Request $request)
    {
      $data = [
        'name' => $request->input('name'),
        'surname' => $request->input('surname'),
        'age' => $request->input('age'),
        'gender' => $request->input('gender'),
        'email' => $request->input('email'),
        'phone' => $request->input('phone'),
        'pass' => $request->input('pass'),
      ];

      if(isset($data['name'])) {

        if(empty($data['name']))
          throw new InvalidArgumentException('הזן את שמך המלא');
      }

      if(isset($data['email'])) {
        if(!preg_match("/^([a-z0-9_\.\-]){1,}@([a-z0-9\.\-]){1,}\.([a-z0-9\.\-]){1,}$/is", $data['email']))
          throw new InvalidArgumentException('תבנית דואר לא חוקית');

        if(app()->mdb->has('user', [
          'email' => $data['email'],
          'id_user[!]' => $request->attributes['user']['id_user']
        ]))
          throw new InvalidArgumentException('פוסט זה כבר תפוס');
      }

      if(!empty($data['pass'])) {
        $non_hashed_pass = $data['pass'];
        $data['pass'] = $this->hashPassword($data['pass']);
      } else {
        unset($data['pass']);
      }

      app()->mdb->update('user', $data, [
        'id_user' => $request->attributes['user']['id_user']
      ]);

      if(!empty($non_hashed_pass))
        return [
          'auth' => Crypt::encrypt(json_encode([
            'id_user' => $request->attributes['user']['id_user'],
            'pass' => $non_hashed_pass,
          ], JSON_UNESCAPED_UNICODE)),
        ];
    }


    public function recoverPass(Request $request) {

        $email = $request->input('email');

        $id_user = app()->mdb->get('user', 'id_user', [
          'email[~]' => $email,
          'id_facebook' => null,
        ]);

        if(empty($id_user))
          throw new InvalidArgumentException('המשתמש לא נמצא');

        $code = str_random(8);

        app()->mdb->insert('user_recover', [
          'id_user' => $id_user,
          'code' => $code,
          'date' => time()
        ]);

        $subject = "Playlist password recovery.";
        mail($email, $subject, "Your code: $code");
    }


  public function checkRecoverCode(Request $request, $code) {

    $id_user = app()->mdb->get('user_recover', 'id_user', [
      'code' => $code
    ]);

    if(empty($id_user))
      throw new InvalidArgumentException('מפתח לא חוקי');
  }


  public function changePassKey(Request $request) {

    $pass = $request->input('pass');
    $confirm = $request->input('confirm');
    $code = $request->input('code');

    $id_user = app()->mdb->get('user_recover', 'id_user', [
      'code' => $code
    ]);

    if(empty($id_user))
      throw new InvalidArgumentException('מפתח לא חוקי');

    $this->validatePass($pass);

    if($confirm != $pass)
      throw new InvalidArgumentException('הקלד מחדש את הסיסמה כראוי');
    if(empty($confirm))
      throw new InvalidArgumentException('חזור על הסיסמה');

    app()->mdb->update('user', [
      'pass' => $this->hashPassword($pass)
    ], [
      'id_user' => $id_user
    ]);

    app()->mdb->delete('user_recover', [
      'id_user' => $id_user
    ]);
  }


    public function registrate(Request $request)
    {

        $email = $request->input('email');
        $name = trim($request->input('name'));
        $surname = trim($request->input('surname'));
        $pass = $request->input('pass');
        $gender = empty($request->input('gender')) ? 'man' : $request->input('gender');
        $age = $request->input('age');
        $date = time();
        $confirm = $request->input('confirm');

        // if(preg_match('/[^\w\ ]/', $name))
        //     throw new InvalidArgumentException('השם אינו יכול לכלול תווים מיוחדים');

        if(!preg_match("/^([a-z0-9_\.\-]){1,}@([a-z0-9\.\-]){1,}\.([a-z0-9\.\-]){1,}$/is", $email))
            throw new InvalidArgumentException('תבנית דואר לא חוקית');
        if(empty($name))
            throw new InvalidArgumentException('הזן את שמך המלא');

        if(app()->mdb->has('user', [
            'email' => $email
        ]))
            throw new InvalidArgumentException('מייל זה כבר תפוס');


        $this->validatePass($pass);
        // $this->validateLogin($login);

        // if($confirm != $pass)
        //     throw new InvalidArgumentException("Повторите пароль верно.");
        // if(empty($confirm))
        //     throw new InvalidArgumentException("Повторите пароль.");

        return app()->mdb->action(function() use($age, $gender, $surname, $pass, $email, $date, $name) {

          $start_games = app()->mdb->get('variable', 'value', [
            'name' => 'start_games',
          ]);

            app()->mdb->insert('user', [
                'name' => $name,
                'surname' => $surname,
                'email' => $email,
                'gender' => $gender,
                'pass' => $this->hashPassword($pass),
                'date' => $date,
                'age' => $age,
                'role' => 'usr',
                'verified' => 0,
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

            $code = str_random(8);
            app()->mdb->insert('user_verify', [
                'code' => $code,
                'id_user' => $id_user
            ]);

            app()->mdb->insert('user_notification', [
                'id_user' => $id_user
            ]);

            // $subject = "Playlist registration.";
            // if(!Mailer::send($email, $subject, [
            //     'link' => $_ENV['APP_URL'] . "/user/verify/$code",
            // ])) {
            //     throw new RuntimeException('Error sending mail.');
            // }

            return [
              'auth' => Crypt::encrypt(json_encode([
                'email' => $email,
                'pass' => $pass,
              ], JSON_UNESCAPED_UNICODE)),
            ];

        });
    }


    public function verify($code)
    {

        $id_user = app()->mdb->get('user_verify', 'id_user', [
            'code' => $code
        ]);

        app()->mdb->update('user', [
            'verified' => 1
        ], [
            'id_user' => $id_user
        ]);

        app()->mdb->delete('user_verify', [
            'id_user' => $id_user
        ]);

        echo "OKAY";
    }


    public function stayLogged(Request $request)
    {
      $email = $request->input('email');
      $pass = $request->input('pass');

      $user = app()->mdb->get('user', '*', [
        'email[~]' => $email,
      ]);

      if(!Hash::check($pass, $user['pass']))
        throw new InvalidArgumentException('כתובת או סיסמה שגויות');

      return [
        'auth' => Crypt::encrypt(json_encode([
          'id_user' => $user['id_user'],
          'pass' => $pass,
        ], JSON_UNESCAPED_UNICODE)),
      ];
    }


    public function hashPassword($pass)
    {
        return Hash::make($pass);
    }


    public function getUserInfo($id_user)
    {
      $user = app()->mdb->get('user', '*', [
        'id_user' => $id_user
      ]);

      if(!empty($user)) {
        return (new \App\User())->getSafe($user);
      } else {
        throw new \InvalidArgumentException(
          'המשתמש לא נמצא',
          404
        );
      }
    }


    public function getMyInfo(Request $request)
    {
      $data = (new \App\User())->getSafe($request->attributes['user']);
      $data['pin_exists'] = app()->mdb->has('parent_code', [
        'id_user' => $request->attributes['user']['id_user'],
      ]);
      $data['games_summary'] = app()->mdb->count('playlist_counter', '*', [
        'id_user' => $request->attributes['user']['id_user'],
        'type' => 'ans',
      ]);
      $data['gifts'] = app()->mdb->select('user_category', 'id_category', [
        'id_user' => $request->attributes['user']['id_user'],
      ]);

      return $data;
    }


    public function setImage(Request $request)
    {
        $file = Image::open($request->file('image')->getPathName());
        $min_file = Image::open($request->file('image')->getPathName());
        $full_name = str_random(16) . '.jpg';
        $min_name = str_random(16) . '.jpg';
        $path = './../storage/app/user/' . $request->attributes['user']['id_user'] . '/';
        $url = $_ENV['APP_URL'] . '/storage/user/' . $request->attributes['user']['id_user'] . '/';

        $images = app()->mdb->get('user', [
          'img', 'full_img'
        ], [
          'id_user' => $request->attributes['user']['id_user']
        ]);

        if(!empty($images['img']) && !empty($images['full_img'])) {
          $images['img'] = explode('/', $images['img']);
          $images['full_img'] = explode('/', $images['full_img']);

          $tmp_name = $path . end($images['img']);

          if(file_exists($tmp_name) && is_file($tmp_name))
            unlink($tmp_name);

          $tmp_name = $path . end($images['full_img']);

          if(file_exists($tmp_name) && is_file($tmp_name))
            unlink($tmp_name);
        }

        $min_file
          ->fill(0xffffff)
          ->cropResize(192, 192)
          ->save($path . $min_name, 'jpg');

        $file
          ->fill(0xffffff)
          ->cropResize(512, 512)
          ->save($path . $full_name, 'jpg');

        app()->mdb->update('user', [
          'img' => $url . $min_name,
          'full_img' => $url . $full_name,
        ], [
          'id_user' => $request->attributes['user']['id_user']
        ]);

        return [
          'url' => $url . $min_name,
          'full_img' => $url . $full_name,
        ];

    }


    public function login($id_user, $pass)
    {
      $user = app()->mdb->get('user', '*', [
        'id_user' => $id_user,
      ]);

      if (!empty($user) && Hash::check($pass, $user['pass'])) {
        return $user;
      } elseif (!empty($user) && $pass === $user['pass']) {
        return $user;
      } else {
        return false;
      }
    }

    public function loginByPhone(Request $request)
    {
      $phone = $request->input('phone');
      $user = app()->mdb->get('user', '*', ['phone' => $phone]);
      if ($user) {
        $twilio = new Client('AC52d1c621e01b5be0fb557292f85d4b0e', 'c09728942758a68fbc0d41a219505c45');
        $code = rand(100000, 999999);
        $twilio->messages->create($phone, [
          'from' => '+15598236091',
          'body' => $code,
        ]);
        return ['code' => $code, 'hash' => Crypt::encrypt($code)];
      } else {
        return ['error' => 'User not registered'];
      }
    }

    public function checkLoginByPhone(Request $request)
    {
      $phone = $request->input('phone');
      $hash = $request->input('hash');
      $code = $request->input('code');

      if (Crypt::decrypt($hash) != $code) {
        throw new RuntimeException('Code incorrect.');
        return;
      }

      $user = app()->mdb->get('user', '*', [
        'phone' => $phone,
      ]);

      if ($user) {
        return [
          'auth' => Crypt::encrypt(json_encode([
            'id_user' => $user['id_user'],
            'pass' => $user['pass'],
          ], JSON_UNESCAPED_UNICODE)),
        ];
      } else {
        throw new RuntimeException('User not found.');
        return;
      }
    }


    public function searchUser(Request $request)
    {
      return app()->mdb->select('user', [
        'id_user',
        'name',
        'surname',
        'email',
      ], [
        'OR' => [
          'name[~]' => "%{$request->input('name')}%",
          'surname[~]' => "%{$request->input('name')}%",
        ]
      ]);
    }
      public function registrateByPhone(Request $request)
    {
      $phone = $request->input('phone');
      if (preg_match('/^0([0-9]{9})$/',$phone ,$m)) {
          $phone = sprintf('+%s%s', self::PHONE_PREFIX, $m[1]);
          $code = rand(100000, 999999);
          $twilio = new Client('AC52d1c621e01b5be0fb557292f85d4b0e', 'c09728942758a68fbc0d41a219505c45');

          $twilio->messages->create($phone, [
              'from' => '+15598236091',
              'body' => $code,
          ]);
          return ['code' => $code, 'hash' => Crypt::encrypt($code)];
      } else {
          return ['error' => 'Bad phone number'];
      }
    }

    public function confirmByPhone(Request $request)
    {
      $phone = $request->input('phone');
      $hash = $request->input('hash');
      $code = $request->input('code');

      if (Crypt::decrypt($hash) != $code) {
        throw new RuntimeException('Code incorrect.');
        return;
      }

      $user = app()->mdb->get('user', '*', [
        'phone' => $phone,
      ]);

      if (!$user) {
        $start_games = app()->mdb->get('variable', 'value', [
          'name' => 'start_games',
        ]);

        app()->mdb->insert('user', [
            'phone' => $phone,
            'pass' => $this->hashPassword(str_random(8)),
            'role' => 'usr',
            'verified' => 1,
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

        $user = app()->mdb->get('user', '*', [
          'id_user' => $id_user,
        ]);
      }

      return [
        'auth' => Crypt::encrypt(json_encode([
          'id_user' => $user['id_user'],
          'pass' => $user['pass'],
        ], JSON_UNESCAPED_UNICODE)),
      ];
    }

    public function applyPromo(Request $request)
    {
        try {
            $code = $request->code;
            $promo = app()->mdb->get('promo', '*', [
                'value' => $code
            ]);
            if ($promo) {
                $curr = time();
                $data = [
                    'subscribe' => $curr,
                ];
                if ($promo['type'] == 'six') {
                    $data['subscribe_interval'] = strtotime('+3 month', $curr);
                } else {
                    $data['subscribe_interval'] = strtotime('+1 month', $curr);
                }
                app()->mdb->update('user', $data, [
                    'id_user' => intval($request->attributes['user']['id_user'])
                ]);
                return ['message' => 'Promo code applied'];
            }
            return ['message' => 'Promo code is invalid'];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
