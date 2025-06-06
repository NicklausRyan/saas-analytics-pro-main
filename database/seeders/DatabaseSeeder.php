<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {        // Uncomment the line below if UsersTableSeeder exists
        // $this->call(UsersTableSeeder::class);
        
        // Call our test data seeder
        $this->call(TestDataSeeder::class);
        
        // Call the revenue attribution seeder
        $this->call(RevenueAttributionSeeder::class);
    }
}
