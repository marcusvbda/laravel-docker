<?php

namespace Calisto;

class Calisto
{
  public static function resource(String $resource): Resource
  {
    $createdResource = app()->make($resource);
    return $createdResource;
  }
}
