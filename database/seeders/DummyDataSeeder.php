<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Category;
use App\Models\Menu;
use App\Models\Location;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::factory(3)->create();

        Menu::factory(5)->state(function () {
            return [
                'category_id' => Category::inRandomOrder()->first()->id,
            ];
        })->create();

        Location::factory(3)->create();
    }
}
