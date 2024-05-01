<?php

namespace App\Filament\Resources\PostResource\RelationManagers;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CommentsRelationManager extends RelationManager
{
  protected static string $relationship = 'comments';

  public function form(Form $form): Form
  {
    return $form
      ->schema([
        Select::make('user_id')
          ->relationship('user', 'name')
          ->preload()
          ->searchable()
          ->required(),
        TextInput::make('comment')
          ->required(),
      ]);
  }

  public function table(Table $table): Table
  {
    return $table
      ->recordTitleAttribute('comment')
      ->columns([
        TextColumn::make('user.name')
          ->searchable(),
        TextColumn::make('comment')
          ->searchable(),
        TextColumn::make('created_at')
          ->date('M d, Y H:i')
          ->toggleable(),
        TextColumn::make('updated_at')
          ->date('M d, Y H:i')
          ->toggleable(),
      ])
      ->filters([
        //
      ])
      ->headerActions([
        Tables\Actions\CreateAction::make(),
      ])
      ->actions([
        Tables\Actions\EditAction::make(),
        Tables\Actions\DeleteAction::make(),
      ])
      ->bulkActions([
        Tables\Actions\BulkActionGroup::make([
          Tables\Actions\DeleteBulkAction::make(),
        ]),
      ]);
  }
}
