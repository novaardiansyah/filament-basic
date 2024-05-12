<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TestWidget extends BaseWidget
{
  use InteractsWithPageFilters;
  
  protected function getStats(): array
  {
    return [
      Stat::make('New Users', User::when($this->filters['startDate'], function($query) {
        return $query->whereDate('created_at', '>', $this->filters['startDate']);
      })
        ->when($this->filters['endDate'], function($query) {
          return $query->whereDate('created_at', '<', $this->filters['endDate']);
        })
        ->count())
        ->description('User this months')
        ->descriptionIcon('heroicon-m-user-group', IconPosition::Before)
        // ->chart([0, 2])
        ->color('success'),
    ];
  }
}
