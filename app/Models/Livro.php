<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Livro extends Model
{
    use HasFactory;

    protected $fillable = [
        'isbn',
        'nome',
        'editora_id',
        'bibliografia',
        'imagem_capa',
        'preco',
    ];

    protected function casts(): array
    {
        return [
            'isbn' => 'encrypted',
            'nome' => 'encrypted',
            'bibliografia' => 'encrypted',
            'preco' => 'decimal:2',
        ];
    }

    public function editora()
    {
        return $this->belongsTo(Editora::class);
    }

    public function autores()
    {
        return $this->belongsToMany(Autor::class, 'autor_livro', 'livro_id', 'autor_id');
    }
}
