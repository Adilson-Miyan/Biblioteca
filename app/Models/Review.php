<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'requisicao_id',
        'rating',
        'comment',
        'status',
        'justification',
    ];

    public function requisicao()
    {
        return $this->belongsTo(Requisicao::class);
    }
}
