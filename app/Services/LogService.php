<?php

namespace App\Services;

use App\Models\ActionLog;
use Illuminate\Support\Facades\Auth;

class LogService
{
    public static function register(string $modulo, string $alteracao, ?int $objectId = null)
    {
        if (!Auth::check()) {
            return;
        }

        ActionLog::create([
            'user_id' => Auth::id(),
            'modulo' => $modulo,
            'object_id' => $objectId,
            'alteracao' => $alteracao,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
