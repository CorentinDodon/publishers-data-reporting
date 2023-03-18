<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\CurrencyRate;
use App\Models\Publisher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Publisher::factory(10)->hasReportingData(100)->create();

        CurrencyRate::factory()->count(155)->create();
        // update base currency USD rate to 1
        DB::table('currency_rate')->where('code', 'USD')->update(['rate' => 1]);
    }
}
