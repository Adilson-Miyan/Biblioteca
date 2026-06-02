<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[#cba77d] leading-tight font-serif tracking-wider">
            {{ __('Gestão de Encomendas') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-[#1c1816] min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="bg-[#2d2019] rounded-2xl border border-[#3e2b1e] shadow-xl overflow-hidden">
                <div class="p-6 border-b border-[#3e2b1e]">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                        <div>
                            <h2 class="text-2xl font-bold text-[#b58f5c] tracking-tight">Encomendas</h2>
                            <p class="text-gray-400 mt-1 text-sm">Visualize o estado de compras realizadas pelos cidadãos.</p>
                        </div>
                        <div class="flex items-center gap-4 w-full md:w-auto">
                            <select wire:model.live="status" class="bg-[#1c1816] border border-[#3e2b1e] text-white focus:border-[#b58f5c] focus:ring-[#b58f5c] rounded-xl px-4 py-2">
                                <option value="">Todos os Estados</option>
                                <option value="pendente">Pendentes</option>
                                <option value="paga">Pagas</option>
                                <option value="cancelada">Canceladas</option>
                            </select>
                            <input type="text" wire:model.live="search" placeholder="Pesquisar utilizador..." class="w-full md:w-64 bg-[#1c1816] border border-[#3e2b1e] text-white focus:border-[#b58f5c] focus:ring-[#b58f5c] rounded-xl px-4 py-2" />
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-300">
                        <thead class="text-xs text-gray-400 uppercase bg-[#1c1816] border-b border-[#3e2b1e]">
                            <tr>
                                <th class="px-6 py-4 font-semibold tracking-wider">ID / Data</th>
                                <th class="px-6 py-4 font-semibold tracking-wider">Utilizador</th>
                                <th class="px-6 py-4 font-semibold tracking-wider">Itens</th>
                                <th class="px-6 py-4 font-semibold tracking-wider">Total</th>
                                <th class="px-6 py-4 font-semibold tracking-wider">Estado</th>
                                <th class="px-6 py-4 font-semibold tracking-wider text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#3e2b1e]">
                            @forelse ($orders as $order)
                                <tr class="hover:bg-[#3e2b1e] transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-white">#{{ $order->id }}</div>
                                        <div class="text-xs text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-white">{{ $order->user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $order->user->email }}</div>
                                        @if($order->address)
                                            <div class="text-xs text-[#b58f5c] mt-1" title="{{ $order->address }}">Ver Morada</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $order->items->count() }} itens
                                    </td>
                                    <td class="px-6 py-4 font-bold text-white">
                                        {{ number_format($order->total, 2, ',', '.') }} €
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($order->status === 'pendente')
                                            <span class="px-3 py-1 text-xs font-bold text-yellow-400 bg-yellow-900/30 border border-yellow-700/50 rounded-full">Pendente</span>
                                        @elseif($order->status === 'paga')
                                            <span class="px-3 py-1 text-xs font-bold text-green-400 bg-green-900/30 border border-green-700/50 rounded-full">Paga</span>
                                        @else
                                            <span class="px-3 py-1 text-xs font-bold text-red-400 bg-red-900/30 border border-red-700/50 rounded-full">Cancelada</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                        @if($order->status === 'pendente')
                                            <button wire:click="cancelarEncomenda({{ $order->id }})" wire:confirm="Tem a certeza que deseja cancelar esta encomenda pendente?" class="text-xs font-bold text-red-400 hover:text-red-300 uppercase tracking-wider">
                                                Cancelar
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                        Nenhuma encomenda encontrada.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-6 border-t border-[#3e2b1e]">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
