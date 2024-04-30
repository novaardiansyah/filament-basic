<?php

namespace App\Filament\Resources\PostResource\RelationManagers;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersRelationManager extends RelationManager
{
  protected static string $relationship = 'users';

  public function form(Form $form): Form
  {
    return $form
      ->schema([
        TextInput::make('order')
          ->required()
          ->numeric()
          ->minValue(1)
          ->default(1),
      ])
      ->columns(1);
  }

  public function table(Table $table): Table
  {
    return $table
      ->recordTitleAttribute('name')
      ->columns([
        TextColumn::make('name')
          ->searchable()
          ->sortable(),
        TextColumn::make('email')
          ->searchable(),
        TextColumn::make('order')
          ->numeric()
          ->sortable(),
        TextColumn::make('created_at')
          ->label('Member Since')
          ->date('M d, Y H:i')
          ->toggleable(isToggledHiddenByDefault: false),
        TextColumn::make('updated_at')
          ->label('Last Update')
          ->date('M d, Y H:i')
          ->toggleable(isToggledHiddenByDefault: true)
      ])
      ->filters([
        //
      ])
      ->headerActions([
        Tables\Actions\AttachAction::make()
          ->preloadRecordSelect()
          ->multiple()
          ->form(fn (AttachAction $action): array => [
            $action->getRecordSelect(),
            TextInput::make('order')  
              ->required()
              ->numeric()
              ->minValue(1)
              ->default(1),
        ]),
      ])
      ->actions([
        Tables\Actions\EditAction::make()
          ->modalWidth(MaxWidth::Small),
        Tables\Actions\DetachAction::make(),
      ])
      ->bulkActions([
        Tables\Actions\BulkActionGroup::make([
          Tables\Actions\DetachBulkAction::make(),
        ]),
      ]);
  }
}
