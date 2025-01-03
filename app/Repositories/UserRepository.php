<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getAll(): Collection
    {
        return $this->user->with('shippingAddresses', 'cart', 'orders')->all();
    }

    public function getQueryAll()
    {
        return $this->user->query();
    }

    public function getById($id): User
    {
        return $this->user->with('shippingAddresses', 'cart', 'orders')->findOrFail($id);
    }

    public function getByEmail($email): ?User
    {
        return $this->user->where('email', $email)->first();
    }

    public function register($data): User
    {
        $user = new $this->user;
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->role = $data['role'];
        $user->save();

        return $user;
    }

    public function update($data, $user): User
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        return $user;
    }

    public function destroy($id): User
    {
        $user = $this->user->findOrFail($id);
        $user->delete();

        return $user;
    }
}
