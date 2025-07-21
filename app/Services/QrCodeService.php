<?php

namespace App\Services;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class QrCodeService
{
    public static function generatePickupQr($transactionId)
    {
        // Create QR code data - URL to pickup endpoint  
        $pickupUrl = route('admin.pickup.scan', ['id' => $transactionId]);
        
        $qrCode = new QrCode($pickupUrl);
        $writer = new PngWriter();
        $result = $writer->write($qrCode);
        
        return $result;
    }

    public static function generatePickupQrDataUri($transactionId)
    {
        $result = self::generatePickupQr($transactionId);
        return $result->getDataUri();
    }
}
