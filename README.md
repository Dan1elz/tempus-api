# Tempus API - Laravel + PostgreSQL + Docker

Este projeto é uma API desenvolvida em [Laravel](https://laravel.com/) utilizando [PostgreSQL](https://www.postgresql.org/) como banco de dados, executada em um ambiente Dockerizado.

---

## Índice

- [Pré-requisitos](#pré-requisitos)
- [Clonando o Projeto](#clonando-o-projeto)
- [Configuração do Ambiente](#configuração-do-ambiente)
    - [Banco de Dados PostgreSQL com Docker](#banco-de-dados-postgresql-com-docker)
    - [Configuração do Laravel](#configuração-do-laravel)
- [Executando as Migrações](#executando-as-migrações)
- [Rodando a API](#rodando-a-api)
- [Documentação da API (Swagger)](#documentação-da-api-swagger)
- [Testes](#testes)
- [Dicas Úteis](#dicas-úteis)
- [Problemas Comuns](#problemas-comuns)
- [Licença](#licença)

---

## Pré-requisitos

Certifique-se de ter instalado em sua máquina:

- [Docker](https://docs.docker.com/get-docker/)
- [Composer](https://getcomposer.org/)
- [Git](https://git-scm.com/)

---

## Clonando o Projeto

```bash
git clone https://github.com/Dan1elz/tempus-api.git
cd tempus-api
```

---

## Configuração do Ambiente

### Banco de Dados PostgreSQL com Docker

Execute o comando abaixo para iniciar um container PostgreSQL:

```bash
docker run -e 'ACCEPT_EULA=1' -e POSTGRES_PASSWORD=SuaSenhaForte123 -e POSTGRES_DB=tempus-database -p 5432:5432 --name tempus-postgres -d postgres
```

- **Dica:** Substitua `SuaSenhaForte123` por uma senha forte de sua escolha. Caso fizer isso, lembre-se de alterar no .env.
- Para parar o container: `docker stop tempus-postgres`
- Para iniciar novamente: `docker start tempus-postgres`

- **Dica:** Substitua `SuaSenhaForte123` por uma senha forte de sua escolha. Caso Fizer isso, lembre-se de alterar no .env
- Para parar o container: `docker stop tempus-postgres`
- Para iniciar novamente: `docker start tempus-postgres`

### Configuração do Laravel

1. Instale as dependências do projeto:

```bash
composer install
```

2. Copie o arquivo de variáveis de ambiente:

```bash
cp .env.example .env
```

3. Edite o arquivo `.env` e configure as variáveis de conexão com o banco de dados:

```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=tempus-database
DB_USERNAME=postgres
DB_PASSWORD=SuaSenhaForte123
```

4. Gere a chave da aplicação:

```bash
php artisan key:generate
```

---

## Executando as Migrações

Crie as tabelas no banco de dados:

```bash
php artisan migrate
```

---

## Rodando a API

Inicie o servidor de desenvolvimento do Laravel:

```bash
php artisan serve
```

A API estará acessível em [http://localhost:8000](http://localhost:8000).

---

## Documentação da API (Swagger)

A documentação interativa da API foi gerada utilizando Swagger. Após iniciar o servidor, acesse:

[http://127.0.0.1:8000/api/documentation](http://127.0.0.1:8000/api/documentation)

Nessa página, você pode visualizar e testar todos os endpoints disponíveis da API.


---


## Dicas Úteis

- Para acessar as rotas autentificadas da API, voce deve excecutar primeiro a rota `/api/users/create-base-user`. ele disponibiliza um usuario:
```json
{
  "email": "admin@tempus.com.br",
  "password": "Tempus@2025"
}
```
- Para visualizar os containers Docker em execução: `docker ps`
- Para acessar o banco de dados via terminal:

```bash
docker exec -it tempus-postgres psql -U postgres
```
---

## Problemas Comuns

- **Porta 5432 já está em uso:** Pare outros containers ou serviços que estejam usando essa porta.
- **Erro de conexão com o banco:** Verifique se o container está rodando e se as credenciais no `.env` estão corretas.
- **Permissões de arquivos:** No Linux/Mac, pode ser necessário rodar `chmod -R 775 storage bootstrap/cache`.

---

## Licença

Este projeto está licenciado sob a [MIT License](LICENSE).

---
