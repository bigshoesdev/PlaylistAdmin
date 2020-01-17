<?php

namespace App;

class Mailer
{
    public static function send($email, $subject, $data)
    {
        return mail($email, $subject, $data['link']);
    }
}
