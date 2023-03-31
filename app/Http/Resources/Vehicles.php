<?php

namespace App\Http\Resources;

use App\Models\Vehicle;
use Lazarus\Filter;
use Lazarus\list\Column;
use Lazarus\Html;
use Lazarus\VueComponent;
use Lazarus\Resource;

class Vehicles extends Resource
{
  public function model(): string
  {
    return Vehicle::class;
  }

  public function title(): string
  {
    return "Veículos";
  }

  public function list(): array
  {
    return [
      Column::make('#', 'id')->width("100px")->canSee(true)->sortable(),
      Column::make('Nome', 'name')->sortable(),
      Column::make('Marca', fn ($entity) => Html::make('b')->text($entity->brand)),
      Column::make('Html Test', function($entity) {
        return  Html::make('a')->attributes(["href" => "https://google.com","target" => "_blank","style" => "display:flex;flex-direction:column"])->text([
          Html::make('b')->text("Test :"),
          Html::make('i')->text($entity->name),
        ]);
      }),
      Column::make('Vue Component Test', fn ($entity) => VueComponent::make('ComponentTest')->attributes(["color" => "red"])->text($entity->brand)),
    ];
  }

  public function search(): array
  {
    return [
      'id',
      'name',
      function ($query, $value) {
        $query->orWhere('brand', 'like', "%$value%");
      }
    ];
  }

  public function filters(): array
  {
    return [
      Filter::make('Código ou Id','text')->likeColumn("id")->canSee(true),
      Filter::make('Marca','text')->handler(fn ($q, $v)=> $q->where('brand', 'like', "%$v%")),
    ];
  }
}
