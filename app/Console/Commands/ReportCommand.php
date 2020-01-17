<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ReportCommand extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'report';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Pops monthly report';

  private $from_time;
  private $to_time;

  private function getHeaderByUser($user)
  {
    $from_date = date('d.m.Y', $this->from_time);
    $to_date = date('d.m.Y', $this->to_time);
    return "{$user['name']} {$user['surname']} Date: {$from_date}-{$to_date} \n";
  }

  private function playedGames($id_user)
  {
    $games = app()->mdb->select('playlist_counter', [
      '[>]playlist' => 'id_playlist',
    ], 'name', [
      'playlist_counter.date[<>]' => [ $this->from_time, $this->to_time ],
      'GROUP' => 'id_playlist',
    ]);

    $str = "\nGames played this month: \n";

    foreach ($games as $game) {
      $str .= "$game \n";
    }

    return $str;
  }

  private function getFunzoneByUser($id_user)
  {
    $count = app()->mdb->count('user_event', '*', [
      'id_user' => $id_user,
      'date[<>]' => [ $this->from_time, $this->to_time ],
    ]);
    return "\nFUN ZONE registrated: $count \n";
  }


  private function getGiftsByUser($id_user)
  {
    $categories = app()->mdb->select('category_playlist', '*');

    $str = "";

    foreach ($categories as $i => $category) {

      $ids = app()->mdb->select('playlist_category', 'id_playlist', [
        'id_category' => $category['id_category']
      ]);

      $categories[$i]['count'] = app()->mdb->count('playlist_counter', [
        'date[<>]' => [ $this->from_time, $this->to_time ],
        'id_playlist' => $ids,
        'id_user' => $id_user,
        'type' => 'ans',
      ]);
    }

    $count = app()->mdb->count('playlist_counter', [
      'date[<>]' => [ $this->from_time, $this->to_time ],
      'id_user' => $id_user,
      'type' => 'ans',
    ]);

    $str .= "Playlist games played: $count \n";
    $str .= "Gifts taken from these games: \n";

    uasort($categories, function ($a, $b) {
      if ($a['count'] == $b['count']) {
        return 0;
      }
      return ($a['count'] < $b['count']) ? 1 : -1;
    });

    foreach ($categories as $category) {
      if($category['count'] > 0) {
        $str .= "{$category['name']}: {$category['count']} \n";
      }
    }

    return $str;
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle()
  {
    $phone_users = app()->mdb->select('user_notification', [
      '[>]user' => 'id_user',
    ], 'id_user', [
      'OR' => [
        'phone_funzone' => true,
        'phone_dev' => true,
      ],
      'phone[!]' => null
    ]);

    $email_users = app()->mdb->select('user_notification', [
      '[>]user' => 'id_user',
    ], '*', [
      'OR' => [
        'email_funzone' => true,
        'email_dev' => true,
      ],
    ]);

    $this->to_time = time();
    $this->from_time = $this->to_time - 2628000;

    echo "Users emails count: " . count($email_users) . " \n\n";

    foreach ($email_users as $user) {

      echo "(EMAIL) Starting to parse user ID: {$user['id_user']}\n";

      $str = "";

      $str .= $this->getHeaderByUser($user);

      if($user['email_funzone']) {
        $str .= $this->getFunzoneByUser($user['id_user']);
      }

      if($user['email_dev']) {
        $str .= $this->getGiftsByUser($user['id_user']);
        $str .= $this->playedGames($user['id_user']);
      }

      // echo "$str \n\n";

      mail($user['email'], 'Playlist monthly activity', $str, "From: playlist \r\n");
    }


    foreach ($email_users as $user) {

      echo "(PHONE) Starting to parse user ID: {$user['id_user']}\n";

      $str = "";

      $str .= $this->getHeaderByUser($user);

      if($user['phone_funzone']) {
        $str .= $this->getFunzoneByUser($user['id_user']);
      }

      if($user['phone_funzone']) {
        $str .= $this->getGiftsByUser($user['id_user']);
      }

      // echo "$str \n\n";

      // try {
      //   (new \App\SMSProvider())->sendSMS("Playlist monthly activity: \n $str", $user['phone']);
      // } catch (\Exception $e) {
      //
      // }

    }

  }
}
