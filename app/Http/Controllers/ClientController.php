<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Application\Services\ClientService;
use OpenApi\Attributes as OA;


/**
 * @OA\Tag(
 *     name="Clientes",
 *     description="Gerenciamento de clientes"
 * )
 */
class ClientController extends Controller
{
   protected ClientService $service;

    public function __construct(ClientService $service)
    {
        $this->service = $service;
    }
     /**
     * @OA\Get(
     *     path="/api/clients",
     *     tags={"Clientes"},
     *     summary="Listar clientes",
     *     security={{"sanctum": {}}},
     *     description="Retorna uma lista paginada de clientes, podendo ser filtrada por nome.",
     *     @OA\Parameter(
     *         name="nome",
     *         in="query",
     *         description="Nome do cliente para busca",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Quantidade de itens por página (default 10)",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de clientes retornada com sucesso"
     *     )
     * )
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $perPage = $request->query('per_page', 10);

        $clientes = $this->service->listClients($perPage, $search);

        return response()->json($clientes);
    }

        /**
     * @OA\Post(
     * path="/api/clients",
     * tags={"Clientes"},
     * summary="Criar cliente",
     * security={{"sanctum": {}}},
     * description="Cria um novo cliente",
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"nome", "cpf", "data_nascimento"},
     * @OA\Property(property="nome", type="string", example="João Silva"),
     * @OA\Property(property="cpf", type="string", example="123.456.789-00"),
     * @OA\Property(property="data_nascimento", type="string", format="date", example="1990-01-01"),
     * @OA\Property(property="renda_familiar", type="number", format="float", example="3500.00")
     * )
     * ),
     * @OA\Response(
     * response=201,
     * description="Cliente criado com sucesso",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Cliente criado com sucesso!"),
     * @OA\Property(property="data", type="object",
     * @OA\Property(property="id", type="string", format="uuid", example="a1b2c3d4-e5f6-7890-1234-567890abcdef"),
     * @OA\Property(property="nome", type="string", example="João Silva"),
     * @OA\Property(property="cpf", type="string", example="123.456.789-00"),
     * @OA\Property(property="data_nascimento", type="string", format="date", example="1990-01-01"),
     * @OA\Property(property="renda_familiar", type="number", format="float", example="3500.00"),
     * @OA\Property(property="created_at", type="string", format="date-time", example="2023-10-27T10:00:00.000000Z"),
     * @OA\Property(property="updated_at", type="string", format="date-time", example="2023-10-27T10:00:00.000000Z")
     * )
     * )
     * ),
     * @OA\Response(
     * response=422,
     * description="Erro de validação",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="The given data was invalid."),
     * @OA\Property(property="errors", type="object", example={"cpf": {"The cpf has already been taken."}})
     * )
     * ),
     * @OA\Response(
     * response=401,
     * description="Não autorizado",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Unauthenticated.")
     * )
     * )
     * )
     */
    public function store(StoreClientRequest $request)
    {
        $client = $this->service->createClient($request->validated());

        return response()->json([
            'message' => 'Cliente criado com sucesso!',
            'data' => $client
        ], 201);
    }

     /**
     * @OA\Get(
     *     path="/api/clients/{id}",
     *     tags={"Clientes"},
     *     summary="Buscar cliente",
     *     security={{"sanctum": {}}},
     *     description="Retorna os dados de um cliente específico",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do cliente",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cliente encontrado"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cliente não encontrado"
     *     )
     * )
     */
    public function show($id)
    {
        $client = $this->service->getClient($id);

        if (!$client) {
            return response()->json([
                'message' => 'Cliente não encontrado!'
            ], 404);
        }

        return response()->json([
            'message' => 'Cliente encontrado com sucesso!',
            'data' => $client
        ], 200);
    }

    /**
     * @OA\Put(
     * path="/api/clients/{id}",
     * tags={"Clientes"},
     * summary="Atualizar cliente",
     * security={{"sanctum": {}}},
     * description="Atualiza os dados de um cliente específico",
     * @OA\Parameter(
     * name="id",
     * in="path",
     * description="ID do cliente",
     * required=true,
     * @OA\Schema(type="string", format="uuid")
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"nome", "cpf", "data_nascimento"},
     * @OA\Property(property="nome", type="string", example="João Silva"),
     * @OA\Property(property="cpf", type="string", example="123.456.789-00"),
     * @OA\Property(property="data_nascimento", type="string", format="date", example="1990-01-01"),
     * @OA\Property(property="renda_familiar", type="number", format="float", example="3500.00")
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Cliente atualizado com sucesso",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Cliente atualizado com sucesso!"),
     * @OA\Property(property="data", type="object",
     * @OA\Property(property="id", type="string", format="uuid", example="a1b2c3d4-e5f6-7890-1234-567890abcdef"),
     * @OA\Property(property="nome", type="string", example="João Silva"),
     * @OA\Property(property="cpf", type="string", example="123.456.789-00"),
     * @OA\Property(property="data_nascimento", type="string", format="date", example="1990-01-01"),
     * @OA\Property(property="renda_familiar", type="number", format="float", example="3500.00"),
     * @OA\Property(property="created_at", type="string", format="date-time", example="2023-10-27T10:00:00.000000Z"),
     * @OA\Property(property="updated_at", type="string", format="date-time", example="2023-10-27T10:00:00.000000Z")
     * )
     * )
     * ),
     * @OA\Response(
     * response=404,
     * description="Cliente não encontrado",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Cliente não encontrado!")
     * )
     * ),
     * @OA\Response(
     * response=422,
     * description="Erro de validação",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="The given data was invalid."),
     * @OA\Property(property="errors", type="object", example={"cpf": {"The cpf has already been taken."}})
     * )
     * ),
     * @OA\Response(
     * response=401,
     * description="Não autorizado",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Unauthenticated.")
     * )
     * )
     * )
     */
    public function update(UpdateClientRequest $request, string $id)
    {
        $client = $this->service->updateClient($id, $request->validated());

        if (!$client) {
            return response()->json([
                'message' => 'Cliente não encontrado!'
            ], 404);
        }

        return response()->json([
            'message' => 'Cliente atualizado com sucesso!',
            'data' => $client
        ], 200);
    }

     /**
     * @OA\Delete(
     *     path="/api/clients/{id}",
     *     tags={"Clientes"},
     *     summary="Deletar cliente",
     *     security={{"sanctum": {}}},
     *     description="Remove um cliente específico",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do cliente",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Cliente deletado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cliente não encontrado"
     *     )
     * )
     */
    public function destroy($id)
    {
         $deleted = $this->service->deleteClient($id);

        if (!$deleted) {
            return response()->json([
                'message' => 'Cliente não encontrado!'
            ], 404);
        }

        return response()->noContent();
    }
      /**
     * @OA\Get(
     *     path="/api/dashboard",
     *     tags={"Clientes"},
     *     summary="Dashboard de clientes",
     *     security={{"sanctum": {}}},
     *     description="Retorna dados estatísticos de clientes no período selecionado",
     *     @OA\Parameter(
     *         name="period",
     *         in="query",
     *         description="Período: today, week ou month (default: month)",
     *         required=false,
     *         @OA\Schema(type="string", enum={"today", "week", "month"})
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Dados do dashboard retornados com sucesso"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Período inválido"
     *     )
     * )
     */
    public function dashboard(Request $request)
    {
        $period = $request->query('period', 'month');

        if (!in_array($period, ['today', 'week', 'month'])) {
            return response()->json([
                'message' => 'Período inválido. Utilize: today, week ou month.'
            ], 422);
        }

        $data = $this->service->getDashboardData($period);

        return response()->json([
            'message' => 'Relatório gerado com sucesso!',
            'data' => $data
        ]);
    }
}
