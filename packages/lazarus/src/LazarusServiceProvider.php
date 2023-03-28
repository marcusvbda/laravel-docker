<?php

namespace Lazarus;

use Illuminate\Support\ServiceProvider;

class LazarusServiceProvider extends ServiceProvider
{
  public function boot(Router $router)
  {
    $this->publishes([
      __DIR__ . '/config' => config_path(),
    ]);
    $this->publishes([
      __DIR__ . '/views' => resource_path("/views"),
    ]);
  }
}
