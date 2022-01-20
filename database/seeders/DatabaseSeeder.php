<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        // \App\Models\User::factory(10)->create();
        // Create 10 records of customers
        \App\Models\User::factory(5)->create()->each(function ($customer) {
            // Seed the relation with one address
            $details = \App\Models\UserDetail::factory()->make();
            $customer->userDetail()->save($details);            
        });
    }
}
