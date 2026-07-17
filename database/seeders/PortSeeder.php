<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Port;
use Illuminate\Database\Seeder;

class PortSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $portsData = [
            'id' => [
                ['name' => 'Tanjung Priok', 'code' => 'IDTPP', 'latitude' => -6.1034, 'longitude' => 106.8794],
                ['name' => 'Tanjung Perak', 'code' => 'IDTPE', 'latitude' => -7.2045, 'longitude' => 112.7371],
                ['name' => 'Belawan', 'code' => 'IDBLW', 'latitude' => 3.7842, 'longitude' => 98.6917],
            ],
            'de' => [
                ['name' => 'Port of Hamburg', 'code' => 'DEHAM', 'latitude' => 53.5456, 'longitude' => 9.9678],
                ['name' => 'Port of Bremen', 'code' => 'DEBRE', 'latitude' => 53.1189, 'longitude' => 8.7042],
            ],
            'cn' => [
                ['name' => 'Port of Shanghai', 'code' => 'CNSHA', 'latitude' => 31.2222, 'longitude' => 121.5397],
                ['name' => 'Port of Shenzhen', 'code' => 'CNSZX', 'latitude' => 22.5089, 'longitude' => 113.9069],
                ['name' => 'Port of Ningbo-Zhoushan', 'code' => 'CNNGB', 'latitude' => 29.8406, 'longitude' => 121.5606],
            ],
            'au' => [
                ['name' => 'Port of Sydney', 'code' => 'AUSYD', 'latitude' => -33.8608, 'longitude' => 151.2136],
                ['name' => 'Port of Melbourne', 'code' => 'AUMEL', 'latitude' => -37.8286, 'longitude' => 144.9282],
            ],
            'us' => [
                ['name' => 'Port of Los Angeles', 'code' => 'USLAX', 'latitude' => 33.7288, 'longitude' => -118.2620],
                ['name' => 'Port of New York & New Jersey', 'code' => 'USNYNJ', 'latitude' => 40.6723, 'longitude' => -74.1287],
            ],
            'sg' => [
                ['name' => 'Port of Singapore', 'code' => 'SGSIN', 'latitude' => 1.2661, 'longitude' => 103.8344],
            ],
            'gb' => [
                ['name' => 'Port of London', 'code' => 'GBLON', 'latitude' => 51.5042, 'longitude' => 0.0514],
                ['name' => 'Port of Southampton', 'code' => 'GBSOU', 'latitude' => 50.9038, 'longitude' => -1.4042],
            ],
        ];

        foreach ($portsData as $iso2 => $ports) {
            $country = Country::where('iso2', $iso2)->first();
            if ($country) {
                foreach ($ports as $port) {
                    Port::updateOrCreate(
                        ['code' => $port['code']],
                        [
                            'name' => $port['name'],
                            'latitude' => $port['latitude'],
                            'longitude' => $port['longitude'],
                            'country_id' => $country->id,
                        ]
                    );
                }
            }
        }

        // Dynamically add a default port for every other country in the database
        $allCountries = Country::all();
        foreach ($allCountries as $country) {
            $hasPort = Port::where('country_id', $country->id)->exists();
            if (!$hasPort) {
                // Generate a port name and slightly offset coordinates so they don't land exactly on the country center if it's inland, or just use the center
                $portName = 'Port of ' . $country->name;
                $code = strtoupper($country->iso3 ?? ($country->iso2 . 'X')) . 'PRT';
                
                // Add a small offset to latitude/longitude for variety
                $lat = $country->latitude ? ($country->latitude + 0.1) : 0;
                $lng = $country->longitude ? ($country->longitude + 0.1) : 0;

                Port::updateOrCreate(
                    ['code' => $code],
                    [
                        'name' => $portName,
                        'latitude' => $lat,
                        'longitude' => $lng,
                        'country_id' => $country->id,
                    ]
                );
            }
        }
    }
}
