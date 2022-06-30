<?php

use Illuminate\Database\Seeder;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'd',
            'email' => 'd@d.d',
            'password' => bcrypt('d'),
        ]);
        $user->markEmailAsVerified();
    }
}
