<?php

namespace Lazarus\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
  public function resolveResourceComponent(Request $request): JsonResponse
  {
    return $this->{$request->action}($request);
  }

  protected function resolveCreateBtn(Request $request): JsonResponse
  {
    return response()->json(['can_create' => app()->make($request->resource)->canCreate()]);
  }

  private function getVisibleListFields($resource): array
  {
    return array_filter($resource->list(), fn ($column) => $column->visible);
  }

  protected function resolveDataTable(Request $request): JsonResponse
  {
    $resource = app()->make($request->resource);
    $list = $this->getVisibleListFields($resource);

    return response()->json([
      'success' => true,
      'columns' => $list,
      'no_result_text' => $resource->noListResultText(),
      'per_page_options' => $resource->perPageOptions(),
      'per_page_default' => $resource->perPageDefault(),
      'basic_filter_placeholder' => $resource->basicFilterPlaceholder(),
      'hover_color' => config('lazarus.datatable.hover_color', 'rgb(249, 250, 251)'),
      'theme_color' => config('lazarus.datatable.theme_color', 'rgb(0, 238, 255)'),
      'per_page_text' => $resource->perPageText(),
      'total_list_text' => config('lazarus.datatable.total_list_text', 'Total de {total} registros'),
    ]);
  }

  protected function resolveListData(Request $request): JsonResponse
  {
    $resource = app()->make($request->resource);
    $list = $this->getVisibleListFields($resource);
    $data = [];
    $total = 0;
    $defaultSort = $resource->defaultSort();
    $sort = @$request->sort ? $request->sort : $defaultSort[0];
    $sortType = @$request->sort_type ? $request->sort_type : $defaultSort[1];
    if ($resource->type() === 'model') {
      $query = app()->make($resource->model());

      if (!$request->sort) {
        $query = $query->orderBy($sort, $sortType);
      }

      // filter here

      $total = $query->count();
      $result = $query->paginate($request->per_page, ['*'], 'page', $request->page);

      foreach ($result as $entity) {
        $row = [];
        foreach ($list as $column) {
          $row[] = @$column->handleAction($entity);
        }
        $data[] = $row;
      }
    }
    return response()->json(['success' => true, "list" => $data, "total" => $total, "sort_type" => $sortType, "sort" => $sort]);
  }
}
