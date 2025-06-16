<?php

namespace App\Application\Services;
use App\Domain\Interfaces\UserRepositoryInterface;
use App\Domain\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class UserService
{
    protected UserRepositoryInterface $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }
    public function listUsers(int $perPage, ?string $search = null): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $search);
    }

    public function updatePassword(int $id, string $newPassword): ?User
    {
        return $this->repository->update($id, [
            'password' => bcrypt($newPassword),
        ]);
    }
    public function createUser(array $data): User
    {
        return $this->repository->create($data);
    }

    public function getUserById(int $id): ?User
    {
        return $this->repository->findById($id);
    }

    public function updateUser(int $id, array $data): ?User
    {
        return $this->repository->update($id, $data);
    }

    public function deleteUser(int $id): bool
    {
        return $this->repository->delete($id);
    }
    public function login(string $email, string $password): ?array
    {
        $user = $this->repository->login($email, $password);

        if (!$user) {
            return null;
        }
        $expiration = now()->addDays(2);
        $token = $user->createToken('auth_token',['id' => $user->id, 'exp' => $expiration->timestamp])->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }
    public function logout(User $user): void
    {
        $this->repository->logout($user);
    }
}
