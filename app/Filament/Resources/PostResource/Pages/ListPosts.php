<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use Illuminate\Contracts\Database\Eloquent\Builder;

class ListPosts extends ListRecords
{
  protected static string $resource = PostResource::class;

  protected function getHeaderActions(): array
  {
    return [
      Actions\CreateAction::make(),
    ];
  }

  public function getTabs(): array
  {
    return [
      'All' => Tab::make(),
      'Published' => Tab::make() 
        ->modifyQueryUsing(function (Builder $query): Builder {
          return $query->where('published', true);
        }),
      'Unpublish' => Tab::make() 
        ->modifyQueryUsing(function (Builder $query): Builder {
          return $query->where('published', false);
        })
    ];
  }
}
