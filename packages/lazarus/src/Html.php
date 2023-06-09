<?php

namespace Lazarus;

class Html
{
  public string $tag;
  public string $text = "";
  public array $attributes = [];
  public string $rendered = "";

  public function __construct(string $tag)
  {
    $this->tag = $tag;
    $this->render();
  }

  public static function make(string $tag): Html
  {
    return new Html($tag);
  }

  public function text($value): Html
  {
    $valueText = "";
    if(in_array(gettype($value),["string","object"])) {
      $valueText = @$value?->rendered ?? $value;
    } else if(gettype($value) == "array") {
      foreach ($value as $key => $item) {
        $valueText .= @$item?->rendered ?? $item;
      }
    }
    $this->text = $valueText;
    $this->render();
    return $this;
  }

  public function attributes(array $payload): Html
  {
    $this->attributes = $payload;
    $this->render();
    return $this;
  }

  public function render() : void
  {
    $value = "<$this->tag></$this->tag>";
    if (@$this->text) $value = "<$this->tag>$this->text</$this->tag>";
    if ($this->attributes) {
      $attributes = "";
      foreach ($this->attributes as $key => $value) {
        $attributes .= " $key='$value'";
      }
      $value = "<$this->tag $attributes></$this->tag>";
      if ($this->text) $value = "<$this->tag $attributes>$this->text</$this->tag>";
    }
    $this->rendered = $value;
  }
}
