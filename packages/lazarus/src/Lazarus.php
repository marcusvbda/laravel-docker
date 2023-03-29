<?php

namespace Lazarus;

use Illuminate\Support\Facades\Route;
use Lazarus\Controllers\ResourceController;

class Lazarus
{
  public static function resource(String $resource): Resource
  {
    $createdResource = app()->make($resource);
    return $createdResource;
  }

  public static function routes(): void
  {
    Route::post('resolve-resource-component', [ResourceController::class, 'resolveResourceComponent'])->name('lazarus.resolve-resource-component');
  }
}
