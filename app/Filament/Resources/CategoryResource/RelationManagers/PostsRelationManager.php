<?php

namespace App\Filament\Resources\CategoryResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PostsRelationManager extends RelationManager
{
  protected static string $relationship = 'posts';

  public function form(Form $form): Form
  {
    return $form
      ->schema([
        Section::make('Post Details')
          ->description('Please fill in the post details below.')
          ->collapsible()
          ->schema([
            TextInput::make('title')
              ->required(),
            TextInput::make('slug')
              ->required()
              ->unique(ignoreRecord: true),
            ColorPicker::make('color')
              ->required(),
            MarkdownEditor::make('content')
              ->columnSpanFull(),
          ])
          ->columnSpan(2)
          ->columns(2),
          
          Group::make()
            ->schema([
              Section::make('Image')
                ->collapsible()
                ->schema([
                  FileUpload::make('thumbnail')
                    ->disk('public')
                    ->directory('thumbnails'),
                ]),

              Section::make('Meta')
                ->collapsible()
                ->schema([
                  TagsInput::make('tags')
                    ->required(),
                  Checkbox::make('published'),
                ])
            ])
            ->columnSpan(1)
            ->columns(1),
      ])
      ->columns(3);
  }

  public function table(Table $table): Table
  {
    return $table
      ->recordTitleAttribute('title')
      ->columns([
        ImageColumn::make('thumbnail')
          ->toggleable(isToggledHiddenByDefault: true),
        ColorColumn::make('color')
          ->toggleable(),
        TextColumn::make('title')
          ->sortable()
          ->searchable(),
        TextColumn::make('slug')
          ->toggleable()
          ->sortable()
          ->searchable(),
        TextColumn::make('tags'),
        CheckboxColumn::make('published')
          ->toggleable(),
        TextColumn::make('content')
          ->toggleable(isToggledHiddenByDefault: true),
        TextColumn::make('created_at')
          ->sortable()
          ->date('M d, Y H:i')
          ->toggleable(),
        TextColumn::make('updated_at')
          ->sortable()
          ->date('M d, Y H:i')
          ->toggleable(isToggledHiddenByDefault: true),
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
