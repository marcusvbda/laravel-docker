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
    $list = array_filter($resource->list(), fn ($column) => $column->visible);

    return response()->json([
      'success' => true,
      'columns' => $list,
      'no_result_text' => $resource->noListResultText(),
      'basic_filter_placeholder' => $resource->basicFilterPlaceholder(),
      'hover_color' => config('lazarus.datatable.hover_color', 'rgb(249, 250, 251)'),
      'loading_color' => config('lazarus.datatable.loading_color', 'rgb(0, 238, 255)'),
    ]);
  }
}
