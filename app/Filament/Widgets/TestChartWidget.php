<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Carbon;

class TestChartWidget extends ChartWidget
{
  use InteractsWithPageFilters;

  protected static ?string $heading = 'Testing Chart';
  protected int | string | array $columnSpan = 1;

  protected function getData(): array
  {
    $startDate = $this->filters['startDate'];
    $endDate = $this->filters['endDate'];

    $data = Trend::model(User::class)
        ->between(
            start: $startDate ? Carbon::parse($startDate) : now()->subMonths(6),
            end: $endDate ? Carbon::parse($endDate) : now(),
        )
        ->perMonth()
        ->count();

    return [
      'datasets' => [
        [
          'label' => 'Blog posts created',
          'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
        ],
      ],
      'labels' => $data->map(fn (TrendValue $value) => $value->date),
    ];
  }

  protected function getType(): string
  {
    return 'line';
  }
}
