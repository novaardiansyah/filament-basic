<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CommentResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers\CommentsRelationManager;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Components\MorphToSelect\Type;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CommentResource extends Resource
{
  protected static ?string $model = Comment::class;

  protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
  protected static ?string $navigationGroup = 'Blog';

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Group::make()
          ->schema([
            Section::make('Author')
              ->description('The author of the comment')
              ->schema([
                Select::make('user_id')
                  ->relationship('user', 'name')
                  ->preload()
                  ->searchable()
                  ->required(),
                TextInput::make('comment')
                  ->required(),
                MorphToSelect::make('commentable')
                  ->types([
                    Type::make(Post::class)->titleAttribute('title'),
                    Type::make(User::class)->titleAttribute('email'),
                    Type::make(Comment::class)->titleAttribute('id')
                  ])
                  ->native(false)
                  ->searchable()
                  ->preload()
              ])
              ->columns(2)
          ])
          ->columnSpanFull()
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        TextColumn::make('user.name')
          ->searchable(),
        TextColumn::make('commentable_type'),
        TextColumn::make('commentable_id'),
        TextColumn::make('comment')
          ->searchable(),
        TextColumn::make('created_at')
          ->date('M d, Y H:i')
          ->toggleable(),
        TextColumn::make('updated_at')
          ->date('M d, Y H:i')
          ->toggleable(isToggledHiddenByDefault: true),
      ])
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
      CommentsRelationManager::class
    ];
  }

  public static function getPages(): array
  {
    return [
      'index' => Pages\ListComments::route('/'),
      'create' => Pages\CreateComment::route('/create'),
      'edit' => Pages\EditComment::route('/{record}/edit'),
    ];
  }
}
