<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers\CommentsRelationManager;
use App\Filament\Resources\PostResource\RelationManagers\UsersRelationManager;
use App\Models\Post;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\IconPosition;
use Filament\Tables;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PostResource extends Resource
{
  protected static ?string $model = Post::class;

  protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
  protected static ?string $modelLabel = 'Posts';

  public static function form(Form $form): Form
  {
    return $form
    ->schema([
      Tabs::make('Posts')->tabs([
        Tab::make('Headline')
          ->icon('heroicon-m-inbox')
          ->iconPosition(IconPosition::After)
          ->schema([
            TextInput::make('title')
              ->required(),
            TextInput::make('slug')
              ->required()
              ->unique(ignoreRecord: true),
            Select::make('category_id')
              ->label('Category')
              ->relationship('category', 'name')
              ->native(false)
              ->searchable()
              ->preload()
              ->required(),
            ColorPicker::make('color')
              ->required(),
          ])
          ->columns(2),

        Tab::make('Content')
          ->badge(0)
          ->schema([
            MarkdownEditor::make('content')
              ->required()
              ->columnSpanFull(),
          ]),

        Tab::make('Meta')
          ->schema([
            FileUpload::make('thumbnail')
              ->disk('public')
              ->directory('thumbnails'),
            TagsInput::make('tags')
              ->required(),
            Checkbox::make('published'),
          ])
      ])
      ->activeTab(1)
      ->persistTabInQueryString()
    ])->columns(1);
  }

  public static function table(Table $table): Table
  {
    return $table
    ->columns([
      ImageColumn::make('thumbnail')
        ->toggleable(),
      ColorColumn::make('color')
        ->toggleable(),
      TextColumn::make('title')
        ->sortable()
        ->searchable(),
      TextColumn::make('slug')
        ->toggleable()
        ->sortable()
        ->searchable(),
      TextColumn::make('category.name')
        ->toggleable()
        ->sortable()
        ->searchable(),
      TextColumn::make('tags'),
      CheckboxColumn::make('published')
        ->toggleable(),
      TextColumn::make('content')
        ->toggleable(),
      TextColumn::make('created_at')
        ->sortable()
        ->date('M d, Y H:i')
        ->toggleable(),
      TextColumn::make('updated_at')
        ->sortable()
        ->date('M d, Y H:i')
        ->toggleable(),
      ])
      ->defaultSort('created_at', 'desc')
      ->filters([
        Filter::make('Published')
          ->query(function (Builder $query): Builder {
            return $query->where('published', true);
          }),
        
        TernaryFilter::make('published')
          ->label('Published')
          ->native(false),

        SelectFilter::make('category_id')
          ->label('Category')
          ->relationship('category', 'name')
          ->searchable()
          ->preload()
          ->multiple()
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
      UsersRelationManager::class,
      CommentsRelationManager::class
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
