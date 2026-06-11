<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[#cba77d] leading-tight font-serif tracking-wider">
            {{ __('Logs de Atividade') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-[#1c1816] min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-[#2d2019] rounded-2xl border border-[#3e2b1e] shadow-xl overflow-hidden">
                <div class="p-6 border-b border-[#3e2b1e]">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                        <div>
                            <h2 class="text-2xl font-bold text-[#b58f5c] tracking-tight">Registo de Atividades</h2>
                            <p class="text-gray-400 mt-1 text-sm">Visualize o histórico completo de ações na plataforma.</p>
                        </div>
                        <div class="w-full md:w-auto">
                            <input type="text" wire:model.live="search" placeholder="Pesquisar módulo, alteração, user..." class="w-full md:w-64 bg-[#1c1816] border border-[#3e2b1e] text-white focus:border-[#b58f5c] focus:ring-[#b58f5c] rounded-xl px-4 py-2" />
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-300">
                        <thead class="text-xs text-gray-400 uppercase bg-[#1c1816] border-b border-[#3e2b1e]">
                            <tr>
                                <th class="px-6 py-4 font-semibold tracking-wider">Data / Hora</th>
                                <th class="px-6 py-4 font-semibold tracking-wider">Utilizador</th>
                                <th class="px-6 py-4 font-semibold tracking-wider">Módulo</th>
                                <th class="px-6 py-4 font-semibold tracking-wider">ID Objeto</th>
                                <th class="px-6 py-4 font-semibold tracking-wider">Alteração</th>
                                <th class="px-6 py-4 font-semibold tracking-wider">IP</th>
                                <th class="px-6 py-4 font-semibold tracking-wider">Browser</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#3e2b1e]">
                            @forelse ($logs as $log)
                                <tr class="hover:bg-[#3e2b1e] transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-bold text-white">{{ $log->created_at->format('d/m/Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $log->created_at->format('H:i:s') }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-white">{{ $log->user->name }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs font-bold text-blue-400 bg-blue-900/30 border border-blue-700/50 rounded-md">
                                            {{ $log->modulo }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($log->object_id)
                                            <span class="text-[#b58f5c] font-mono">#{{ $log->object_id }}</span>
                                        @else
                                            <span class="text-gray-600">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-gray-200">
                                        {{ $log->alteracao }}
                                    </td>
                                    <td class="px-6 py-4 text-xs font-mono text-gray-400">
                                        {{ $log->ip_address ?: '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-xs text-gray-500" title="{{ $log->user_agent }}">
                                        {{ Str::limit($log->user_agent, 30) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                        Nenhum registo de atividade encontrado.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-6 border-t border-[#3e2b1e]">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
