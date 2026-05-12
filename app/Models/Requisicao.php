<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Requisicao extends Model
{
    protected $fillable = [
        'user_id',
        'livro_id',
        'data_requisicao',
        'data_fim_prevista',
        'data_rececao',
        'dias_decorrentes',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function livro()
    {
        return $this->belongsTo(Livro::class);
    }
}
