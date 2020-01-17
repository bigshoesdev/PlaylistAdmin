<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Medoo\Medoo;
use Gregwar\Image\Image;
use Carbon\Carbon;

class PlaylistController extends Controller
{


  const PATH = './../storage/app/playlist/';
  const URL = '/storage/playlist/';


  public function store(Request $request)
  {
    if(empty($request->input('name'))) {
      throw new \InvalidArgumentException('השם אינו יכול להיות ריק');
    }

    app()->mdb->insert('playlist', [
      'name' => $request->input('name'),
      'edited' => time(),
      'date' => time(),
    ]);
  }


   public function addActivity(Request $request, $id_playlist, $type)
   {
     (new \App\Playlist)->writeActivity(
       $id_playlist,
       $request->attributes['user']['id_user'],
       $type
     );
   }


   public function delete($id_playlist)
   {
     app()->mdb->delete('playlist', [
       'id_playlist' => $id_playlist,
     ]);
   }


   public function updateCategories($id_playlist, $categories)
   {
     app()->mdb->delete('playlist_category', [
       'id_playlist' => $id_playlist,
     ]);
     foreach ($categories as $i => $category) {
       app()->mdb->insert('playlist_category', [
         'id_category' => $category,
         'id_playlist' => $id_playlist,
       ]);
     }
   }


   public function updateLocations($id_playlist, $locations)
   {
     app()->mdb->delete('playlist_location', [
       'id_playlist' => $id_playlist,
     ]);
     foreach ($locations as $i => $location) {
       app()->mdb->insert('playlist_location', [
         'id_location' => $location,
         'id_playlist' => $id_playlist,
       ]);
     }
   }


   public function addCategory(Request $request)
   {
     app()->mdb->insert('category_playlist', [
       'name' => $request->input('name'),
       'date' => time(),
     ]);
   }


   public function deleteCategory($id_category)
   {
     app()->mdb->delete('category_playlist', [
       'id_category' => $id_category,
     ]);
   }


   public function get(Request $request, $id_playlist)
   {
     $item = app()->mdb->get('playlist', '*', [
       'id_playlist' => $id_playlist,
     ]);

     $item['liked'] = $this->isLiked($request->attributes['user']['id_user'], $item['id_playlist']);

     $item['categories'] = app()->mdb->select('playlist_category', '*', [
       'id_playlist' => $id_playlist
     ]);

     return $item;
   }


   public function getFull(Request $request, $id_playlist)
   {
     $item = app()->mdb->get('playlist', '*', [
       'id_playlist' => $id_playlist,
     ]);

     $item['played'] = app()->mdb->count('playlist_counter', [
       'id_playlist' => $id_playlist,
       'type' => 'ans',
     ]);

     $item['liked'] = app()->mdb->count('playlist_counter', [
       'id_playlist' => $id_playlist,
       'type' => 'lik',
     ]);

     $item['skipped'] = app()->mdb->count('playlist_counter', [
       'id_playlist' => $id_playlist,
       'type' => 'skp',
     ]);

     $item['categories'] = app()->mdb->select('playlist_category', 'id_category', [
       'id_playlist' => $id_playlist
     ]);

     $item['locations'] = app()->mdb->select('playlist_location', 'id_location', [
       'id_playlist' => $id_playlist
     ]);

     if($item['id_author']) {
       $item['author_info'] = app()->mdb->get('user', [
         'id_user',
         'name',
         'surname',
       ], [
         'id_user' => $item['id_author']
       ]);
       $item['author_info']['name'] = $item['author_info']['name'] . ' ' . $item['author_info']['surname'];
       unset($item['author_info']['surname']);
     }


     if($item['id_school']) {
       $item['school_info'] = app()->mdb->get('school', [
         'id_school',
         'name',
         'city',
       ], [
         'id_school' => $item['id_school']
       ]);
     }


     $item['show'] = intval($item['show']);
     $item['additional'] = intval($item['additional']);

     return $item;
   }


   public function getCategoryFull(Request $request)
   {
     $sections = app()->mdb->select('category_playlist_section', '*');
     foreach ($sections as $i => $section) {
       $sections[$i]['categories'] = app()->mdb->select('category_playlist', '*', [
         'id_section' => $section['id_section'],
       ]);
     }
     return [
       'sections' => $sections,
       'selected' => app()->mdb->select('user_category', 'id_category', [
         'id_user' => $request->attributes['user']['id_user'],
       ]),
     ];
   }


   public function getUserSelected(Request $request) {
     return app()->mdb->select('user_category', [
       '[>]category_playlist' => 'id_category',
     ], '*', [
       'id_user' => $request->attributes['user']['id_user'],
     ]);
   }


   public function getCategorySections(Request $request)
   {
     return app()->mdb->select('category_playlist_section', '*');
   }


   public function getCategories(Request $request)
   {
     $categories = app()->mdb->select('category_playlist', '*', [
       'ORDER' => 'name'
     ]);

     foreach ($categories as $i => $category) {
       $ids = app()->mdb->select('playlist_category', 'id_playlist', [
         'id_category' => $category['id_category'],
       ]);

       $categories[$i]['count'] = count($ids);

       if($categories[$i]['count'] != 0) {
         $categories[$i]['played'] = app()->mdb->count('playlist_counter', [
           'id_playlist' => $ids,
           'type' => 'ans',
         ]);

         $categories[$i]['views'] = app()->mdb->count('playlist_counter', [
           'id_playlist' => $ids,
           'type' => [ 'ans', 'skp' ],
         ]);
       }
     }

     return $categories;
   }


   public function updateCategory(Request $request, $id_category)
   {
     app()->mdb->update('category_playlist', [
       'name' => $request->input('name'),
       'id_section' => $request->input('id_section'),
     ], [
       'id_category' => $id_category,
     ]);
   }


   public function update(Request $request, $id_playlist)
   {

     $input = $request->all();

     app()->mdb->action(function () use ($input, $id_playlist) {
       $this->updateCategories($id_playlist, $input['categories']);
       $this->updateLocations($id_playlist, $input['locations']);
       unset($input['categories']);
       unset($input['locations']);
       unset($input['id_category']);
       unset($input['liked']);
       unset($input['played']);
       unset($input['author_info']);
       unset($input['school_info']);
       unset($input['skipped']);
       $input['edited'] = time();
       app()->mdb->update('playlist', $input, [
         'id_playlist' => $id_playlist
       ]);
     });
   }


   public function setUserSections(Request $request)
   {
     app()->mdb->delete('user_category', [
       'id_user' => $request->attributes['user']['id_user'],
     ]);

     foreach ($request->input('categories') as $i => $category) {
       app()->mdb->insert('user_category', [
         'id_category' => $category,
         'id_user' => $request->attributes['user']['id_user'],
       ]);
     }


   }


   public function getRandom(Request $request)
   {
     $provider = new \App\User();

     $random = false;

     if(!$provider->hasSubscribe($request->attributes['user'])) {
       throw new \InvalidArgumentException('Subscribe ended', 402);
     }

     $query = [
       'ORDER' => 'order_index',
       'show' => true,
     ];

     if(!$request->has('id_playlist')) {
       $data = $request->all();

       // if(!empty($data['section']) && $data['section'] == 'family' && !empty($data['age_from']) && !empty($data['age_to'])) {
       //   $query['AND'] = [
       //     'min_age[<=]' => $data['age_from'],
       //     'max_age[>=]' => $data['age_to'],
       //   ];
       // }

       if(!empty($data['num_people'])) {
         $query['min_players[<=]'] = $data['num_people'];
         $query['max_players[>=]'] = $data['num_people'];
       }

       if(!empty($data['gifts'])) {
         $ids = app()->mdb->select('playlist_location', 'id_playlist', [
           'id_location' => $data['gifts'],
         ]);
         if(!empty($ids)) {
           $query['id_playlist'] = $ids;
         }
       }

       if(!empty($data['level'])) {
         $query['level'] = $data['level'];
       }

       if(isset($data['section']) && $data['section'] == 'child') {
         $gifts = app()->mdb->select('user_category', 'id_category', [
           'id_user' => $request->attributes['user']['id_user'],
         ]);
         if(!empty($gifts)) {
           $ids = app()->mdb->select('playlist_category', 'id_playlist', [
             'id_category' => $gifts,
           ]);
           if(!empty($ids)) {
             // тут array_intersect только если до этого искали по гифтам юзера, если еще добавишь то будет не верно работать
             if(isset($query['id_playlist'])) {
               $query['id_playlist'] = array_intersect($query['id_playlist'], $ids);
             } else {
               $query['id_playlist'] = $ids;
             }
           }
         }
       }
     }

     $rows = [
       'id_playlist',
       'question_1',
       'question_2',
       'question_3',
       'question_4',
       'question_5',
       'video',
       'date',
       'name',
       'additional[Bool]',
       'id_author',
       'id_school',
       'city',
       'level',
       'img',
       'audio',
       'section',
       'is_recommended',
       'teachers_guide',
     ];

     if(isset($query['id_playlist']) && empty($query['id_playlist'])) {
       unset($query['id_playlist']);
     }

     if($request->has('id_playlist')) {
       $item = app()->mdb->get('playlist', $rows, [
         'id_playlist' => $request->input('id_playlist')
       ]);
     } else {
       $count = app()->mdb->count('playlist', $query);

       if($count) {
         $index = $request->input('order_index') % $count;

         $item = app()->mdb->select('playlist', $rows, array_merge($query, [
           'LIMIT' => [ $index, 1 ],
         ]));

         $this->processFilterState($request, $index);
       }
     }

     if(empty($item)) {
       $random = true;
       $item = app()->mdb->get('playlist', $rows, [
         'ORDER' => Medoo::raw('RAND()')
       ]);
     } else {
       if(isset($item[0])) {
         $item = $item[0];
       }
     }

     if(!isset($count)) {
       $count = 0;
     }

     $item['liked'] = $this->isLiked($request->attributes['user']['id_user'], $item['id_playlist']);
     $item['categories'] = app()->mdb->select('playlist_category', [
       '[>]category_playlist' => 'id_category'
     ], [
       'name',
       'img',
       'id_category',
     ], [
       'id_playlist' => $item['id_playlist'],
     ]);

     if(!empty($item['id_author'])) {
       $item['author'] = app()->mdb->get('user', [
         'age',
         'name',
         'surname',
         'id_user',
       ], [
         'id_user' => $item['id_author']
       ]);
     }

     if(!empty($item['id_school'])) {
       $item['school'] = app()->mdb->get('school', '*', [
         'id_school' => $item['id_school']
       ]);
     }

     if(!$provider->hasSubscribeTime($request->attributes['user'])) {
       app()->mdb->update('user', [
         'subscribe_games[-]' => 1,
       ], [
         'id_user' => $request->attributes['user']['id_user'],
       ]);
     }

     $types = ['fml' => 'family', 'chl' => 'child', 'adl' => 'adult'];
     $condition = isset($types[$item['section']]) ? ['type' => $types[$item['section']]] : [];
     $giftsList = app()->mdb->get('setting_playlist', '*', $condition);
     $item['gifts'] = $giftsList;

     return [
       'result' => $item,
       'meta' => [
         'random' => $random,
         'count' => $count,
         'query' => $query,
       ],
     ];
   }


   public function search(Request $request)
   {

     $per_page = 12;

     $str = $request->input('str');
     $page = $request->input('page');

     $where = [ 'LIMIT' => [ $page * $per_page, $per_page ] ];

     if(!empty($str)) {
       $where['name[~]'] = "%$str%";
     }

     $result = app()->mdb->select('playlist', '*', $where);

     return [
       'result' => $result,
       'ended' => count($result) < $per_page,
     ];
   }


   public function getPrizers(Request $request)
   {

     $query = $request->input('query');
     $page = $request->input('page');

     $per_page = 12;
     $from = $per_page * $page;

     ob_start();
     app()->mdb->debug()->select('playlist_counter', [
       '[>]user' => 'id_user',
     ], [
       'id_user',
       'name',
       'surname',
       'age',
       'points' => Medoo::raw('COUNT(id_user)'),
       'user.date',
     ], [
       'name[~]' => "%$query%",
       'type' => 'ans',
       // 'LIMIT' => [ $page * $per_page, $per_page ],
       'ORDER' => 'points',
       'GROUP' => 'id_user',
     ]);
     $table = ob_get_contents();
     ob_end_clean();

     // Убрать лишние данные
     $result = app()->mdb->query("
       SELECT * FROM ($table) as n
       WHERE points >= 25
       LIMIT $per_page
       OFFSET $from
     ")->fetchAll();

     $result = array_map(function ($n) {
       $n['had_donate'] = app()->mdb->has('playlist', [
         'id_author' => $n['id_user'],
       ]);
       return $n;
     }, $result);

     return [
       'result' => $result,
       'ended' => count($result) < $per_page,
     ];
   }


   public function addGameRequest(Request $request)
   {
     // Не будет работать! Без 'name'
     app()->mdb->insert('playlist_request', [
       // 'question_1' => $request->input('question_1'),
       // 'question_2' => $request->input('question_2'),
       // 'question_3' => $request->input('question_3'),
       // 'question_4' => $request->input('question_4'),
       // 'question_5' => $request->input('question_5'),
       // 'name' => $request->input('name'),
       // 'comment' => $request->input('comment'),
       'id_user' => $request->attributes['user']['id_user'],
       'date' => time(),
     ]);

     $school = 'Unknown';
     if($request->input('id_school')) {
       $school = app()->mdb->get('school', '*', [
         'id_school' => $request->input('id_school'),
       ]);
       $school = $school['name'] . ', ' . $school['city'];
     }

     $credit = $request->input('credit') ? 'yes' : 'no';

     mail(env('APP_RELEASE_EMAIL'), 'New PLAYLIST requested idea.', "
       From: {$request->attributes['user']['name']} {$request->attributes['user']['surname']}, \n
       Wants credit: $credit \n
       School: {$school}, \n
       City: {$request->input('city')}, \n
       Question 1: {$request->input('question_1')}, \n
       Question 2: {$request->input('question_2')}, \n
       Question 3: {$request->input('question_3')}, \n
       Comment: {$request->input('comment')}
     ");
   }


   // public function index($query)
   // {
     // if(empty($query)) {
     //   return app()->mdb->select('playlist', '*', [
     //     'LIMIT' => 64,
     //   ]);
     // } else {
     //   return app()->mdb->select('playlist', '*', [
     //     'name[~]' => "%$query%",
     //     'LIMIT' => 64,
     //   ]);
     // }
   // }


   public function selectFull(Request $request)
   {

     $data = $request->input('query');

     $query = $data['str'];
     $gifts = $data['categories'];
     $locations = $data['locations'];
     $level = $data['level'];

     $where = [
       'name[~]' => "%$query%",
       'ORDER' => [
         'order_index' => 'ASC',
         'date' => 'DESC'
       ],
     ];

     if(!empty($gifts)) {
       $where['id_playlist'] = app()->mdb->select('playlist_category', 'id_playlist', [
         'id_category' => $gifts,
       ]);
     }

     if(!empty($level)) {
       $where['level'] = $level;
     }

     if(!empty($locations)) {
       $tmp = app()->mdb->select('playlist_location', 'id_playlist', [
         'id_location' => $locations,
       ]);

       if(isset($where['id_playlist'])) {
         $where['id_playlist'] = array_merge($where['id_playlist'], $tmp);
       } else {
         $where['id_playlist'] = $tmp;
       }
     }

     $items = app()->mdb->select('playlist', [
       'id_playlist',
       'date[Int]',
       'name',
       'show[Bool]',
       'order_index[Int]',
     ], $where);

     foreach ($items as $i => $item) {
       $items[$i]['played'] = app()->mdb->count('playlist_counter', [
         'id_playlist' => $item['id_playlist'],
         'type' => 'ans',
       ]);
       $items[$i]['skipped'] = app()->mdb->count('playlist_counter', [
         'id_playlist' => $item['id_playlist'],
         'type' => 'skp',
       ]);
       $items[$i]['opened_ext'] = app()->mdb->count('playlist_counter', [
         'id_playlist' => $item['id_playlist'],
         'type' => 'ext',
       ]);
       $items[$i]['opened_video'] = app()->mdb->count('playlist_counter', [
         'id_playlist' => $item['id_playlist'],
         'type' => 'vid',
       ]);
       $items[$i]['liked'] = app()->mdb->count('playlist_counter', [
         'id_playlist' => $item['id_playlist'],
         'type' => 'lik',
       ]);
     }

     return $items;
   }


   public function getCategoriesCounter(Request $request)
   {
     $categories = app()->mdb->select('category_playlist', '*');

     foreach ($categories as $i => $category) {

       $ids = app()->mdb->select('playlist_category', 'id_playlist', [
         'id_category' => $category['id_category']
       ]);

       $categories[$i]['count'] = app()->mdb->count('playlist_counter', [
         'id_playlist' => $ids,
         'id_user' => $request->attributes['user']['id_user'],
         'type' => 'ans',
       ]);
     }

     return array_values($categories);
   }


   public function like(Request $request, $id_playlist)
   {
     if(app()->mdb->has('playlist_like', [
       'id_user' => $request->attributes['user']['id_user'],
       'id_playlist' => $id_playlist,
     ])) {
       app()->mdb->delete('playlist_like', [
         'id_user' => $request->attributes['user']['id_user'],
         'id_playlist' => $id_playlist,
       ]);
     } else {
       app()->mdb->insert('playlist_like', [
         'id_user' => $request->attributes['user']['id_user'],
         'id_playlist' => $id_playlist,
       ]);
     }
   }


   public function disLike(Request $request, $id_playlist)
   {
     app()->mdb->delete('playlist_like', [
       'id_user' => $request->attributes['user']['id_user'],
       'id_playlist' => $id_playlist,
     ]);
   }


   public function getRequestsStats(Request $request)
   {
     $users = app()->mdb->select('playlist_request', [
       '[>]user' => 'id_user',
     ], [
       'requests_count' => Medoo::raw('COUNT(id_user)'),
       'id_user',
       'user.name',
       'user.img',
     ], [
       'GROUP' => 'id_user',
       'ORDER' => [ 'requests_count' => 'DESC' ],
       'LIMIT' => 10,
     ]);

     foreach ($users as $i => $user) {
       $tmp = app()->mdb->select('playlist', 'id_playlist', [
         'id_author' => $user['id_user'],
       ]);

       if(empty($tmp)) {
         $users[$i]['saw'] = 0;
         continue;
       }

       $users[$i]['saw'] = app()->mdb->count('playlist_counter', [
         'id_playlist' => $tmp,
       ]);

     }

     // SAWED
     $tmp = app()->mdb->select('playlist', 'id_playlist', [
       'id_author' => $request->attributes['user']['id_user'],
     ]);
     if(empty($tmp)) {
       $saw = 0;
     } else {
       $saw = app()->mdb->count('playlist_counter', [
         'id_playlist' => $tmp,
       ]);
     }

     return [
       'users' => $users,
       'personal' => [
         'sent' => app()->mdb->count('playlist_request', [
           'id_user' => $request->attributes['user']['id_user'],
         ]),
         'verified' => app()->mdb->count('playlist', [
           'id_author' => $request->attributes['user']['id_user'],
         ]),
         'saw' => $saw,
       ]
     ];
   }


   public function isLiked($id_user, $id_playlist)
   {
     return app()->mdb->has('playlist_like', [
       'id_user' => $id_user,
       'id_playlist' => $id_playlist,
     ]);
   }


   public function getLiked(Request $request)
   {
     return app()->mdb->select('playlist_like', [
       '[>]playlist' => 'id_playlist'
     ], '*', [
       'id_user' => $request->attributes['user']['id_user']
     ]);
   }


   public function setCategorySVG(Request $request, $id_category)
   {
     if (!$request->hasFile('file')) throw new \Exception("Error Processing Request", 1);

     $category = app()->mdb->get('category_playlist', '*', [
       'id_category' => $id_category,
     ]);

     if(!$category) {
       throw new \InvalidArgumentException("No category $category found", 404);
     }

     app()->mdb->action(function () use ($id_category, $category, $request) {
       $tmp_name = str_random(6);
       app()->mdb->update('category_playlist', [
         'img_url' => $_ENV['APP_URL'] . '/storage/playlist/categories/' . $tmp_name,
         'img' => $tmp_name,
       ], [
         'id_category' => $id_category,
       ]);

       $file = $request->file('file');
       $file->move('./../storage/app/playlist/categories', $tmp_name);

       $prev = './../storage/app/playlist/categories/' . $category['img'];

       if(file_exists($prev) && !is_dir($prev)) {
         unlink($prev);
       }
     });
   }


   public function getLatestActivity(Request $request)
   {

     $items = app()->mdb->select('playlist_counter', [
       '[>]playlist' => 'id_playlist'
     ], [
       'id_playlist',
       'playlist_counter.date',
       'name',
     ], [
       'id_user' => $request->attributes['user']['id_user'],
       'playlist_counter.date[<>]' => [ strtotime($request->input('from')), strtotime($request->input('to')) ],
       'type' => 'ans',
       'ORDER' => [ 'playlist_counter.date' => 'DESC' ],
       'LIMIT' => 32,
     ]);

     foreach ($items as $i => $item) {
       $items[$i]['categories'] = app()->mdb->select('playlist_category', [
         '[>]category_playlist' => 'id_category'
       ], '*', [
         'id_playlist' => $item['id_playlist'],
       ]);
       $items[$i]['liked'] = $this->isLiked($request->attributes['user']['id_user'], $item['id_playlist']);
     }

     return $items;
   }


   public function getUserPoints(Request $request)
   {
     return [
       'points' => app()->mdb->count('playlist_counter', '*', [
         'id_user' => $request->attributes['user']['id_user'],
         'type' => 'ans',
       ])
     ];
   }

   public function setAudio(Request $request, $id_playlist)
   {
       try {
           $uniqueId = uniqid();
           $file = $request->file('audio');
           $originalName = $file->getClientOriginalName();
           $size = $file->getSize();
           $extension = $file->getClientOriginalExtension();
           $filename = Carbon::now()->format('Ymd') . '_' . $uniqueId . '.' . $extension;
           $audioPath = url('http://3.90.154.66/uploads/audio/' . $filename);
           $path = 'uploads/audio/';
           $file->move($path, $filename);

           app()->mdb->update('playlist', [
               'audio' => $audioPath,
           ], [
               'id_playlist' => $id_playlist
           ]);
           return [
               'url' => $audioPath,
           ];
       } catch(Exception $e) {
           return ['error' => $e->getMessage()];
       }
   }

   public function removeAudio(Request $request, $id_playlist)
   {
     try {
       app()->mdb->update('playlist', [
         'audio' => null,
       ], [
         'id_playlist' => $id_playlist
       ]);
       return ['success' => true];
     } catch(Exception $e) {
       return ['error' => $e->getMessage()];
     }
   }

   public function setImage(Request $request, $id_playlist)
   {
     $name = str_random(16);
     $file = Image::open($request->file('file')->getPathName());

     $prev = app()->mdb->get('playlist', 'img', [
       'id_playlist' => $id_playlist,
     ]);

     if(!empty($prev)) {
       $_ = explode('/', $prev);
       $tmp_path = self::PATH . end($_);
       if(file_exists($tmp_path) && is_file($tmp_path)) {
         unlink($tmp_path);
       }
     }

     $file
       ->fill(0xffffff)
       ->cropResize(128, 128)
       ->save(self::PATH . $name, 'png');

     app()->mdb->update('playlist', [
       'img' => $_ENV['APP_URL'] . self::URL . $name,
     ], [
       'id_playlist' => $id_playlist
     ]);

     return [
       'url' => $_ENV['APP_URL'] . self::URL . $name
     ];
   }


   public function getFilterState(Request $request)
   {
     $filter = $request->all();

     if(isset($filter['gifts'])) {
       sort($filter['gifts'], SORT_NUMERIC);
     }

     if(isset($filter['id_playlist'])) {
       unset($filter['id_playlist']);
     }

     if(isset($filter['order_index'])) {
       unset($filter['order_index']);
     }

     return app()->mdb->get('playlist_state', '*', [
       'id_user' => $request->attributes['user']['id_user'],
       'filter' => json_encode($filter, JSON_UNESCAPED_UNICODE),
     ]);
   }


   private function processFilterState(Request $request, $index)
   {
     $filter = $request->all();

     if(isset($filter['id_playlist'])) {
       unset($filter['id_playlist']);
     }

     if(isset($filter['order_index'])) {
       unset($filter['order_index']);
     }

     if(isset($filter['gifts'])) {
       sort($filter['gifts'], SORT_NUMERIC);
     }

     $filter = json_encode($filter, JSON_UNESCAPED_UNICODE);

     if(!app()->mdb->has('playlist_state', [
       'id_user' => $request->attributes['user']['id_user'],
       'filter' => $filter,
     ])) {
       app()->mdb->insert('playlist_state', [
         'order_index' => $index,
         'id_user' => $request->attributes['user']['id_user'],
         'filter' => $filter,
       ]);
     } else {
       app()->mdb->update('playlist_state', [
         'order_index' => $index,
       ], [
         'id_user' => $request->attributes['user']['id_user'],
         'filter' => $filter,
       ]);
     }
   }
}
