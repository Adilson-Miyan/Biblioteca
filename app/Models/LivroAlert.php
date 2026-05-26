<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LivroAlert extends Model
{
    protected $fillable = [
        'user_id',
        'livro_id',
        'is_notified',
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
