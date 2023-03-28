<?php

namespace App\Http\Resources;

use App\Models\Vehicle;
use Lazarus\Resource;

class Vehicles extends Resource
{
  public function model(): string
  {
    return Vehicle::class;
  }
}
