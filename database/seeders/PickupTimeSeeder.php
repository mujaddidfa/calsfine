<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Location;
use App\Models\PickupTime;

class PickupTimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua lokasi yang aktif
        $locations = Location::where('is_active', true)->get();

        // Jam pickup umum yang bisa digunakan untuk semua lokasi
        $commonPickupTimes = [
            '08:00:00',
            '09:00:00',
            '10:00:00',
            '11:00:00',
            '12:00:00',
            '13:00:00',
            '14:00:00',
            '15:00:00',
            '16:00:00',
            '17:00:00',
        ];

        foreach ($locations as $location) {
            foreach ($commonPickupTimes as $time) {
                PickupTime::create([
                    'location_id' => $location->id,
                    'pickup_time' => $time,
                    'is_active' => true,
                ]);
            }
        }

        $this->command->info('Pickup times seeded successfully!');
    }
}
