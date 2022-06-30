<?php

use Illuminate\Database\Seeder;
use lenal\catalog\Models\Diamonds\Color;
use lenal\catalog\Models\Diamonds\Clarity;
use lenal\MarginCalculate\Facades\MarginCalculate;
use lenal\MarginCalculate\Models\MarginCalculate as MarginCalculateModel;

/**
 * Class MarginSeeder
 */
class MarginSeeder extends Seeder
{
    const DEFAULT_MARGIN = 20;

    private $colors;
    private $clarities;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MarginCalculateModel::truncate();

        $this->loadColors();
        $this->loadClarities();

        collect([
            ['min' => 0.0, 'max' => 0.29],
            ['min' => 0.3, 'max' => 0.39],
            ['min' => 0.4, 'max' => 0.49],
            ['min' => 0.5, 'max' => 0.69],
            ['min' => 0.7, 'max' => 0.89],
            ['min' => 0.9, 'max' => 0.99],
            ['min' => 1.00, 'max' => 1.49],
            ['min' => 1.5, 'max' => 1.99],
            ['min' => 2.0, 'max' => 2.99],
            ['min' => 3.0, 'max' => 3.99],
            ['min' => 4.0, 'max' => 4.99],
            ['min' => 5.0, 'max' => 5.99],
            ['min' => 6.0, 'max' => 6.99],
            ['min' => 7.0, 'max' => 9.99],
            ['min' => 10.0, 'max' => 10.99],
            ['min' => 11.0, 'max' => 20.99],
            ['min' => 21.0, 'max' => 30.0],
        ])->each(function ($range) {
            $this->colors->each(function ($color) use ($range) {
                $this->clarities->each(function ($clarity) use ($range, $color) {
                    collect([1, 0])->each(function ($is_round) use ($range, $color, $clarity) {
                        MarginCalculate::setMargin([
                            'manufacturer' => null,
                            'is_round'     => $is_round,
                            'clarity'      => $clarity,
                            'color'        => $color,
                            'carat_min'    => $range['min'],
                            'carat_max'    => $range['max'],
                            'margin'       => self::DEFAULT_MARGIN,
                        ]);
                    });
                });
            });
        });
    }

    private function loadColors()
    {
        $this->colors = Color::all()->pluck('slug');
    }

    private function loadClarities()
    {
        $this->clarities = Clarity::all()->pluck('slug');
    }
}
