<?php

use Illuminate\Database\Seeder;
use lenal\ShowRooms\Models\ShowRoomCountry;
use lenal\ShowRooms\Models\ShowRoom;
use Faker\Factory as Faker;

class ShowRoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countries = [
            'AU' => 'Australia',
            'GB' => 'Great Britain',
            'CHN' => 'China'
        ];
        foreach ($countries as $code => $country) {
            $country = ShowRoomCountry::create([
                'code' => $code,
                'title->en' => $country,
                'title->zh' => $country,
                'title->ru' => $country,
            ]);
            $countriesId[] = $country->id;
        }
        factory(ShowRoom::class, 10)->create();
    }
}
