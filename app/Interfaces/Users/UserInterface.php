<?php

namespace App\Interfaces\Users;

interface UserInterface
{
    public function get();
    public function getById($id);
    public function store($data);
    public function update($id, $data);
    public function destroy($id);
    public function resetPassword(int $id);
}
