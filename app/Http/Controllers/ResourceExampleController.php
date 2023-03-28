<?php

namespace App\Http\Controllers;

use App\Http\Resources\Vehicles;
use Inertia\Response;
use Lazarus\Lazarus;

class ResourceExampleController extends Controller
{
  public function index(): Response
  {
    return Lazarus::resource(Vehicles::class)->makeListPage();
  }
}
