<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Medoo\Medoo;

class ShootlistController extends Controller
{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
   public function store(Request $request)
   {
     app()->mdb->insert('shootlist', [
       'content_fem' => $request->input('content_fem'),
       'content_man' => $request->input('content_man'),
       'answers' => '{"right": 1,"first": "","second": "","third": ""}',
       'date' => time(),
     ]);
   }


   public function getRandom(Request $request)
   {

     $where = [
       'show' => true,
       'ORDER' => 'order_index',
     ];

     $data = $request->all();

     if(!empty($request->input('gifts'))) {
       $where['category'] = $request->input('gifts');
     }

     if(!empty($request->input('for_type'))) {
       $where['for_type'] = $request->input('for_type');
     }

     $count = app()->mdb->count('shootlist', $where);

     $select = [
       'date',
       'content_fem',
       'content_man',
       'category[Int]',
       'id_shootlist',
       'answers[JSON]',
       'city',
       'for_type',
     ];

     if($count == 0) {
       unset($where['for_type']);
       $count = app()->mdb->count('shootlist', $where);
     }

     $where = array_merge($where, [
       'LIMIT' => [ $request->input('order_index'), 1 ],
     ]);

     if(!empty($data['id_shootlist'])) {
       $item = app()->mdb->get('shootlist', $select, [
         'id_shootlist' => $data['id_shootlist'],
       ]);
     } else {
       $index = $request->input('order_index') % $count;
       $item = app()->mdb->select('shootlist', $select, $where)[0];
       $item['liked'] = $this->isLiked($request->attributes['user']['id_user'], $item['id_shootlist']);
       $this->processFilterState($request, $index);
     }

     // return array_merge($where, [
     //   'LIMIT' => [ $index, 1 ],
     // ]);

     if(!empty($item['id_author'])) {
       $item['author'] = app()->mdb->get('user', [
         'age',
         'name',
         'id_user',
       ], [
         'id_user' => $item['id_author']
       ]);
     }

     return [
       'result' => $item,
       'meta' => [
         'where' => $where,
       ],
     ];
   }


  public function selectFull(Request $request)
  {

    $query = $request->input('query');
    $category = $request->input('category');

    $where = [
      'OR' => [
        'content_fem[~]' => "%$query%",
        'content_man[~]' => "%$query%",
      ],
      'ORDER' => [
        // 'order_index' => 'ASC',
        'date' => 'DESC'
      ],
    ];

    if($category) {
      $where['category'] = $category;
    }

    $items = app()->mdb->select('shootlist', [
      'date',
      'content_fem',
      'content_man',
      'show[Bool]',
      'id_shootlist',
      'order_index[Int]',
    ], $where);

    foreach ($items as $i => $item) {
      $items[$i]['played'] = app()->mdb->count('shootlist_counter', [
        'id_shootlist' => $item['id_shootlist'],
        'type' => 'ans',
      ]);
      $items[$i]['skipped'] = app()->mdb->count('shootlist_counter', [
        'id_shootlist' => $item['id_shootlist'],
        'type' => 'skp',
      ]);
      $items[$i]['liked'] = app()->mdb->count('shootlist_counter', [
        'id_shootlist' => $item['id_shootlist'],
        'type' => 'lik',
      ]);
    }

    return $items;
  }


   public function index(Request $request)
   {
     $query = $request->input('query');
     return app()->mdb->select('shootlist', '*', [
       'OR' => [
         'content_fem[~]' => "%$query%",
         'content_man[~]' => "%$query%",
       ]
     ]);
   }


   public function get($id_shootlist)
   {
     return app()->mdb->get('shootlist', [
       'date',
       'content_fem',
       'content_man',
       'category[Int]',
       'show[Bool]',
     ], [
       'id_shootlist' => $id_shootlist
     ]);
   }


   public function getFull($id_shootlist)
   {
     $item = app()->mdb->get('shootlist', [
       'date',
       'content_fem',
       'content_man',
       'category[Int]',
       'show[Bool]',
       'answers[JSON]',
       'id_author',
       'id_school',
       'city',
       'order_index[Int]',
       'for_type',
     ], [
       'id_shootlist' => $id_shootlist
     ]);
     $item['played'] = app()->mdb->count('shootlist_counter', [
       'id_shootlist' => $id_shootlist,
       'type' => 'ans',
     ]);
     $item['skipped'] = app()->mdb->count('shootlist_counter', [
       'id_shootlist' => $id_shootlist,
       'type' => 'skp',
     ]);
     $item['liked'] = app()->mdb->count('shootlist_counter', [
       'id_shootlist' => $id_shootlist,
       'type' => 'lik',
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

     return $item;
   }


   public function addActivity(Request $request, $id_shootlist, $type)
   {
     (new \App\Shootlist)->writeActivity(
       $id_shootlist,
       $request->attributes['user']['id_user'],
       $type
     );
   }


   public function delete($id_shootlist)
   {
     app()->mdb->delete('shootlist', [
       'id_shootlist' => $id_shootlist
     ]);
   }


   public function update(Request $request, $id_shootlist)
   {
     app()->mdb->update('shootlist', [
       'content_fem' => $request->input('content_fem'),
       'content_man' => $request->input('content_man'),
       'category' => $request->input('category'),
       'answers[JSON]' => $request->input('answers'),
       'show' => $request->input('show'),
       'id_author' => $request->input('id_author'),
       'order_index' => $request->input('order_index'),
       'id_school' => $request->input('id_school'),
       'city' => $request->input('city'),
       'for_type' => $request->input('for_type'),
     ], [
       'id_shootlist' => $id_shootlist
     ]);
   }


   public function like(Request $request, $id_shootlist)
   {
     if(app()->mdb->has('shootlist_like', [
       'id_user' => $request->attributes['user']['id_user'],
       'id_shootlist' => $id_shootlist,
     ])) {
       app()->mdb->delete('shootlist_like', [
         'id_user' => $request->attributes['user']['id_user'],
         'id_shootlist' => $id_shootlist,
       ]);
     } else {
       app()->mdb->insert('shootlist_like', [
         'id_user' => $request->attributes['user']['id_user'],
         'id_shootlist' => $id_shootlist,
       ]);
     }
   }


   public function disLike(Request $request, $id_shootlist)
   {
     app()->mdb->delete('shootlist_like', [
       'id_user' => $request->attributes['user']['id_user'],
       'id_shootlist' => $id_shootlist,
     ]);
   }


   public function isLiked($id_user, $id_shootlist)
   {
     return app()->mdb->has('shootlist_like', [
       'id_user' => $id_user,
       'id_shootlist' => $id_shootlist,
     ]);
   }


  public function addRequest(Request $request)
  {
    app()->mdb->insert('shootlist_request', [
      'question' => $request->input('question'),
      'id_user' => $request->attributes['user']['id_user'],
      'date' => time(),
    ]);

    $user = app()->mdb->get('user', '*', [
      'id_user' => $request->attributes['user']['id_user'],
    ]);

    $school = 'Unknown';
    if($request->input('id_school')) {
      $school = app()->mdb->get('school', '*', [
        'id_school' => $request->input('id_school'),
      ]);
      $school = $school['name'] . ', ' . $school['city'];
    }

    $credit = $request->input('credit') ? 'yes' : 'no';

    mail(env('APP_RELEASE_EMAIL'), 'New SHOOTLIST requested idea.', "
      From: {$user['name']} {$user['surname']}, \n
      Wants credit: $credit \n
      School: {$school}, \n
      City: {$request->input('city')}, \n
      Question: {$request->input('question')},
    ");
  }


   public function getRequestsStats(Request $request)
   {
     $users = app()->mdb->select('shootlist_request', [
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
       $tmp = app()->mdb->select('shootlist', 'id_shootlist', [
         'id_author' => $user['id_user'],
       ]);

       if(empty($tmp)) {
         $users[$i]['saw'] = 0;
         continue;
       }

       $users[$i]['saw'] = app()->mdb->count('shootlist_counter', [
         'id_shootlist' => $tmp,
       ]);
     }

     // SAWED
     $tmp = app()->mdb->select('shootlist', 'id_shootlist', [
       'id_author' => $request->attributes['user']['id_user'],
     ]);
     if(empty($tmp)) {
       $saw = 0;
     } else {
       $saw = app()->mdb->count('shootlist_counter', [
         'id_shootlist' => $tmp,
       ]);
     }

     return [
       'users' => $users,
       'personal' => [
         'sent' => app()->mdb->count('shootlist_request', [
           'id_user' => $request->attributes['user']['id_user'],
         ]),
         'verified' => app()->mdb->count('shootlist', [
           'id_author' => $request->attributes['user']['id_user'],
         ]),
         'saw' => $saw,
       ]
     ];
   }


   public function getLiked(Request $request)
   {
     return app()->mdb->select('shootlist_like', [
       '[>]shootlist' => 'id_shootlist'
     ], '*', [
       'id_user' => $request->attributes['user']['id_user']
     ]);
   }


   public function getFilterState(Request $request)
   {
     $filter = $request->all();

     $filter = array_intersect_key($filter, array_flip([
       'for_type',
       'gifts',
     ]));

     return app()->mdb->get('shootlist_state', '*', [
       'id_user' => $request->attributes['user']['id_user'],
       'filter' => json_encode($filter, JSON_UNESCAPED_UNICODE),
     ]);
   }


   private function processFilterState(Request $request, $index)
   {
     $filter = $request->all();

     $filter = array_intersect_key($filter, array_flip([
       'for_type',
       'gifts',
     ]));

     $filter = json_encode($filter, JSON_UNESCAPED_UNICODE);

     if(!app()->mdb->has('shootlist_state', [
       'id_user' => $request->attributes['user']['id_user'],
       'filter' => $filter,
     ])) {
       app()->mdb->insert('shootlist_state', [
         'order_index' => $index,
         'id_user' => $request->attributes['user']['id_user'],
         'filter' => $filter,
       ]);
     } else {
       app()->mdb->update('shootlist_state', [
         'order_index' => $index,
       ], [
         'id_user' => $request->attributes['user']['id_user'],
         'filter' => $filter,
       ]);
     }
   }
}
