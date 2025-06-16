<?php
namespace App\Infrastructure\Persistence;

use App\Domain\Interfaces\UserRepositoryInterface;
use App\Domain\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository implements UserRepositoryInterface
{
    public function paginate(int $perPage, ?string $search = null): LengthAwarePaginator
    {
        $query = User::query();

        if ($search)
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');

        return $query->orderBy('name')->paginate($perPage);
    }

    public function create(array $data): User
    {
        $data['password'] = bcrypt("Tempus@2025");
        return User::create($data);
    }

    public function findById(int $id): ?User
    {
        return User::find($id);
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function update(int $id, array $data): ?User
    {
        $user = $this->findById($id);
        if (!$user) return null;

        $user->update($data);
        return $user;
    }

    public function delete(int $id): bool
    {
        $user = $this->findById($id);
        if (!$user) return false;

        return $user->delete();
    }

    public function login(string $email, string $password): ?User
    {
        $user = $this->findByEmail($email);

        if ($user && password_verify($password, $user->password))
            return $user;

        return null;
    }
    public function logout(User $user): void
    {
        $user->tokens()->delete();
    }
}
