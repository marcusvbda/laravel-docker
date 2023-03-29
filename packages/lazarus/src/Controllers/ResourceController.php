<?php

namespace Lazarus\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
  public function resolveResourceComponent(Request $request)
  {
    return $this->{$request->action}($request);
  }

  protected function resolveCreateBtn(Request $request)
  {
    return response()->json(['can_create' => app()->make($request->resource)->canCreate()]);
  }

  protected function resolveDataTable(Request $request)
  {
    $resource = app()->make($request->resource);
    return response()->json([
      'success' => true,
      'basic_filter_placeholder' => $resource->basicFilterPlaceholder(),
      'hover_color' => config('lazarus.datatable.hover_color', 'rgb(249, 250, 251)'),
    ]);
  }
}
