<?php

namespace Lazarus\Controllers;

use App\Http\Controllers\Controller;
use Error;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ResourceController extends Controller
{
  public function resolveResourceComponent($action,Request $request): JsonResponse
  {
    try {
      return $this->{$action}($request);
    } catch (Error $e) {
      return response()->json(['success' => false, 'message' => $e->getMessage()]);
    }
  }

  protected function resolveCreateBtn(Request $request): JsonResponse
  {
    return response()->json(['can_create' => app()->make($request->resource)->canCreate()]);
  }

  private function filterOnArray($array,$index): array
  {
    $filtered = array_filter($array, fn ($column) => $column->{$index});
    $newArray = [];
    foreach ($filtered as $row) $newArray[] = $row;
    return $newArray;
  }

  private function getVisibleListFields($resource): array
  {
    return $this->filterOnArray($resource->list(), 'visible');
  }

  private function getVisibleListFilters($resource): array
  {
    return $this->filterOnArray($resource->filters(), 'visible');
  }

  protected function resolveDataTable(Request $request): JsonResponse
  {
    try {
      $resource = app()->make($request->resource);
      $columns = $this->getVisibleListFields($resource);
      $filters = $this->getVisibleListFilters($resource);

      return response()->json([
        'success' => true,
        'columns' => $columns,
        'filters' => $filters,
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

  private function renderSlotRespoonse(array $slot): array
  {
    $rows = [];
    foreach ($slot as $row) {
      if(@$row?->rendered) $rows[] = $row->rendered;
      if(@$row?->componentOptions) $rows[] = $row->componentOptions;
      if(gettype($row) == 'string') $rows[] = $row;
    }
    return $rows;
  }

  private function makeSlotResponse(Request $request,string $index): JsonResponse
  {
    $resource = app()->make($request->resource);
    $slot = $this->renderSlotRespoonse($resource->{$index}());
    return response()->json(['success' => true, 'slot' => $slot]);
  }

  protected function resolveBeforeListSlot(Request $request): JsonResponse
  {
    return $this->makeSlotResponse($request,"beforeListSlot");
  }

  protected function resolveAfterListSlot(Request $request): JsonResponse
  {
    return $this->makeSlotResponse($request,"afterListSlot");
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
