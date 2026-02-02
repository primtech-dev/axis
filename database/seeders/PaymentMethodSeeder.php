<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('payment_methods')->insert([
            [
                'code'          => 'bank_transfer_bca',
                'name'          => 'Bank Transfer BCA',
                'provider'      => 'manual', // tanpa gateway
                'type'          => 'bank_transfer',
                'config'        => json_encode([
                    'bank_name'  => 'BCA',
                    'account_no' => '1234567890',
                    'account_name' => 'PT Contoh Jaya',
                    'instructions' => 'Lakukan transfer ke rekening BCA di atas dan upload bukti pembayaran.'
                ]),
                'is_active'     => true,
                'display_order' => 1,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'code'          => 'bank_transfer_mandiri',
                'name'          => 'Bank Transfer Mandiri',
                'provider'      => 'manual',
                'type'          => 'bank_transfer',
                'config'        => json_encode([
                    'bank_name'  => 'Mandiri',
                    'account_no' => '9876543210',
                    'account_name' => 'PT Contoh Jaya',
                    'instructions' => 'Transfer ke rekening Mandiri dan lakukan konfirmasi pembayaran.'
                ]),
                'is_active'     => true,
                'display_order' => 2,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'code'          => 'qris_gateway',
                'name'          => 'QRIS',
                'provider'      => 'gateway', // cocok jika pakai midtrans/xendit
                'type'          => 'qris',
                'config'        => json_encode([
                    'dynamic_qr' => true,
                    'fee' => [
                        'type' => 'percentage',
                        'value' => 0.7 // 0.7% fee
                    ],
                ]),
                'is_active'     => true,
                'display_order' => 3,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'code'          => 'cash_on_delivery',
                'name'          => 'Cash on Delivery (COD)',
                'provider'      => 'manual',
                'type'          => 'cash_on_delivery',
                'config'        => json_encode([
                    'instructions' => 'Bayar langsung ke kurir saat barang diterima.',
                    'allowed_regions' => ['Surabaya', 'Sidoarjo', 'Gresik']
                ]),
                'is_active'     => true,
                'display_order' => 4,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
        ]);
    }
}
