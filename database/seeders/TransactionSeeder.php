<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Menu;
use App\Models\Location;
use Carbon\Carbon;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all menus and locations
        $menus = Menu::where('is_active', 1)->get();
        $locations = Location::where('is_active', 1)->get();
        
        if ($menus->isEmpty() || $locations->isEmpty()) {
            $this->command->error('Please run Menu and Location seeders first!');
            return;
        }

        // Create transactions for the last 30 days
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $orderDate = $date->format('Y-m-d');
            $pickupDate = $date->format('Y-m-d H:i:s');
            
            // Create 3-8 transactions per day
            $transactionCount = rand(3, 8);
            
            for ($j = 0; $j < $transactionCount; $j++) {
                // Random pickup time between 10:00 and 16:00
                $pickupHour = rand(10, 16);
                $pickupMinute = rand(0, 59);
                $finalPickupDate = Carbon::parse($orderDate)->setTime($pickupHour, $pickupMinute);
                
                // Determine status based on date
                if ($i == 0) {
                    // Today: mix of all statuses
                    $statuses = ['pending', 'paid', 'completed', 'cancelled', 'wasted'];
                    $status = $statuses[array_rand($statuses)];
                } elseif ($i == 1) {
                    // Yesterday: mostly completed, some cancelled/wasted
                    $statuses = ['completed', 'completed', 'completed', 'cancelled', 'wasted'];
                    $status = $statuses[array_rand($statuses)];
                } else {
                    // Older dates: mostly completed, some cancelled/wasted
                    $statuses = ['completed', 'completed', 'completed', 'completed', 'cancelled', 'wasted'];
                    $status = $statuses[array_rand($statuses)];
                }
                
                // Create transaction
                $transaction = Transaction::create([
                    'customer_name' => $this->generateRandomName(),
                    'wa_number' => $this->generateRandomPhone(),
                    'location_id' => $locations->random()->id,
                    'order_date' => $orderDate,
                    'pick_up_date' => $finalPickupDate,
                    'total_price' => 0, // Will be calculated after items
                    'status' => $status,
                    'note' => rand(1, 10) > 7 ? 'Pesanan khusus: ' . $this->generateRandomNote() : null,
                ]);

                // Add 1-4 items per transaction
                $itemCount = rand(1, 4);
                $totalPrice = 0;
                
                $usedMenus = [];
                for ($k = 0; $k < $itemCount; $k++) {
                    // Avoid duplicate menus in same transaction
                    $availableMenus = $menus->whereNotIn('id', $usedMenus);
                    if ($availableMenus->isEmpty()) {
                        break;
                    }
                    
                    $menu = $availableMenus->random();
                    $usedMenus[] = $menu->id;
                    $quantity = rand(1, 3);
                    
                    TransactionItem::create([
                        'transaction_id' => $transaction->id,
                        'menu_id' => $menu->id,
                        'qty' => $quantity,
                        'price_per_item' => $menu->price,
                        'total_price' => $menu->price * $quantity,
                    ]);
                    
                    $totalPrice += ($menu->price * $quantity);
                }
                
                // Update transaction total price
                $transaction->update(['total_price' => $totalPrice]);
            }
        }

        // Create some transactions for tomorrow (H-1 orders)
        $tomorrow = Carbon::now()->addDay();
        $tomorrowOrderDate = $tomorrow->format('Y-m-d');
        
        // Create 5-10 transactions for tomorrow
        $tomorrowTransactionCount = rand(5, 10);
        
        for ($j = 0; $j < $tomorrowTransactionCount; $j++) {
            // Random pickup time between 10:00 and 16:00
            $pickupHour = rand(10, 16);
            $pickupMinute = rand(0, 59);
            $finalPickupDate = Carbon::parse($tomorrowOrderDate)->setTime($pickupHour, $pickupMinute);
            
            // Tomorrow orders are mostly pending or paid
            $statuses = ['pending', 'pending', 'paid', 'paid', 'paid'];
            $status = $statuses[array_rand($statuses)];
            
            // Create transaction
            $transaction = Transaction::create([
                'customer_name' => $this->generateRandomName(),
                'wa_number' => $this->generateRandomPhone(),
                'location_id' => $locations->random()->id,
                'order_date' => $tomorrowOrderDate,
                'pick_up_date' => $finalPickupDate,
                'total_price' => 0, // Will be calculated after items
                'status' => $status,
                'note' => rand(1, 10) > 8 ? 'Order H-1: ' . $this->generateRandomNote() : null,
            ]);

            // Add 1-3 items per transaction
            $itemCount = rand(1, 3);
            $totalPrice = 0;
            
            $usedMenus = [];
            for ($k = 0; $k < $itemCount; $k++) {
                // Avoid duplicate menus in same transaction
                $availableMenus = $menus->whereNotIn('id', $usedMenus);
                if ($availableMenus->isEmpty()) {
                    break;
                }
                
                $menu = $availableMenus->random();
                $usedMenus[] = $menu->id;
                $quantity = rand(1, 2);
                
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'menu_id' => $menu->id,
                    'qty' => $quantity,
                    'price_per_item' => $menu->price,
                    'total_price' => $menu->price * $quantity,
                ]);
                
                $totalPrice += ($menu->price * $quantity);
            }
            
            // Update transaction total price
            $transaction->update(['total_price' => $totalPrice]);
        }

        $this->command->info('Transaction seeder completed successfully!');
        $this->command->info('Created transactions for the last 30 days and tomorrow');
    }

    private function generateRandomName(): string
    {
        $firstNames = [
            'Ahmad', 'Budi', 'Citra', 'Dewi', 'Eko', 'Fitri', 'Gilang', 'Hana',
            'Indra', 'Joko', 'Kartika', 'Lina', 'Maya', 'Nurul', 'Oscar', 'Putri',
            'Qori', 'Rina', 'Sari', 'Toni', 'Umar', 'Vina', 'Wati', 'Yoga', 'Zara'
        ];
        
        $lastNames = [
            'Pratama', 'Sari', 'Putra', 'Dewi', 'Santoso', 'Wati', 'Kurniawan', 'Lestari',
            'Wijaya', 'Safitri', 'Setiawan', 'Rahayu', 'Gunawan', 'Maharani', 'Hidayat'
        ];
        
        return $firstNames[array_rand($firstNames)] . ' ' . $lastNames[array_rand($lastNames)];
    }

    private function generateRandomPhone(): string
    {
        $prefixes = ['0812', '0813', '0821', '0822', '0823', '0851', '0852', '0853'];
        $prefix = $prefixes[array_rand($prefixes)];
        $number = str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT);
        
        return $prefix . $number;
    }

    private function generateRandomNote(): string
    {
        $notes = [
            'tanpa cabe',
            'extra pedas',
            'porsi besar',
            'bungkus terpisah',
            'tanpa bawang',
            'extra sayur',
            'matang sekali',
            'kuah terpisah',
            'tidak terlalu asin',
            'tambah kerupuk'
        ];
        
        return $notes[array_rand($notes)];
    }
}
