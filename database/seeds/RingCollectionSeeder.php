<?php

use Illuminate\Database\Seeder;
use lenal\catalog\Models\Rings\RingCollection;

class RingCollectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RingCollection::truncate();

        collect([
            [
                'title'       => [
                    'en' => 'Angeli',
                ],
                'slug'        => 'angeli',
                'description' => [
                    'en' => 'An ethereal beauty like no other. The Angeli collection showcases craftsmanship to show-stopping effect. An utterly romantic, otherworldly experience for the lady lucky enough to wear it.',
                ]
            ],
            [
                'title'       => [
                    'en' => 'Alta',
                ],
                'slug'        => 'alta',
                'description' => [
                    'en' => 'Fall in love with the opulent curves of Alta. Crafted to perfection, luminous diamonds radiate elegance, glamour and beauty. This is a collection dedicated to the great modern love story – yours.',
                ]
            ],
            [
                'title'       => [
                    'en' => 'Cara',
                ],
                'slug'        => 'cara',
                'description' => [
                    'en' => 'Indulge her with unapologetic attention to detail. Diamonds and gold are crafted to covetable effect in the striking Cara collection. Let your love shine with a distinctive piece that transcends time and place.',
                ]
            ],
            [
                'title'       => [
                    'en' => 'Calista',
                ],
                'slug'        => 'calista',
                'description' => [
                    'en' => 'A stylish symbol of love. Cultivated edges and glittering stones make for understated glamour. A truly refined offering with a chic attitude, Calista is the collection for the modern sophisticate.',
                ]
            ],
            [
                'title'       => [
                    'en' => 'Cathedral',
                ],
                'slug'        => 'cathedral',
                'description' => [
                    'en' => 'The pinnacle of beauty and grace. Sparkling diamonds are cradled in a sculptural silhouette –  a true monument to your love. Exuding precision and artistic perfection, the Cathedral collection is stunning in its simplicity.',
                ]
            ],
            [
                'title'       => [
                    'en' => 'Dalia',
                ],
                'slug'        => 'dalia',
                'description' => [
                    'en' => 'Let your love bloom with Dalia. Diamonds and gold pirouette in an object of desire as ornate and beautiful as its namesake. With its flourishing design, this collection personifies the very nature of romance.',
                ]
            ],
            [
                'title'       => [
                    'en' => 'Jana',
                ],
                'slug'        => 'jana',
                'description' => [
                    'en' => 'Enchant her with the intricate Jana collection. Woven gold creates a sense of movement and flow around an exquisite diamond – the ultimate symbol of eternal love. This is a collection fit  for a princess.',
                ]
            ],
            [
                'title'       => [
                    'en' => 'Rene',
                ],
                'slug'        => 'rene',
                'description' => [
                    'en' => 'It’s time for her star turn. Embody the glamour of Hollywood romance with timeless masterpieces of diamonds and gold. Rene is a red carpet-worthy collection made for the modern siren.',
                ]
            ],
            [
                'title'       => [
                    'en' => 'Reverie',
                ],
                'slug'        => 'reverie',
                'description' => [
                    'en' => 'Regal. Glittering. Gorgeous. An arresting diamond crowns impeccable design to deliver the ultimate in wow-factor jewellery. The stately Revere collection turns heads for all the right reasons.',
                ]
            ],
            [
                'title'       => [
                    'en' => 'Selena',
                ],
                'slug'        => 'selena',
                'description' => [
                    'en' => 'Beauty is in the detail, elegance in the style. Encircled by contemporary design, a superbly polished stone brings the cultivated Selena to life. This collection is a contemporary take on classic diamonds.',
                ]
            ],
            [
                'title'       => [
                    'en' => 'Snowflake',
                ],
                'slug'        => 'snowflake',
                'description' => [
                    'en' => 'Designed for a love story as unique as yours. Baroque gold and icy diamonds captivate in the dreamy Snowflake collection. Gift her the jewellery she’s dreamt about ever since she was a little girl.',
                ]
            ],
            [
                'title'       => [
                    'en' => 'Sol',
                ],
                'slug'        => 'sol',
                'description' => [
                    'en' => 'A fusion of everlasting light and love. Taking inspiration from the powerful beauty of the sun, the Sol collection shimmers in eternal circles of gold and diamonds. A decadent piece fit for a goddess.',
                ]
            ],
            [
                'title'       => [
                    'en' => 'Tribute',
                ],
                'slug'        => 'tribute',
                'description' => [
                    'en' => 'Herald a lifetime of love to come. She’ll be in awe of the unmistakable design and fine materials melded to precision in the Tribute collection. Capture her air and grace with an absolutely unforgettable piece.',
                ]
            ],
            [
                'title'       => [
                    'en' => 'Tulip',
                ],
                'slug'        => 'tulip',
                'description' => [
                    'en' => 'Engagement ring',
                ]
            ],
            [
                'title'       => [
                    'en' => 'Vita',
                ],
                'slug'        => 'vita',
                'description' => [
                    'en' => 'Amore! Celebrate your love with decadent Vita. Coiled gold cradles exquisitely crafted diamonds in a dazzling display of beauty. The Vita collection captures a passion for the finer things in life. ',
                ]
            ],
        ])->each(function ($ring_collection) {
            RingCollection::create($ring_collection);
        });
    }
}
