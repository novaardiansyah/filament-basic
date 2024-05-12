<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;

class Dashboard extends \Filament\Pages\Dashboard
{
  use HasFiltersForm;

  public function filtersForm(Form $form): Form
  {
    return $form->schema([
      Section::make('Filters')
        ->description('Filter your dashboard using the form below.')
        ->schema([
          TextInput::make('name'),
          DatePicker::make('startDate')
            ->native(false),
          DatePicker::make('endDate')
            ->native(false)
        ])
        ->columns(3)
    ]);
  }
}
