<?php

use Illuminate\Database\Seeder;
use lenal\social\Models\ContactLink;

class ContactLinksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $services = [
            'skype',
            'phone',
            'email',
            'messenger',
            'whatsapp',
            'wechat',
            'viber',
            'telegram',
        ];
        foreach ($services as $service) {
            ContactLink::create([
                'service' => $service
            ]);
        }

    }
}
