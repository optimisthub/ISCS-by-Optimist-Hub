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
        ini_set('memory_limit', '-1');

        $remoteJson = 'https://raw.githubusercontent.com/dr5hn/countries-states-cities-database/master/countries+states+cities.json';

        $fileSize = $this->remoteFileSize($remoteJson);

        $this->info('Fetching countries from remote source...['.$fileSize.']');

        $countries = json_decode($this->remoteGet($remoteJson));
        //$countries = json_decode(file_get_contents(__DIR__ . "/../countries+states+cities.json"));
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
            $this->info("Adding " . $country->name . " state and cities... ");

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
            $this->info("#### Done. ####");
        }
    }

    /**
     * Get Remote File
     *
     * @param [string] $url
     * @return void
     */
    private function remoteGet( $url )
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }

    /**
     * Get file size from remote source
     *
     * @param [string] $url
     * @return void
     */
    private function remoteFileSize( $url )
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
        curl_setopt($ch, CURLOPT_NOBODY, TRUE);
        $data = curl_exec($ch);
        $size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
        curl_close($ch);
        $bytes = $size;
        if ($bytes > 0)
        {
            $unit = intval(log($bytes, 1024));
            $units = array('B', 'KB', 'MB', 'GB');
            if (array_key_exists($unit, $units) === true)
            {
                return sprintf('%d %s', $bytes / pow(1024, $unit), $units[$unit]);
            }
        }
        return $bytes;
    }

    /**
     * Console output
     *
     * @param [string] $string
     * @return void
     */
    private function info($string)
    {
        echo $string . PHP_EOL;
    }
}
