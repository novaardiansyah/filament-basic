<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CategoryResource extends Resource
{
  protected static ?string $model = Category::class;

  protected static ?string $navigationIcon = 'heroicon-o-folder';
  protected static ?string $modelLabel = 'Post Categories';

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Section::make('Category Details')
          ->description('Please fill in the category details below.')
          ->schema([
            TextInput::make('name')
              ->required(),
            TextInput::make('slug')
              ->required()
              ->unique(ignoreRecord: true),
          ])
          ->columns(2)
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        TextColumn::make('name'),
        TextColumn::make('slug'),
        TextColumn::make('created_at')
          ->date('M d, Y H:i')
          ->toggleable(),
        TextColumn::make('updated_at')
          ->date('M d, Y H:i')
          ->toggleable(),
      ])
      ->defaultSort('created_at', 'desc')
      ->filters([
        //
      ])
      ->actions([
        Tables\Actions\EditAction::make(),
      ])
      ->bulkActions([
        Tables\Actions\BulkActionGroup::make([
          Tables\Actions\DeleteBulkAction::make(),
        ]),
      ]);
  }

  public static function getRelations(): array
  {
    return [
      //
    ];
  }

  public static function getPages(): array
  {
    return [
      'index' => Pages\ListCategories::route('/'),
      'create' => Pages\CreateCategory::route('/create'),
      'edit' => Pages\EditCategory::route('/{record}/edit'),
    ];
  }
}
