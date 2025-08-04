<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Domain;
use App\Models\Template;
use Filament\Widgets\StatsOverviewWidget as BaseStatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseStatsOverviewWidget
{
    protected static string $view = 'filament-widgets::stats-overview-widget'; // Gunakan view default Filament

    protected function getStats(): array
    {
        return [
            Stat::make('Total Orders', $this->getData()['total_orders'])
                ->description('Jumlah total pesanan')
                ->color('primary'),
            Stat::make('Pending Orders', $this->getData()['pending_orders'])
                ->description('Pesanan yang menunggu')
                ->color('warning'),
            Stat::make('Paid Orders', $this->getData()['paid_orders'])
                ->description('Pesanan yang sudah dibayar')
                ->color('success'),
            Stat::make('Completed Orders', $this->getData()['completed_orders'])
                ->description('Pesanan yang selesai')
                ->color('success'),
            Stat::make('Total Domains', $this->getData()['total_domains'])
                ->description('Jumlah total domain')
                ->color('info'),
            Stat::make('Available Domains', $this->getData()['available_domains'])
                ->description('Domain yang tersedia')
                ->color('success'),
            Stat::make('Reserved Domains', $this->getData()['reserved_domains'])
                ->description('Domain yang dipesan')
                ->color('warning'),
            Stat::make('Total Templates', $this->getData()['total_templates'])
                ->description('Jumlah total template')
                ->color('info'),
            Stat::make('Total Revenue', 'Rp ' . number_format($this->getData()['total_revenue'], 2))
                ->description('Total pendapatan dari pesanan')
                ->color('success'),
        ];
    }

    protected function getData(): array
    {
        return [
            'total_orders' => Order::count(),
            'pending_orders' => Order::pending()->count(),
            'paid_orders' => Order::paid()->count(),
            'completed_orders' => Order::completed()->count(),
            'total_domains' => Domain::count(),
            'available_domains' => Domain::available()->count(),
            'reserved_domains' => Domain::reserved()->count(),
            'total_templates' => Template::count(),
            'total_revenue' => Order::sum('total_price'),
        ];
    }

    protected int | string | array $columnSpan = 2; // Atur lebar kolom (opsional)
}
