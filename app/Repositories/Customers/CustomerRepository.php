<?php

namespace App\Repositories\Customers;

use App\Interfaces\Customers\CustomerInterface;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class CustomerRepository implements CustomerInterface
{
    public function __construct(private Customer $customer) {}

    public function get()
    {
        return $this->customer->get();
    }

    public function getById($id)
    {
        return $this->customer->find($id);
    }

    public function store($data)
    {
        return DB::transaction(function () use ($data) {
            $this->customer->create([
                'name' => $data['name'],
                'address' => $data['address'],
                'car_unit' => $data['car_unit'],
                'phone_number' => $data['phone_number']
            ]);
        });
    }

    public function update($id, $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $this->customer->find($id)->update([
                'name' => $data['name'],
                'address' => $data['address'],
                'car_unit' => $data['car_unit'],
                'phone_number' => $data['phone_number']
            ]);
        });
    }

    public function destroy($id)
    {
        return DB::transaction(function () use ($id) {
            $this->customer->find($id)->delete();
        });
    }
}
