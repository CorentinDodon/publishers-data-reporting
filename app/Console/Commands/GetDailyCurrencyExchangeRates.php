<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GetDailyCurrencyExchangeRates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-daily-currency-exchange-rates {provider=exchangerate_host}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieve and save latest currency rate from the specified provider';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $controller = app()->make('App\Http\Controllers\CurrencyRateController');
        try {
            app()->call([$controller, 'getExternalCurrencyRate'], ['provider' => $this->argument('provider')]);
            $this->info('Currency rates have been updated with the data from the provider ' . $this->argument('provider') .' api!');
        } catch(\Exception) {
            $this->error('Something went wrong, you can try another provider!');

        }
    }
}
