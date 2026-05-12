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
        // Create an Admin user
        User::updateOrCreate(
            ['email' => 'admin@biblioteca.pt'],
            [
                'name' => 'Administrador',
                'password' => bcrypt('password123'),
                'role' => 'admin',
            ]
        );

        // Create a test Cidadao
        User::updateOrCreate(
            ['email' => 'cidadao@biblioteca.pt'],
            [
                'name' => 'Cidadão Teste',
                'password' => bcrypt('password123'),
                'role' => 'cidadao',
            ]
        );

        $e1 = Editora::firstOrCreate(['nome' => 'Porto Editora']);
        $e2 = Editora::firstOrCreate(['nome' => 'Leya']);
        $e3 = Editora::firstOrCreate(['nome' => 'Bertrand Editora']);
        $e4 = Editora::firstOrCreate(['nome' => 'Presença']);
        $e5 = Editora::firstOrCreate(['nome' => 'Relógio d\'Água']);

        $a1 = Autor::firstOrCreate(['nome' => 'José Saramago']);
        $a2 = Autor::firstOrCreate(['nome' => 'Fernando Pessoa']);
        $a3 = Autor::firstOrCreate(['nome' => 'Eça de Queiroz']);
        $a4 = Autor::firstOrCreate(['nome' => 'Luís de Camões']);
        $a5 = Autor::firstOrCreate(['nome' => 'Sophia de Mello Breyner']);
        $a6 = Autor::firstOrCreate(['nome' => 'Mia Couto']);
        $a7 = Autor::firstOrCreate(['nome' => 'José Luís Peixoto']);

        $livros = [
            [
                'isbn' => '978-972-21-0080-6',
                'nome' => 'Ensaio sobre a Cegueira',
                'editora_id' => $e1->id,
                'bibliografia' => 'Um romance de 1995. Um grupo de pessoas é inexplicavelmente atingido por uma cegueira branca.',
                'preco' => 15.50,
                'autor_id' => $a1->id,
            ],
            [
                'isbn' => '978-972-0-04671-1',
                'nome' => 'Mensagem',
                'editora_id' => $e2->id,
                'bibliografia' => 'Livro de poesia único publicado em vida (1934). Fala sobre o sebastianismo e a alma nacional.',
                'preco' => 12.00,
                'autor_id' => $a2->id,
            ],
            [
                'isbn' => '978-972-0-04944-6',
                'nome' => 'Os Maias',
                'editora_id' => $e1->id,
                'bibliografia' => 'Obra-prima do realismo português (1888). História de Carlos da Maia e Maria Eduarda.',
                'preco' => 18.99,
                'autor_id' => $a3->id,
            ],
            [
                'isbn' => '978-000-0-00000-0',
                'nome' => 'O Ano da Morte de Ricardo Reis',
                'editora_id' => $e2->id,
                'bibliografia' => 'Neste livro, José Saramago traz de volta a Lisboa um dos mais importantes heterónimos de Fernando Pessoa.',
                'preco' => 22.30,
                'autor_id' => [$a1->id, $a2->id],
            ],
            [
                'isbn' => '978-989-660-400-0',
                'nome' => 'Os Lusíadas',
                'editora_id' => $e3->id,
                'bibliografia' => 'Poema épico de 1572 que relata as grandes navegações e a história de Portugal.',
                'preco' => 25.00,
                'autor_id' => $a4->id,
            ],
            [
                'isbn' => '978-972-21-1600-0',
                'nome' => 'A Fada Oriana',
                'editora_id' => $e1->id,
                'bibliografia' => 'Clássico da literatura infantil em Portugal sobre a importância da responsabilidade.',
                'preco' => 10.50,
                'autor_id' => $a5->id,
            ],
            [
                'isbn' => '978-972-21-1350-0',
                'nome' => 'Terra Sonâmbula',
                'editora_id' => $e2->id,
                'bibliografia' => 'Um dos mais belos romances africanos, misturando realidade e sonho num país em guerra.',
                'preco' => 17.20,
                'autor_id' => $a6->id,
            ],
            [
                'isbn' => '978-989-616-200-0',
                'nome' => 'Nenhum Olhar',
                'editora_id' => $e4->id,
                'bibliografia' => 'Retrato duro mas lírico do interior rural de Portugal, vencedor do prémio José Saramago.',
                'preco' => 14.80,
                'autor_id' => $a7->id,
            ],
            [
                'isbn' => '978-989-660-200-0',
                'nome' => 'O Livro do Desassossego',
                'editora_id' => $e5->id,
                'bibliografia' => 'Diário íntimo, reflexivo e fragmentário assinado pelo semi-heterónimo Bernardo Soares.',
                'preco' => 21.00,
                'autor_id' => $a2->id,
            ],
            [
                'isbn' => '978-972-0-04321-1',
                'nome' => 'A Ilustre Casa de Ramires',
                'editora_id' => $e1->id,
                'bibliografia' => 'Conta a história de Gonçalo Ramires, um fidalgo frouxo do Portugal do século XIX.',
                'preco' => 16.99,
                'autor_id' => $a3->id,
            ],
            [
                'isbn' => '978-972-21-2000-0',
                'nome' => 'O Conto da Ilha Desconhecida',
                'editora_id' => $e1->id,
                'bibliografia' => 'Uma pequena fábula maravilhosa e poética sobre a busca interior do indivíduo.',
                'preco' => 8.90,
                'autor_id' => $a1->id,
            ],
            [
                'isbn' => '978-972-21-1700-0',
                'nome' => 'O Rapaz de Bronze',
                'editora_id' => $e1->id,
                'bibliografia' => 'Uma história mágica num jardim noturno repleto de surpresas, luz e fantasia.',
                'preco' => 11.50,
                'autor_id' => $a5->id,
            ]
        ];

        foreach ($livros as $livroData) {
            $autor_ids = is_array($livroData['autor_id']) ? $livroData['autor_id'] : [$livroData['autor_id']];
            unset($livroData['autor_id']);
            
            $livro = Livro::where('isbn', $livroData['isbn'])->first();
            
            if (!$livro) {
                $livro = Livro::create($livroData);
                $livro->autores()->sync($autor_ids);
            }
        }
    }
}
