<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

class FunZoneController extends \App\Http\Controllers\Controller
{


  public function get(Request $request, $id_event)
  {
    $funzone = app()->mdb->get('event', '*', [
      'id_event' => $id_event,
    ]);

    $funzone['hidden'] = boolval($funzone['hidden']);
    return $funzone;
  }


  public function update(Request $request, $id_event)
  {
    app()->mdb->update('event', $request->all(), [
      'id_event' => $id_event,
    ]);
  }


  public function index(Request $request)
  {
    $events = app()->mdb->select('event', '*', [
      'LIMIT' => 258,
    ]);

    foreach ($events as $i => $event) {
      $events[$i]['author'] = app()->mdb->get('user', '*', [
        'id_user' => $event['id_author'],
      ]);
      $events[$i]['users'] = app()->mdb->count('user_event', [
        'id_event' => $event['id_event'],
      ]);
      $events[$i]['users_saw'] = app()->mdb->count('activity', [
        'id_essence' => $event['id_event'],
        'type' => 'funzone-see',
      ]);
      $events[$i]['hidden'] = boolval($events[$i]['hidden']);
    }

    return $events;
  }


  public function delete(Request $request, $id_event)
  {
    app()->mdb->delete('event', [
      'id_event' => $id_event,
    ]);
  }
}
