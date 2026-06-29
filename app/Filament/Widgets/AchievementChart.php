<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class AchievementChart extends ChartWidget
{
    protected ?string $heading = 'Achievement Chart';

    protected function getData(): array
    {
        return [
            //
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
