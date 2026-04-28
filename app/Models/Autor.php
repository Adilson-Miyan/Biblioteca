<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Autor extends Model
{
    use HasFactory;

    protected $table = 'autores';

    protected $fillable = ['nome', 'foto'];

    protected function casts(): array
    {
        return [
            'nome' => 'encrypted',
        ];
    }

    public function livros()
    {
        return $this->belongsToMany(Livro::class, 'autor_livro', 'autor_id', 'livro_id');
    }
}
