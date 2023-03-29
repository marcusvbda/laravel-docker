<?php

namespace Lazarus;

use Inertia\Inertia;
use Inertia\Response;
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
        'type' => $this->type(),
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

  public function listViewPayload(): array
  {
    $defaultPayload = $this->defaultPayload();
    return array_merge($defaultPayload, []);
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

  public function type(): string
  {
    return 'model';
  }

  public function model(): string
  {
    return '';
  }

  public function list(): array
  {
    return [];
  }

  public function noListResultText(): string
  {
    return config('lazarus.datatable.default_no_result_text', 'Nenhum resultado encontrado');
  }
}
