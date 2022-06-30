<?php

use Illuminate\Database\Seeder;
use lenal\catalog\Models\Paysystem;

class AlipayPaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!Paysystem::query()->where('slug', 'alipay')->exists()) {
            Paysystem::query()->create([
                'id' => 5,
                'slug' => 'alipay',
                'name' => 'Alipay',
                'description' => json_encode(['en' => 'Alipay'], JSON_UNESCAPED_SLASHES),
                'type' => 'other',
                'credentials' => json_encode([
                    'store_name' => 'store_name',
                    'merchant_id' => 'merchant_id',
                    'recevice_id' => 'recevice_id',
                    'key' => 'key',
                ], JSON_UNESCAPED_SLASHES),
            ]);
        }
    }
}
