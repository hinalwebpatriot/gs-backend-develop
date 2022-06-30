<?php

use Illuminate\Database\Seeder;
use lenal\catalog\Models\Paysystem;

class PaysystemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Paysystem
            ::firstOrCreate(
                ['slug' => 'paypal'],
                [
                    'name' => 'Paypal',
                    'description' => [
                        'en' => 'PayPal is the faster, safer way to send money, make an online payment, receive money or set up a merchant account.'
                ]
            ])
            ->update(['type' => 'credit_card']);
        Paysystem
            ::firstOrCreate(
                [ 'slug' => 'adyen' ],
                [
                    'name' => 'Adyen',
                    'description' => [
                        'en' => 'We provide a single payments platform globally to accept payments and grow revenue online, on mobile, and at the point of sale.'
                ]
            ])
            ->update(['type' => 'credit_card']);
        Paysystem
            ::firstOrCreate(
                [ 'slug' => 'skye' ],
                [
                    'name' => 'Skye Mastercard',
                    'description' => [
                        'en' => 'Skye Mastercard allows you to convert purchases of $250 or more to your choice of 3 Interest Free Instalment Plans'
                ]
            ])
            ->update(['type' => 'credit_card']);
        Paysystem
            ::firstOrCreate(
                [ 'slug' => 'bank_transfer' ],
                [
                    'name' => 'Bank transfer',
                    'description' => [
                        'en' => 'You will get an invoice you can print and pay'
                    ],
                    'type' => 'other'
                ]);

    }
}
