<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Cria a tabela 'clientes' no banco de dados.
        Schema::create('clientes', function (Blueprint $table) {
            $table->id(); // Coluna de chave primária auto-incrementável (id)
            $table->string('nome'); // Coluna para o nome do cliente
            $table->string('cpf')->unique(); // Coluna para o CPF, garantindo que seja único
            $table->date('data_nascimento')->nullable(); // Coluna para a data de nascimento, pode ser nula
            $table->decimal('renda_familiar', 10, 2)->nullable(); // Coluna para a renda familiar, com 10 dígitos no total e 2 decimais, pode ser nula
            $table->date('data_cadastro')->useCurrent(); // Coluna para a data de cadastro, define o valor padrão como a data e hora atual no momento da criação
            $table->timestamps(); // Adiciona as colunas 'created_at' e 'updated_at'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
