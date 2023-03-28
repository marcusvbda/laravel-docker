<?php

namespace App\Http\Controllers;

use App\Http\Resources\Vehicles;
use Calisto\Calisto;
use Inertia\Response;

class ResourceExampleController extends Controller
{
  public function index(): Response
  {
    return Calisto::resource(Vehicles::class)->makeListPage();
  }
}
