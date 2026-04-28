<?php

namespace App\Exports;

use App\Models\Livro;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LivrosExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Livro::with(['editora', 'autores'])->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'ISBN',
            'Nome do Livro',
            'Editora',
            'Autores',
            'Preço',
            'Data de Criação'
        ];
    }

    public function map($livro): array
    {
        return [
            $livro->id,
            $livro->isbn,
            $livro->nome,
            $livro->editora ? $livro->editora->nome : 'N/A',
            $livro->autores->pluck('nome')->implode(', '),
            $livro->preco ? number_format($livro->preco, 2, ',', ' ') . ' €' : '-',
            $livro->created_at->format('d/m/Y H:i')
        ];
    }
}
