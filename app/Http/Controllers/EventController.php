<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Medoo\Medoo;

class EventController extends Controller
{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
   public function store(Request $request)
   {
     app()->mdb->action(function () use ($request) {

       $categories = $request->input('categories');
       $date = strtotime($request->input('date') . ' ' . $request->input('time'));

       if($date < time())
         throw new \InvalidArgumentException('לא ניתן להשתמש בתאריך שעבר');
       if(count($request->input('age')) > 2)
         throw new \InvalidArgumentException('פורמט לא חוקי של הגילאים');
       if(empty($request->input('geo_full')) || empty($request->input('geo')))
         throw new \InvalidArgumentException('יש למלא את כל התומוסים הגיאוגרפיים');
       if(empty($categories))
         throw new \InvalidArgumentException('שחכתם למלא את שדה מוזמנים לבוא כי יהיה...');


       $age = implode('-', $request->input('age'));

       app()->mdb->insert('event', [
         'img' => $request->input('img'),
         'id_author' => $request->attributes['user']['id_user'],
         'geo' => $request->input('geo'),
         'pass' => $request->input('pass'),
         'comment' => $request->input('comment'),
         'max_users' => $request->input('max_users'),
         'name' => $request->input('name'),
         'phone' => $request->input('phone'),
         'age' => $age,
         'geo_full' => $request->input('geo_full'),
         'date' => time(),
         'date_release' => $date,
       ]);

       $id_event = app()->mdb->id();

       app()->mdb->insert('user_event', [
         'id_event' => $id_event,
         'id_user' => $request->attributes['user']['id_user'],
       ]);

       $query = [];

       foreach ($categories as $id_category) {
         $query[] = [
           'id_event' => $id_event,
           'id_category' => $id_category,
         ];
       }
       app()->mdb->insert('category_event', $query);


     });
   }


   public function get(Request $request, $id_event)
   {
     $event = app()->mdb->get('event', '*', [
       'id_event' => $id_event
     ]);

     if($event) {
       $event['categories'] = app()->mdb->select('category_event', [
         '[>]event_category' => 'id_category'
       ], '*', [
         'id_event' => $id_event
       ]);
       $event['users_count'] = app()->mdb->count('user_event', [
         'id_event' => $id_event
       ]);
     } else {
       throw new \InvalidArgumentException('Event not found', 404);
     }

     $event['is_in'] = app()->mdb->has('user_event', [
       'id_user' => $request->attributes['user']['id_user'],
       'id_event' => $id_event,
     ]);

     $event['date_release'] = intval($event['date_release']);

     return $event;
   }


   public function select(Request $request)
   {

     $page = $request->input('page');
     $filters = $request->input('filters');
     $query = $request->input('query');

     $per_chunk = 12;

     $from = $page * $per_chunk;
     $to = $per_chunk;
     $events = app()->mdb->select('event', '*', [
       'LIMIT' => [ $from, $to ],
       'ORDER' => $filters,
       'OR' => [
         'geo_full[~]' => "%$query%",
         'geo[~]' => "%$query%",
       ],
       'date_release[>]' => time(),
       'hidden' => false,
     ]);

     foreach ($events as $i => $event) {
       $events[$i]['users_count'] = app()->mdb->count('user_event', [
         'id_event' => $event['id_event']
       ]);
       $events[$i]['is_registered'] = app()->mdb->has('user_event', [
         'id_user' => $request->attributes['user']['id_user']
       ]);
     }

     return [
       'events' => $events,
       'ended' => count($events) < $per_chunk,
     ];
   }


   public function selectPastLast(Request $request)
   {
     $events = app()->mdb->select('event', '*', [
       'LIMIT' => 32,
       'ORDER' => [ 'date' => 'DESC' ],
       'date_release[<]' => time(),
       'hidden' => false,
     ]);

     foreach ($events as $i => $event) {
       $events[$i]['users_count'] = app()->mdb->count('user_event', [
         'id_event' => $event['id_event']
       ]);
       $events[$i]['is_registered'] = app()->mdb->has('user_event', [
         'id_user' => $request->attributes['user']['id_user']
       ]);
     }

     return $events;
   }


   public function join(Request $request, $id_event)
   {

     $event = app()->mdb->get('event', '*', [
       'id_event' => $id_event
     ]);

     $count = app()->mdb->count('user_event', [
       'id_event' => $id_event,
     ]);

     if($event['max_users'] < $count) {
       throw new \InvalidArgumentException('האירוע מלא');
     }

     if(!app()->mdb->has('user_event', [
       'id_event' => $id_event,
       'id_user' => $request->attributes['user']['id_user'],
     ])) {
       app()->mdb->insert('user_event', [
         'id_event' => $id_event,
         'id_user' => $request->attributes['user']['id_user'],
         'date' => time(),
       ]);
     } else {
       throw new \InvalidArgumentException('אתה כבר רשום באירוע');
     }
   }


   public function addCategory(Request $request)
   {
     app()->mdb->insert('event_category', [
       'name' => $request->input('name')
     ]);
   }


   public function removeCategory($id_category)
   {
     app()->mdb->delete('event_category', [
       'id_category' => $id_category
     ]);
   }


   public function updateCategory(Request $request, $id_category)
   {
     app()->mdb->update('event_category', [
       'name' => $request->input('name')
     ], [
       'id_category' => $id_category
     ]);
   }


   public function getCategories()
   {
     return app()->mdb->select('event_category', '*');
   }


   public function cancel(Request $request, $id_event)
   {
     app()->mdb->delete('user_event', [
       'id_event' => $id_event,
       'id_user' => $request->attributes['user']['id_user'],
     ]);
   }


  public function sendConclusion(Request $request, $id_event)
  {
    $event = app()->mdb->get('event', '*', [
      'id_event' => $id_event
    ]);

    $data = $request->all();

    $date = date($event['date_release']);

    mail(env('APP_RELEASE_EMAIL'), 'FUN ZONE Conclusion', "
      Geolocation: {$event['geo']}, {$event['geo_full']} \n
      Passed: $date \n
      Phone: {$event['phone']} \n
      \n
      Next FUN ZONE: {$data['next']} \n
      {$data['second']} \n
      {$data['third']} \n
      {$data['fourth']} \n
      Rating: {$data['rating']} \n
      WhatsApp group?: {$data['whatsapp']} \n
    ");
  }
}
