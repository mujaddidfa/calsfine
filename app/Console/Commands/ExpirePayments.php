<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class ExpirePayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark expired pending payments as expired and restore stock';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to process expired payments...');
        
        $expiredPayments = Payment::getExpiredPendingPayments();
        $count = $expiredPayments->count();
        
        if ($count === 0) {
            $this->info('No expired payments found.');
            return 0;
        }
        
        $this->info("Found {$count} expired payments to process.");
        
        $successCount = 0;
        $errorCount = 0;
        
        foreach ($expiredPayments as $payment) {
            try {
                if ($payment->markAsExpired()) {
                    $successCount++;
                    $this->line("✓ Expired payment ID {$payment->id} for transaction {$payment->transaction_id}");
                } else {
                    $errorCount++;
                    $this->error("✗ Failed to expire payment ID {$payment->id} (status: {$payment->status})");
                }
            } catch (\Exception $e) {
                $errorCount++;
                $this->error("✗ Error processing payment ID {$payment->id}: " . $e->getMessage());
                Log::error("Error expiring payment {$payment->id}: " . $e->getMessage());
            }
        }
        
        $this->info("\nProcessing completed:");
        $this->info("✓ Successfully expired: {$successCount} payments");
        
        if ($errorCount > 0) {
            $this->error("✗ Errors: {$errorCount} payments");
        }
        
        Log::info("Expired payments processed", [
            'total_found' => $count,
            'success' => $successCount,
            'errors' => $errorCount
        ]);
        
        return 0;
    }
}
