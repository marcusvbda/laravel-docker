<?php

namespace Lazarus;

use Inertia\Inertia;
use Inertia\Response;
use Lazarus\list\Column;
use ReflectionClass;

class Resource
{
  public function makeListPage(): Response
  {
    if (!$this->canViewList()) abort(403);

    return Inertia::render($this->listView(), [
      'payload' => $this->listViewPayload()
    ]);
  }

  protected function icon(): string
  {
    return "";
  }

  protected function resourceName(): string
  {
    return (new ReflectionClass($this))->getName();
  }

  protected function resourceShortName(): string
  {
    return (new ReflectionClass($this))->getShortName();
  }

  protected function viewPath(): string
  {
    return "Resources";
  }

  public function listView(): string
  {
    $viewPath = $this->viewPath();
    return "$viewPath/ListView";
  }

  public function defaultPayload(): array
  {
    return [
      'colors' => config('lazarus.colors'),
      'appearance' => [
        'create_btn_text' => $this->createBtnText(),
        'icon' => $this->icon(),
        'title' => $this->title(),
        'singular_title' => $this->singularTitle(),
      ],
      'resource' => [
        'name' => $this->resourceName(),
        'short_name' => $this->resourceShortName(),
        'model' => $this->model(),
      ]
    ];
  }

  public function basicFilterPlaceholder(): string
  {
    return config('lazarus.datatable.default_basic_filter_placeholder', 'Encontrar ...');
  }

  public function createBtnText(): string
  {
    $createBtnText = config('lazarus.labels.create_btn_text');
    return str_replace('{singularTitle}', $this->singularTitle(), $createBtnText);
  }

  private function renderSlot($slot): array
  {
    $rows = [];
    foreach ($slot as $row) {
      if(@$row?->rendered) $rows[] = $row->rendered;
      if(@$row?->componentOptions) $rows[] = $row->componentOptions;
      if(gettype($row) == 'string') $rows[] = $row;
    }
    return $rows;
  }

  public function listViewPayload(): array
  {
    $defaultPayload = $this->defaultPayload();
    $beforeListSlot = $this->renderSlot($this->beforeListSlot());

    return array_merge($defaultPayload, [
      "slots" => [
        'before_list_slot' => $beforeListSlot,
      ]
    ]);
  }

  public function aclPayload($entity = null): array
  {
    $payload = [
      'canViewList' => $this->canViewList(),
      'canCreate' => $this->canCreate(),
      'canCreate' => $this->canEdit(),
      'canDelete' => $this->canDelete(),
    ];

    //add acl for entity
    if ($entity) {
      $payload = array_merge($payload, []);
    }

    return $payload;
  }

  public function title(): string
  {
    return $this->resourceShortName();
  }

  public function singularTitle(): string
  {
    $resourceShortName = $this->title();
    return substr($resourceShortName, 0, -1);
  }

  public function canViewList(): bool
  {
    return true;
  }

  public function canCreate(): bool
  {
    return true;
  }

  public function canDelete(): bool
  {
    return true;
  }

  public function canEdit(): bool
  {
    return true;
  }

  public function model(): string
  {
    return '';
  }

  public function list(): array
  {
    return [
      Column::make('#', 'id')->width("100px")->sortable(),
    ];
  }

  public function noListResultText(): string
  {
    return config('lazarus.datatable.default_no_result_text', 'Nenhum resultado encontrado');
  }

  public function defaultSort(): array
  {
    return ['id', 'desc'];
  }

  public function perPageDefault(): int
  {
    return config('lazarus.datatable.default_per_page_value', 10);
  }

  public function perPageOptions(): array
  {
    return config('lazarus.datatable.default_per_page_options', [10, 25, 50, 100]);
  }

  public function totalListText(): string
  {
    return config('lazarus.datatable.total_list_text', 'Total de registros: {total}');
  }

  public function perPageText(): string
  {
    return config('lazarus.datatable.per_page_text', 'Por Página');
  }

  public function search(): array
  {
    return [];
  }

  public function filters(): array
  {
    return [];
  }

  public function basicFilterHandler($query, $value)
  {
    return $query->where(function ($query) use ($value) {
      foreach ($this->search() as $search) {
        if (gettype($search) === 'string') {
          $query->orWhere($search, 'like', "%$value%");
        } else {
          $query = $search($query, $value);
        }
      }
    });
  }
  
  public function beforeListSlot():array
  {
    return [];
  }
}
