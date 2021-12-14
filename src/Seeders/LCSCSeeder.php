<?php

namespace Optimisthub\LCSC\Seeders;

use Illuminate\Database\Seeder;
use Optimisthub\LCSC\Models\City;
use Optimisthub\LCSC\Models\Country;
use Optimisthub\LCSC\Models\State;

class LCSCSeeder extends Seeder
{
    public function run()
    {
        $countries = json_decode(file_get_contents(__DIR__ . "/../countries+states+cities.json"));
        foreach ($countries as $countryData) {
            $country = Country::query()->updateOrCreate(
                [
                    "name" => $countryData->name,
                ]
                , [
                "iso3" => $countryData->iso3,
                "iso2" => $countryData->iso2,
                "numeric_code" => $countryData->numeric_code,
                "phone_code" => $countryData->phone_code,
                "capital" => $countryData->capital,
                "currency" => $countryData->currency,
                "currency_symbol" => $countryData->currency_symbol,
                "tld" => $countryData->tld,
                "native" => $countryData->native,
                "region" => $countryData->region,
                "latitude" => $countryData->latitude,
                "longitude" => $countryData->longitude,
            ]);
            echo "Adding " . $country->name . " state and cities... ";

            foreach ($countryData->states as $stateData) {
                $state = State::query()->updateOrCreate(
                    [
                        "country_id" => $country->id,
                        "name" => $stateData->name,
                    ]
                    , [
                    "code" => $stateData->state_code,
                    "latitude" => $stateData->latitude,
                    "longitude" => $stateData->longitude,
                ]);

                foreach ($stateData->cities as $cityData) {
                    City::query()->updateOrCreate(
                        [
                            "state_id" => $state->id,
                            "name" => $cityData->name,
                        ]
                        , [
                        "latitude" => $cityData->latitude,
                        "longitude" => $cityData->longitude
                    ]);
                }
            }
            echo "done." . PHP_EOL;
        }

    }
}
