<?php

namespace App\Console\Commands;

use App\Models\Domain;
use Illuminate\Console\Command;

class ReleaseDomainReservations extends Command
{
    protected $signature = 'domains:release-expired';
    protected $description = 'Release expired domain reservations';

    public function handle()
    {
        $expiredReservations = Domain::expiredReservations()->get();

        foreach ($expiredReservations as $domain) {
            $domain->releaseReservation();
            $this->info("Released reservation for: {$domain->name}");
        }

        $this->info("Released {$expiredReservations->count()} expired reservations");
    }
}
