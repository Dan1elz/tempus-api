<?php

namespace App\Application\Services;

use App\Domain\Interfaces\ClientRepositoryInterface;
use App\Domain\Models\Client;

use Illuminate\Pagination\LengthAwarePaginator;

class ClientService
{
    protected ClientRepositoryInterface $repository;

    public function __construct(ClientRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function listClients(int $perPage, ?string $search = null): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $search);
    }

    public function createClient(array $data): Client
    {
        return $this->repository->create($data);
    }

    public function getClient(int|string $id): ?Client
    {
        return $this->repository->find($id);
    }

    public function updateClient(int|string $id, array $data): ?Client
    {
        return $this->repository->update($id, $data);
    }

    public function deleteClient(int|string $id): bool
    {
        return $this->repository->delete($id);
    }
    public function getDashboardData(string $period): array
    {
        $highIncomeAdults = $this->repository->countHighIncomeAdults($period);
        $byClass = $this->repository->countBySocialClass($period);

        return [
            'high_income_adults' => $highIncomeAdults['count'] ?? 0,
            'average_income' => $highIncomeAdults['average_income'] ?? 0,
            'class_distribution' => [
                'A' => $byClass['A'] ?? 0,
                'B' => $byClass['B'] ?? 0,
                'C' => $byClass['C'] ?? 0,
            ],
        ];
    }
}
