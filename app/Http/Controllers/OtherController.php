<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Medoo\Medoo;

class OtherController extends Controller
{


  public function sendContact(Request $request)
  {
    mail(env('APP_RELEASE_EMAIL'), 'Contact form', "
      Name: {$request->input('name')} \n
      Phone: {$request->input('phone')} \n
      Email: {$request->input('email')} \n
      Comment: {$request->input('comment')}
    ");
  }


  public function addActivity(Request $request, $id_essence, $type)
  {
    app()->mdb->insert('activity', [
      'id_essence' => $id_essence,
      'id_user' => $request->attributes['user']['id_user'],
      'type' => $type,
      'date' => time(),
    ]);
  }


  public function buyGift(Request $request)
  {
    if(empty($request->input('city')))
      throw new \InvalidArgumentException(
        'להיכנס לעיר',
        400
      );
    if(empty($request->input('street')))
      throw new \InvalidArgumentException(
        'הזן את הרחוב',
        400
      );
    if(empty($request->input('num')))
      throw new \InvalidArgumentException(
        'הזן מספר',
        400
      );
    if(empty($request->input('mail')))
      throw new \InvalidArgumentException(
        'הזן דוא"ל',
        400
      );
    if(empty($request->input('postal')))
      throw new \InvalidArgumentException(
        'הזן את הדואר',
        400
      );

    $days = (time() - $request->attributes['user']['date']) / 86400;
    $games = app()->mdb->count('playlist_counter', '*', [
      'id_user' => $request->attributes['user']['id_user'],
      'type' => 'ans',
    ]);

    mail(env('APP_RELEASE_EMAIL'), 'Gift wants to be delivered', "
      Name: {$request->attributes['user']['name']} {$request->attributes['user']['surname']} \n
      Can he get this gift? - He is $days days in this game and answered $games games. \n
      City: {$request->input('city')} \n
      Street: {$request->input('street')} \n
      Num: {$request->input('num')} \n
      Mail: {$request->input('mail')} \n
      Postal: {$request->input('postal')} \n
      Comment: {$request->input('comment')}
    ");
  }
}
