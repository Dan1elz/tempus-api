<?php
namespace App\Http\Controllers;

use App\Application\Services\UserService;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserStoreUpdateRequest;
use App\Http\Requests\UserUpdatePasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;

/**
 * @OA\Tag(
 *     name="Usuários",
 *     description="Gerenciamento de usuários e autenticação"
 * )
 */
class UserController extends Controller
{
    protected $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

     /**
     * @OA\Get(
     *     path="/api/users",
     *     tags={"Usuários"},
     *     summary="Listar usuários",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Filtro por nome ou e-mail",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Itens por página (default 10)",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Lista de usuários")
     * )
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $perPage = $request->query('per_page', 10);

        $users = $this->service->listUsers($perPage, $search);
        return response()->json($users);
    }

     /**
     * @OA\Get(
     *     path="/api/users/{id}",
     *     tags={"Usuários"},
     *     summary="Buscar usuário",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do usuário",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Usuário encontrado"),
     *     @OA\Response(response=404, description="Usuário não encontrado")
     * )
     */
    public function show($id)
    {
        $user = $this->service->getUserById($id);

        if (!$user) {
            return response()->json([
                'message' => 'Usuario não encontrado!'
            ], 404);
        }

        return response()->json([
            'message' => 'Usuario encontrado com sucesso!',
            'data' => $user
        ], 200);
    }

     /**
     * @OA\Post(
     *     path="/api/users",
     *     tags={"Usuários"},
     *     summary="Criar usuário",
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password"},
     *             @OA\Property(property="name", type="string", example="João Silva"),
     *             @OA\Property(property="email", type="string", example="joao@email.com"),
     *             @OA\Property(property="password", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Usuário criado com sucesso"),
     *     @OA\Response(response=422, description="Erro de validação")
     * )
     */
    public function store(UserStoreUpdateRequest $request)
    {

        $user = $this->service->createUser($request->validated());
        return response()->json([
            'message' => 'Usuario criado com sucesso!',
            'data' => $user
        ], 201);
    }

    /**
     * @OA\Put(
     *     path="/api/users/{id}",
     *     tags={"Usuários"},
     *     summary="Atualizar usuário",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do usuário",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="João Atualizado"),
     *             @OA\Property(property="email", type="string", example="joao_novo@email.com")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Usuário atualizado com sucesso"),
     *     @OA\Response(response=404, description="Usuário não encontrado")
     * )
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $user = $this->service->updateUser($id, $data);


         if (!$user) {
            return response()->json([
                'message' => 'Usuario não encontrado!'
            ], 404);
        }

        return response()->json([
            'message' => 'Usuario atualizado com sucesso!',
            'data' => $user
        ], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     tags={"Usuários"},
     *     summary="Excluir usuário",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do usuário",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Usuário deletado"),
     *     @OA\Response(response=404, description="Usuário não encontrado")
     * )
     */
    public function destroy($id)
    {
        $deleted = $this->service->deleteUser($id);

        if (!$deleted) {
            return response()->json(['message' => 'Usuário não encontrado!'], 404);
        }

        return response()->noContent();
    }

     /**
     * @OA\Put(
     *     path="/api/users/{id}/password",
     *     tags={"Usuários"},
     *     summary="Atualizar senha do usuário",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do usuário",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"password"},
     *             @OA\Property(property="password", type="string", example="novaSenha123")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Senha atualizada com sucesso"),
     *     @OA\Response(response=404, description="Usuário não encontrado")
     * )
     */
    public function updatePassword(UserUpdatePasswordRequest $request, $id)
    {
        $validated = $request->validated();
        $updated = $this->service->updatePassword($id, $validated['password']);


        if (!$updated) {
            return response()->json([
                'message' => 'Usuário não encontrado!'
            ], 404);
        }

        return response()->json([
            'message' => 'Usuário atualizado com sucesso!',
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/users/login",
     *     tags={"Usuários"},
     *     summary="Login de usuário",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", example="joao@email.com"),
     *             @OA\Property(property="password", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Login realizado com sucesso"),
     *     @OA\Response(response=401, description="Credenciais inválidas")
     * )
     */
    public function login(LoginRequest $request)
    {
        $request->validated();
        $token = $this->service->login($request['email'], $request['password']);

        if (!$token) {
            return response()->json([
                'message' => 'Credenciais inválidas!'
            ], 401);
        }

        return response()->json([
            'message' => 'Login realizado com sucesso!',
            'token' => $token
        ], 200);
    }

     /**
     * @OA\Post(
     *     path="/api/users/logout",
     *     tags={"Usuários"},
     *     summary="Logout do usuário",
     *     @OA\Response(response=200, description="Logout realizado com sucesso")
     * )
     */
    public function logout()
    {
        $user = Auth::User();
        $this->service->logout($user);

        return response()->json(['message' => 'Logout realizado com sucesso!'], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/users/create-base-user",
     *     tags={"Usuários"},
     *     summary="Criar usuário base (Admin)",
     *     description="Cria um usuário administrador base (Somente uso interno, por exemplo, para inicializar o sistema)",
     *     @OA\Response(response=201, description="Usuário base criado com sucesso"),
     *     @OA\Response(response=400, description="Erro ao criar usuário base")
     * )
     */
    public function createBaseUser()
    {

        $data = [
            'name' => 'Admin',
            'email' => 'admin@tempus.com.br',
        ];
        $user = $this->service->createUser($data);

        if (!$user) {
            return response()->json([
                'message' => 'Não foi possível criar o usuário base!'
            ], 400);
        }

        return response()->json([
            'message' => 'Usuário base criado com sucesso!',
            'data' => $user
        ], 201);
    }
}
