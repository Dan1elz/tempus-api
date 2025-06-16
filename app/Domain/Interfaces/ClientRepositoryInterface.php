<?php
namespace App\Domain\Interfaces;

use App\Domain\Models\Client;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ClientRepositoryInterface
{
    public function paginate(int $perPage, ?string $search = null): LengthAwarePaginator;
    public function create(array $data): Client;
    public function find(int|string $id): ?Client;
    public function update(int|string $id, array $data): ?Client;
    public function delete(int|string $id): bool;
    public function countHighIncomeAdults(string $period): array;
    public function countBySocialClass(string $period): array;
}
