<?php

namespace App\Filament\Widgets;

use App\Models\Book;
use Filament\Widgets\ChartWidget;

class BooksAvailabilityChart extends ChartWidget
{
    protected static ?string $heading = 'Books Availability';

    protected function getData(): array
    {
        $total = Book::count();

        $borrowed = Book::where('is_borrowed', true)
            ->orWhere('status', 'borrowed')
            ->count();

        $available = max(0, $total - $borrowed);

        // Simpan nilai ke property supaya bisa dipakai di heading
        $this->available = $available;
        $this->borrowed = $borrowed;

        return [
            'datasets' => [
                [
                    'label' => 'Books',
                    'data' => [
                        $available,
                        $borrowed,
                    ],
                ],
            ],
            'labels' => ['Available', 'Borrowed'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    public function getDescription(): ?string
    {
        return "Available: {$this->available} — Borrowed: {$this->borrowed}";
    }
}
