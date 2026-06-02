<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'status',
        'reminder_sent_at',
    ];

    protected function casts(): array
    {
        return [
            'reminder_sent_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public static function resolveActiveForUser(int $userId): self
    {
        $active = self::query()
            ->where('user_id', $userId)
            ->where('status', 'active')
            ->first();

        if ($active) {
            return $active;
        }

        $abandoned = self::query()
            ->where('user_id', $userId)
            ->where('status', 'abandoned')
            ->whereHas('items')
            ->latest('updated_at')
            ->first();

        if ($abandoned) {
            $abandoned->update([
                'status' => 'active',
                'reminder_sent_at' => null,
            ]);

            return $abandoned->fresh();
        }

        return self::create([
            'user_id' => $userId,
            'status' => 'active',
        ]);
    }
}
