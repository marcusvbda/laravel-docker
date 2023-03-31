<?php

namespace Lazarus;

class Filter
{
  public string $type;
  public string $label;
  public string $likeColumn; 
  public bool $visible = true; 
  public object $handle;

  public function __construct(string $label,string $type)
  {
    $this->label = $label;
    $this->type = $type;
  }

  public static function make(string $label,string $type): Filter
  {
    return new Filter($label,$type);
  }

  public function likeColumn(string $column): Filter
  {
    $this->likeColumn = $column;
    return $this;
  }

  public function canSee(bool $value): Filter
  {
    $this->visible = $value;
    return $this;
  }

  public function handler($value): Filter
  {
    $this->handler = $value;
    return $this;
  }
}
