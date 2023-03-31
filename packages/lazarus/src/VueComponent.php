<?php

namespace Lazarus;

class VueComponent
{
  public string $component;
  public string $text = "";
  public array $attributes = [];
  public array $componentOptions = [];

  public function __construct(string $component)
  {
    $this->component = $component;
    $this->componentOptions = ["component" => $component];
  }

  public static function make(string $tag): VueComponent
  {
    return new VueComponent($tag);
  }

  public function text($value): VueComponent
  {
    $this->text = @$value->rendered ?? $value;
    $this->componentOptions = array_merge(["text" => $text],$this->componentOptions);
    return $this;
  }

  public function attributes(array $payload): VueComponent
  {
    $this->attributes = $payload;
    $this->componentOptions = array_merge(["attributes" => $attributes],$this->componentOptions);
    return $this;
  }
}
