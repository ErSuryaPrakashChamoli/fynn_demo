<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Customer;

class AchievementChart extends ChartWidget
{

    protected static ?int $sort = 3;
    // protected ?string $heading = 'Achievement Chart';
     protected ?string $heading = 'Monthly Loan Achievement';

    protected function getData(): array
    {
         $data = [];

        foreach (range(1, 12) as $month) {
            $data[] = Customer::whereYear('created_at', now()->year)
            ->whereMonth('created_at', $month)
            ->sum('sanctioned_loan_amount');
        }
          return [
            'datasets' => [
                [
                    'label' => 'Loan Amount',
                    'data' => $data,
                ],
            ],
            'labels' => [
                'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec',
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
