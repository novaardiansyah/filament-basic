<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreatePost extends CreateRecord
{
  protected static string $resource = PostResource::class;

  protected function handleRecordCreation(array $data): Model
  {
    $record = static::getModel()::create($data);
    $record->users()->attach(auth()->id());
    return $record;
  }
}
