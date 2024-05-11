<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UserResource extends Resource
{
  protected static ?string $model = User::class;

  protected static ?string $navigationIcon = 'heroicon-o-users';
  protected static ?string $navigationGroup = 'User';

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Section::make('User details')
          ->description('Please fill your user details.')
          ->schema([
            TextInput::make('name')
              ->required(),
            TextInput::make('email')
              ->email()
              ->required(),
            TextInput::make('password')
              ->password()
              ->revealable()
              ->required()
              ->minLength(7)
              ->maxLength(20)
              ->visibleOn('create')
          ])
          ->columns(2)
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        TextColumn::make('name'),
        TextColumn::make('email'),
        TextColumn::make('created_at')
          ->label('Member Since')
          ->date('M d, Y H:i'),
        TextColumn::make('updated_at')
          ->label('Last Update')
          ->date('M d, Y H:i')
          ->toggleable()
      ])
      ->filters([
        //
      ])
      ->actions([
        Tables\Actions\EditAction::make(),
      ])
      ->bulkActions([
        // Tables\Actions\BulkActionGroup::make([
        //   Tables\Actions\DeleteBulkAction::make(),
        // ]),
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
      'index'  => Pages\ListUsers::route('/'),
      'create' => Pages\CreateUser::route('/create'),
      'edit'   => Pages\EditUser::route('/{record}/edit'),
    ];
  }
}
