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
    $this->componentOptions = ["component" => $component, "attributes" => $this->attributes, "text" => $this->text];
  }

  public static function make(string $tag): VueComponent
  {
    return new VueComponent($tag);
  }

  public function text($text): VueComponent
  {
    $this->text = @$text?->rendered ?? $text;
    $this->componentOptions["text"] = $this->text;
    return $this;
  }

  public function attributes(array $attributes): VueComponent
  {
    $this->attributes = $attributes;
    $this->componentOptions["attributes"] = $this->attributes;
    return $this;
  }
}
