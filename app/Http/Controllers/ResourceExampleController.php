<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use Neo\Inspire;

class ResourceExampleController extends Controller
{
  public function index(): Response
  {
    $quote = Inspire::justDoIt();
    return Inertia::render('Resources/Example', [
      'title' => 'Example',
      'quote' => $quote,
    ]);
  }
}
