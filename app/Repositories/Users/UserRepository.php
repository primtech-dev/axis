<?php

namespace App\Repositories\Users;

use App\Interfaces\Users\UserInterface;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserRepository implements UserInterface
{
    public function __construct(private User $user) {}

    public function get()
    {
        return $this->user->get();
    }

    public function getById($id)
    {
        return $this->user->find($id);
    }

    public function store($data)
    {
        return DB::transaction(function () use ($data) {
            $password = bcrypt('password');
            $user = $this->user->create(array_merge($data, ['password' => $password]));

            $user->assignRole('super-admin');
        });
    }

    public function update($id, $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $user = $this->user->find($id);
            $user->update($data);

            $user->syncRoles('super-admin');
        });
    }

    public function destroy($id)
    {
        return $this->user->find($id)->delete();
    }

    public function resetPassword(int $id)
    {
        return DB::transaction(function () use ($id) {
            $user = $this->user->find($id);
            $user->password = \Hash::make('password');
            $user->save();
        });
    }
}
