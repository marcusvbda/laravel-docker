<?php

namespace Lazarus\Controllers;

use App\Http\Controllers\Controller;
use Error;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ResourceController extends Controller
{
  public function resolveResourceComponent(Request $request): JsonResponse
  {
    try {
      return $this->{$request->action}($request);
    } catch (Error $e) {
      return response()->json(['success' => false, 'message' => $e->getMessage()]);
    }
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
    try {
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
        'show_basic_filter' => count($resource->search()) > 0,
        'total_list_text' => config('lazarus.datatable.total_list_text', 'Total de {total} registros'),
      ]);
    } catch (Error $e) {
      return response()->json(['success' => false, 'message' => $e->getMessage()]);
    }
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

      $madeModel = app()->make($resource->model());
      $query = clone $madeModel;

      $query = $query->when($sort && $sortType, function ($query) use ($sort, $sortType, $madeModel) {
        $table = $madeModel->getTable();
        if (!Schema::hasColumn($table, $sort)) {
          response()->json(['message' => "Column $sort not found in table {$table}"], 500)->send();
          die;
        }
        return $query->orderBy($sort, $sortType);
      });

      $query = $query->when(@$request->filter, function ($query) use ($resource, $request) {
        return $resource->basicFilterHandler($query, @$request->filter);
      });

      $total = $query->count();
      $result = $query->paginate($request->per_page, ['*'], 'page', $request->page);

      foreach ($result as $entity) {
        $row = [];
        foreach ($list as $column) {
          $row[] = @$column->handleAction($entity);
        }
        $data[] = $row;
      }
      return response()->json(['success' => true, "list" => $data, "total" => $total, "sort_type" => $sortType, "sort" => $sort]);
  }
}
