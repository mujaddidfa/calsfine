<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            DummyDataSeeder::class,
        ]);

        $this->call([
            AdminSeeder::class,
        ]);

        $this->call([
            TransactionSeeder::class,
        ]);

        $this->call([
            PickupTimeSeeder::class, // tambahkan baris ini
        ]);
    }
}
