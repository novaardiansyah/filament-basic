<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PostResource extends Resource
{
  protected static ?string $model = Post::class;

  protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
  protected static ?string $modelLabel = 'Posts';

  public static function form(Form $form): Form
  {
    return $form
    ->schema([
      Section::make('Post Details')
        ->description('Please fill in the post details below.')
        ->schema([
          TextInput::make('title')
            ->required(),
          TextInput::make('slug')
            ->required(),
          Select::make('category_id')
            ->label('Category')
            ->relationship('category', 'name')
            ->native(false)
            ->searchable()
            ->preload()
            ->required(),
          ColorPicker::make('color')
            ->required(),
          TagsInput::make('tags')
            ->required(),
          Checkbox::make('published'),
          FileUpload::make('thumbnail')
            ->disk('public')
            ->directory('thumbnails'),
          MarkdownEditor::make('content')
            ->columnSpanFull()
        ])
        ->columns(2)
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
    ->columns([
        ImageColumn::make('thumbnail'),
        ColorColumn::make('color'),
        TextColumn::make('title'),
        TextColumn::make('slug'),
        TextColumn::make('category.name'),
        TextColumn::make('tags'),
        CheckboxColumn::make('published'),
        TextColumn::make('content'),
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
        Tables\Actions\DeleteAction::make(),
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
      'index' => Pages\ListPosts::route('/'),
      'create' => Pages\CreatePost::route('/create'),
      'edit' => Pages\EditPost::route('/{record}/edit'),
    ];
  }
}
