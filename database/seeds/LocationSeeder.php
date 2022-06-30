<?php

use Illuminate\Database\Seeder;
use lenal\AppSettings\Models\Location;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $locations = [
            "AU" => "Australia",
            "QA" => "Qatar",
            "RU" => "Russia",
            "SS" => "South Sudan",
            "SD" => "Sudan",
            "SY" => "Syria",
            "UA" => "Ukraine",
            "NZ" => "New Zealand",
            "AT" => "Austria",
            "BM" => "Bermuda",
            "CA" => "Canada",
            "DK" => "Denmark",
            "FI" => "Finland",
            "DE" => "Germany",
            "GR" => "Greece",
            "HK" => "Hong Kong",
            "IE" => "Ireland",
            "IT" => "Italy",
            "JP" => "Japan",
            "NL" => "Netherlands",
            "NO" => "Norway",
            "PT" => "Portugal",
            "SG" => "Singapore",
            "ES" => "Spain",
            "SE" => "Sweden",
            "TT" => "Trinidad and Tobago",
            "AE" => "United Arab Emirates",
            "GB" => "United Kingdom",
            "US" => "United states",
            "CU" => "Cuba",
            "IR" => "Iran",
            "KP" => "North Korea",
        ];
        foreach ($locations as $code => $location) {
            try {
                Location::create([
                    'code' => $code,
                    'name->en' => $location,
                    'shipment' => false
                ]);
            } catch (Exception $exception) {
                dump($exception->getMessage());
                continue;
            }
        }
        // locations, where shipment is available
        $shipmentLocations = Location::whereIn('code', [
            "AU", "NZ","AT", "BM", "CA", "DK", "FI", "DE", "GR", "HK", "IE", "IT", "JP", "NL", "NO", "PT", "SG","ES",
            "SE", "TT", "AE", "GB", "US"
        ])->get();
        foreach ($shipmentLocations as $location) {
            $location->shipment = true;
            $location->save();
        }
    }
}
