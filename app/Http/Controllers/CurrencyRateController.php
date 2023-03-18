<?php

namespace App\Http\Controllers;

use App\Models\CurrencyRate;
use Illuminate\Support\Facades\Http;

class CurrencyRateController extends Controller
{
    /**
     * Retrieve the latest currency rates data from the specified provider then upsert it in currency_rate table
     *
     * @param string $provider
     * @return mixed
     */
    public function getExternalCurrencyRate(string $provider): array
    {
        // HTTP call to the url of the specified $provider from the currency-rate-external-providers config file
        $response = Http::get(config('currency-rate-external-providers.' . $provider . '.base_url'));
        // name of the $dataTransformer method needed to normalize the data of the api
        // comes from the currency-rate-external-providers config file
        $dataTransformerMethodName = config('currency-rate-external-providers.' . $provider . '.method');
        // call the $dataTransformer method of the specifier $provider with the data of the api
        $upsertData = $this->$dataTransformerMethodName($response->json());
        // upsert the normalized data in the table
        $this->upsertCurrencyRateBulk($upsertData);

        return $upsertData;
    }

    /**
     * Upsert an array of currency_rate in bulk
     *
     * @param array $data
     * @return void
     */
    public function upsertCurrencyRateBulk(array $data): void
    {
        CurrencyRate::query()->upsert($data, ['code'], ['rate', 'rate_date']);
    }

    /**
     * Specific method for the exchangerate_host provider
     * format data to an array of currency rate to upsert it in the database afterwards
     *
     * @param array $data
     * @return array
     */
    public function exchangerateHostDataTransformer(array $data): array
    {
        $newData = [];
        foreach ($data['rates'] as $code => $rate) {
            $currency['code'] = $code;
            $currency['rate'] = $rate;
            $currency['rate_date'] = $data['date'];
            $newData[] = $currency;
        }

        return $newData;
    }

    /**
     * Specific method for the currencylayer provider
     * format data to an array of currency rate to upsert it in the database afterwards
     *
     * @param array $data
     * @return array
     */
    public function currencylayerDataTransformer(array $data): array
    {
        $newData = [];
        foreach ($data['usd'] as $code => $rate) {
            $currency['code'] = strtoupper($code);
            $currency['rate'] = $rate;
            $currency['rate_date'] = $data['date'];
            $newData[] = $currency;
        }

        return $newData;
    }
}
