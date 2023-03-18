<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ListCurrencyRateExternalProviders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:list-currency-rate-external-providers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all the available external provider for the app:get-daily-currency-exchange-rates command';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->line('Below is the list of providers that can be used as argument for the command app:get-daily-currency-exchange-rates');
        $this->line('exchangerate_host');
        $this->line('currencylayer');
        $this->line('fixerio');
    }
}
