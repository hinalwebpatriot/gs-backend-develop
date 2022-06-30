<?php

use Illuminate\Database\Seeder;
use lenal\catalog\Models\Rings\RingSize;

class RingSizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RingSize::truncate();

        collect([
            [
                'slug' => 13.25,
                'size' => json_encode([
                    'us' => '2',
                    'au' => 'D',
                    'eu' => '13.25',
                ]),
            ],
            [
                'slug' => 13.75,
                'size' => json_encode([
                    'us' => '2 1/2',
                    'au' => 'E',
                    'eu' => '13.75',
                ]),
            ],
            [
                'slug' => 14,
                'size' => json_encode([
                    'us' => '3',
                    'au' => 'F',
                    'eu' => '14',
                ]),
            ],
            [
                'slug' => 14.5,
                'size' => json_encode([
                    'us' => '3 1/2',
                    'au' => 'G',
                    'eu' => '14.5',
                ]),
            ],
            [
                'slug' => 15,
                'size' => json_encode([
                    'us' => '4',
                    'au' => 'H',
                    'eu' => '15',
                ]),
            ],
            [
                'slug' => 15.25,
                'size' => json_encode([
                    'us' => '4 1/2',
                    'au' => 'I',
                    'eu' => '15.25',
                ]),
            ],
            [
                'slug' => 15.5,
                'size' => json_encode([
                    'us' => '4 3/4',
                    'au' => 'J',
                    'eu' => '15.5',
                ]),
            ],
            [
                'slug' => 16,
                'size' => json_encode([
                    'us' => '5 1/4',
                    'au' => 'K',
                    'eu' => '16',
                ]),
            ],
            [
                'slug' => 16.375,
                'size' => json_encode([
                    'us' => '5 3/4',
                    'au' => 'L',
                    'eu' => '16.375',
                ]),
            ],
            [
                'slug' => 16.75,
                'size' => json_encode([
                    'us' => '6 1/4',
                    'au' => 'M',
                    'eu' => '16.75',
                ]),
            ],
            [
                'slug' => 17.125,
                'size' => json_encode([
                    'us' => '6 3/4',
                    'au' => 'N',
                    'eu' => '17.125',
                ]),
            ],
            [
                'slug' => 17.25,
                'size' => json_encode([
                    'us' => '7',
                    'au' => 'O',
                    'eu' => '17.25',
                ]),
            ],
            [
                'slug' => 17.75,
                'size' => json_encode([
                    'us' => '7 1/2',
                    'au' => 'P',
                    'eu' => '17.75',
                ]),
            ],
            [
                'slug' => 18.125,
                'size' => json_encode([
                    'us' => '8',
                    'au' => 'Q',
                    'eu' => '18.125',
                ]),
            ],
            [
                'slug' => 18.5,
                'size' => json_encode([
                    'us' => '8 1/2',
                    'au' => 'R',
                    'eu' => '18.5',
                ]),
            ],
            [
                'slug' => 19,
                'size' => json_encode([
                    'us' => '9',
                    'au' => 'S',
                    'eu' => '19',
                ]),
            ],
            [
                'slug' => 19.625,
                'size' => json_encode([
                    'us' => '9 3/4',
                    'au' => 'T',
                    'eu' => '19.625',
                ]),
            ],
        ])->each(function ($size) {
            RingSize::create($size);
        });
    }
}
