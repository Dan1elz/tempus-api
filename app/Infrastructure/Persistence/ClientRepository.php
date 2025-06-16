<?php
namespace App\Infrastructure\Persistence;

use App\Domain\Interfaces\ClientRepositoryInterface;
use App\Domain\Models\Client;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ClientRepository implements ClientRepositoryInterface
{
    public function paginate(int $perPage, ?string $search = null): LengthAwarePaginator
    {
        $query = Client::query();

        if ($search)
            $query->where('nome', 'like', '%' . $search . '%');

        return $query->orderBy('nome')->paginate($perPage);
    }

    public function create(array $data): Client
    {
        return Client::create($data);
    }

    public function find(int|string $id): ?Client
    {
        return Client::find($id);
    }

    public function update(int|string $id, array $data): ?Client
    {
        $client = $this->find($id);

        if (!$client) return null;

        $client->update($data);
        return $client;
    }

    public function delete(int|string $id): bool
    {
        $client = $this->find($id);

        if (!$client)return false;

        return $client->delete();
    }
    protected function applyPeriodFilter($query, string $period)
    {
        switch ($period) {
            case 'today':
                $query->whereDate('created_at', Carbon::today());
                break;
            case 'week':
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('created_at', Carbon::now()->month);
                break;
        }

        return $query;
    }

    public function countHighIncomeAdults(string $period): array
    {
        $avgIncome = Client::average('renda_familiar');

        $query = Client::query()
            ->whereRaw("EXTRACT(YEAR FROM AGE(NOW(), data_nascimento)) >= 18")
            ->where('renda_familiar', '>', $avgIncome);

        $this->applyPeriodFilter($query, $period);

        return [
            'count' => $query->count(),
            'average_income' => $avgIncome,
        ];
    }

    public function countBySocialClass(string $period): array
    {
        $query = Client::query();

        $this->applyPeriodFilter($query, $period);

        return $query
            ->select(
            DB::raw("
                CASE
                WHEN renda_familiar <= 980 THEN 'A'
                WHEN renda_familiar > 980 AND renda_familiar <= 2500 THEN 'B'
                ELSE 'C'
                END as classe_social
            "),
            DB::raw('COUNT(*) as total')
            )
            ->groupBy('classe_social')
            ->pluck('total', 'classe_social')
            ->toArray();
    }
}
