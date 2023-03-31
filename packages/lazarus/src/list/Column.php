<?php

namespace Lazarus\list;

class Column
{
  public string $index;
  public string $label;
  public bool $sortable;
  public bool $visible;
  private object $action;
  public string $width;

  public function __construct(string $label, string $index, object $action)
  {
    $this->index = $index;
    $this->label = $label;
    $this->action = $action;
    $this->sortable = false;
    $this->visible = true;
    $this->width = "auto";
  }

  public static function make(string $label, $indexOrAction): Column
  {
    $defaultHandler = fn ($entity) => @$entity->{$index};

    $index = gettype($indexOrAction) == "string" ? $indexOrAction : "";
    $action = gettype($indexOrAction) != "string" ? $indexOrAction : $defaultHandler;

    return new Column($label, $index, $action);
  }

  public function handleAction($entity)
  {
    if ($this->index) return $entity->{$this->index};
    $result =  $this->action->__invoke($entity);
    if(@$result?->rendered) return $result->rendered;
    if(@$result?->componentOptions) return $result->componentOptions;
    return $result;
  }

  public function width(string $value): Column
  {
    $this->width = $value;
    return $this;
  }

  public function visible(bool $value = true): Column
  {
    $this->visible = $value;
    return $this;
  }

  public function sortable(bool $value = true): Column
  {
    $this->sortable = $value;
    return $this;
  }
}
