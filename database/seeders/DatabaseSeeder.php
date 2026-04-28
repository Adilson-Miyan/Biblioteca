<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Editora;
use App\Models\Autor;
use App\Models\Livro;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $e1 = Editora::create(['nome' => 'Porto Editora', 'logotipo' => null]);
        $e2 = Editora::create(['nome' => 'Leya', 'logotipo' => null]);

        $a1 = Autor::create(['nome' => 'José Saramago', 'foto' => null]);
        $a2 = Autor::create(['nome' => 'Fernando Pessoa', 'foto' => null]);
        $a3 = Autor::create(['nome' => 'Eça de Queiroz', 'foto' => null]);

        $l1 = Livro::create([
            'isbn' => '978-972-21-0080-6',
            'nome' => 'Ensaio sobre a Cegueira',
            'editora_id' => $e1->id,
            'bibliografia' => 'Um romance de 1995. Um grupo de pessoas é inexplicavelmente atingido por uma cegueira branca.',
            'preco' => 15.50,
        ]);
        $l1->autores()->attach([$a1->id]);

        $l2 = Livro::create([
            'isbn' => '978-972-0-04671-1',
            'nome' => 'Mensagem',
            'editora_id' => $e2->id,
            'bibliografia' => 'Livro de poesia único publicado em vida (1934). Fala sobre o sebastianismo e a alma nacional.',
            'preco' => 12.00,
        ]);
        $l2->autores()->attach([$a2->id]);

        $l3 = Livro::create([
            'isbn' => '978-972-0-04944-6',
            'nome' => 'Os Maias',
            'editora_id' => $e1->id,
            'bibliografia' => 'Obra-prima do realismo português (1888). História de Carlos da Maia e Maria Eduarda.',
            'preco' => 18.99,
        ]);
        $l3->autores()->attach([$a3->id]);
        
        $l4 = Livro::create([
            'isbn' => '978-000-0-00000-0',
            'nome' => 'O Ano da Morte de Ricardo Reis',
            'editora_id' => $e2->id,
            'bibliografia' => 'Neste livro, José Saramago traz de volta a Lisboa um dos mais importantes heterónimos de Fernando Pessoa.',
            'preco' => 22.30,
        ]);
        // Tem 2 autores neste exemplo de ficção:
        $l4->autores()->attach([$a1->id, $a2->id]);
    }
}
