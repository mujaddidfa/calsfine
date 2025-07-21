<?php

namespace App\Services;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class QrCodeService
{
    public static function generatePickupQr($pickupCode)
    {
        // Create QR code data - URL to pickup endpoint with pickup code
        $pickupUrl = route('admin.pickup.scan', ['code' => $pickupCode]);
        
        $qrCode = new QrCode($pickupUrl);
        $writer = new PngWriter();
        $result = $writer->write($qrCode);
        
        return $result;
    }

    public static function generatePickupQrDataUri($pickupCode)
    {
        $result = self::generatePickupQr($pickupCode);
        return $result->getDataUri();
    }
}
