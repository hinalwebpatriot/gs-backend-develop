<?php

use Illuminate\Database\Seeder;
use lenal\catalog\Models\Status;

class StatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Status::create([
            'title' => 'Prossesing',
            'slug' => 'prossesing'
        ]);
        Status::create([
            'title' => 'Invoice bank transfer',
            'slug' => 'bank_transfer'
        ]);
        Status::create([
            'title' => 'Invoice Adyen',
            'slug' => 'adyen'
        ]);
        Status::create([
            'title' => 'Invoice paypal',
            'slug' => 'paypal'
        ]);
        Status::create([
            'title' => 'Invoice alipay',
            'slug' => 'alipay'
        ]);
        Status::create([
            'title' => 'Рaid',
            'slug' => 'paid'
        ]);
        Status::create([
            'title' => 'Cancel',
            'slug' => 'cancel'
        ]);
        Status::create([
            'title' => 'Waiting for payment',
            'slug' => 'wait'
        ]);
        Status::create([
            'title' => 'Complete',
            'slug' => 'complete'
        ]);
        Status::create([
            'title' => 'In progress',
            'slug' => 'progress'
        ]);
    }
}
