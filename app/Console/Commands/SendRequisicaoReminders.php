<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

use App\Models\Requisicao;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\RequisicaoLembrete;

#[Signature('app:send-requisicao-reminders')]
#[Description('Sends reminders for requisitions that end tomorrow')]
class SendRequisicaoReminders extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tomorrow = Carbon::tomorrow()->toDateString();
        
        $requisicoes = Requisicao::query()
            ->where('status', 'pendente')
            ->whereDate('data_fim_prevista', $tomorrow)
            ->with(['user', 'livro'])
            ->get();

        foreach ($requisicoes as $req) {
            if (! $req->user?->email) {
                continue;
            }
            Mail::to($req->user->email)->send(new RequisicaoLembrete($req));
        }

        $this->info("Sent " . $requisicoes->count() . " reminders.");
    }
}
