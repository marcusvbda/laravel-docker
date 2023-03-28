<?php

namespace App\Http\Resources;

use Calisto\Resource;
use App\Models\Vehicle;

class Vehicles extends Resource
{
  public function model(): string
  {
    return Vehicle::class;
  }
}
