<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class TestChartWidget extends ChartWidget
{
  protected static ?string $heading = 'Testing Chart';
  protected int | string | array $columnSpan = 1;

  protected function getData(): array
  {
    $data = Trend::model(User::class)
        ->between(
            start: now()->subMonths(6),
            end: now(),
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
