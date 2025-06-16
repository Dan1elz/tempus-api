<?php

namespace App\Domain\Interfaces;

use App\Domain\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;


interface UserRepositoryInterface
{
    public function findById(int $id): ?User;
    public function findByEmail(string $email): ?User;
    public function create(array $data): User;
    public function update(int $id, array $data): ?User;
    public function delete(int $id): bool;
    public function paginate(int $perPage, ?string $search = null): LengthAwarePaginator;
    public function login(string $email, string $password): ?User;

    public function logout(User $user): void;

}
