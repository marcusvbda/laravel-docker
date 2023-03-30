<?php

namespace App\Http\Resources;

use App\Models\Vehicle;
use Lazarus\list\Column;
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
      Column::make('#', 'id')->width("100px")->sortable(),
      Column::make('Nome', 'name')->sortable(),
      Column::make('Marca', fn ($entity) => $entity->brand),
    ];
  }

  public function defaultSort(): array
  {
    return ['name', 'desc'];
  }
}
