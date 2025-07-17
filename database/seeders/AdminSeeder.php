<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Use updateOrInsert to avoid duplicate entries
        DB::table('admins')->updateOrInsert(
            ['username' => 'admin'],
            [
                'username' => 'admin',
                'password' => Hash::make('CalsFine2025!'), // Strong password
            ]
        );
        
        // Verify admin exists with ID
        $admin = DB::table('admins')->where('username', 'admin')->first();
        if ($admin) {
            echo "Admin created with ID: " . $admin->id . "\n";
        }
    }
}
