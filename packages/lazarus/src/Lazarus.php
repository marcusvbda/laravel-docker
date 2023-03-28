<?php

namespace Lazarus;

class Lazarus
{
  public static function resource(String $resource): Resource
  {
    $createdResource = app()->make($resource);
    return $createdResource;
  }

  public static function routes(): array
  {
    return [];
  }
}
