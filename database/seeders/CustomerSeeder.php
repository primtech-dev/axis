<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\Customer;
use App\Models\Address;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data dummy customer
        $customers = [
            [
                'full_name' => 'Budi Santoso',
                'email' => 'budi@example.com',
                'phone' => '081234567890',
                'is_active' => true,
            ],
            [
                'full_name' => 'Siti Rahma',
                'email' => 'siti@example.com',
                'phone' => '081298887766',
                'is_active' => true,
            ],
            [
                'full_name' => 'Tono Wijaya',
                'email' => 'tono@example.com',
                'phone' => '082233445566',
                'is_active' => false,
            ],
        ];

        foreach ($customers as $data) {
            // Create customer
            $customer = Customer::create([
                'public_id'     => Str::uuid(),
                'email'         => $data['email'],
                'google_id'     => null,
                'phone'         => $data['phone'],
                'full_name'     => $data['full_name'],
                'password_hash' => Hash::make('password123'),
                'token'         => null,
                'expired_at'    => null,
                'is_active'     => $data['is_active'],
                'meta'          => [
                    'notes' => 'Dummy customer data for testing'
                ],
            ]);

            // Generate 1–3 addresses randomly
            $totalAddress = rand(1, 3);

            for ($i = 1; $i <= $totalAddress; $i++) {
                $address = Address::create([
                    'customer_id' => $customer->id,
                    'label'       => $i === 1 ? 'Rumah' : 'Alamat #' . $i,
                    'street'      => 'Jalan Contoh No. ' . rand(10, 100),
                    'city'        => 'Surabaya',
                    'province'    => 'Jawa Timur',
                    'latitude'    => -7.25 + rand(-10, 10) / 1000,
                    'longitude'   => 112.75 + rand(-10, 10) / 1000,
                    'postal_code' => '602' . rand(10, 99),
                    'country'     => 'ID',
                    'phone'       => $customer->phone,
                    'is_default'  => false,
                ]);

                // Set the first address as default
                if ($i === 1) {
                    $address->is_default = true;
                    $address->save();
                }
            }
        }

        echo "Customer + Addresses seeding complete.\n";
    }
}
