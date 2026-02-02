<?php

namespace App\Interfaces\Customers;

interface SubmissionInterface
{
    public function get();
    public function getById($id);
    public function store($data);
    public function destroy($id);
}
