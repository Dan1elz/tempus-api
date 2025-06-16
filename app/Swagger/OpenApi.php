<?php
namespace App\Swagger;
use OpenApi\Attributes as OA;

/**
 * @OA\Info(
 * title="API Tempus",
 * version="1.0.0",
 * description="Documentação da API Tempus",
 * @OA\Contact(
 * email="seuemail@dominio.com"
 * ),
 * @OA\License(
 * name="MIT",
 * url="https://opensource.org/licenses/MIT"
 * )
 * )
 *
 * @OA\Server(
 * url=L5_SWAGGER_CONST_HOST,
 * description="Servidor API"
 * )
 *
 * @OA\SecurityScheme(
 * securityScheme="sanctum",
 * type="http",
 * scheme="bearer",

 * description="Autenticação via Sanctum"
 * )
 *
 * @OA\Security(    <-- ADICIONE ESTA ANOTAÇÃO AQUI!
 * security={{"sanctum": {}}}
 * )
 */
class OpenApi {}
