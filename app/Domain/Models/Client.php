<?php
namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use HasFactory;

    protected $table = 'clientes';

    protected $fillable = [
        'nome',
        'cpf',
        'data_nascimento',
        'renda_familiar',
        'data_cadastro',
    ];

    protected $casts = [
        'data_nascimento' => 'date',
        'data_cadastro' => 'date',
        'renda_familiar' => 'decimal:2',
    ];

    protected static function booted()
    {
        static::creating(function ($client) {
            $client->data_cadastro = now();
        });
    }
}
