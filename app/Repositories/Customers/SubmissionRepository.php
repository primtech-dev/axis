<?php

namespace App\Repositories\Customers;

use App\Interfaces\Customers\SubmissionInterface;
use App\Models\Submission;
use Illuminate\Support\Facades\DB;

class SubmissionRepository implements SubmissionInterface
{
    public function __construct(private Submission $submission) {}

    public function get()
    {
        return $this->submission->get();
    }

    public function getById($id)
    {
        return $this->submission->find($id);
    }

    public function store($data)
    {
        return DB::transaction(function () use ($data) {
            $this->submission->create([
                'name' => $data['name'],
                'phone_number' => $data['phone_number'],
                'car_unit' => $data['car_unit'],
                'address' => $data['address'],
                'message' => $data['message'] ?? null
            ]);
        });
    }

    public function destroy($id)
    {
        return DB::transaction(function () use ($id) {
            $this->getById($id)->delete();
        });
    }
}
