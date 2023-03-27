<?php

namespace Neo;

use Illuminate\Support\Facades\Http;

class Inspire
{
  public static function justDoIt()
  {
    $response = Http::get('https://inspiration.goprogram.ai/');

    return $response['quote'] . ' -' . $response['author'];
  }
}
