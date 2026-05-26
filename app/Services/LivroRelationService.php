<?php

namespace App\Services;

use App\Models\Livro;

class LivroRelationService
{
    /**
     * Get related books based on description similarity.
     * 
     * @param Livro $targetBook
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */
    public function getRelatedBooks(Livro $targetBook, $limit = 4)
    {
        $allBooks = Livro::with('autores')->where('id', '!=', $targetBook->id)->get();
        
        $targetTokens = $this->tokenize($targetBook->bibliografia ?? '');

        if (empty($targetTokens)) {
            return collect(); // No meaningful description to relate
        }

        $scoredBooks = [];

        foreach ($allBooks as $book) {
            $bookTokens = $this->tokenize($book->bibliografia ?? '');
            
            // Calculate score based on number of intersecting tokens
            $intersection = array_intersect($targetTokens, $bookTokens);
            $score = count($intersection);
            
            if ($score > 0) {
                $scoredBooks[] = [
                    'book' => $book,
                    'score' => $score
                ];
            }
        }

        // Sort by score descending
        usort($scoredBooks, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        // Get top N books
        return collect(array_slice($scoredBooks, 0, $limit))->pluck('book');
    }

    /**
     * Tokenize text into words, removing common stop words.
     */
    private function tokenize($text)
    {
        $text = mb_strtolower($text, 'UTF-8');
        // Remove punctuation
        $text = preg_replace('/[[:punct:]]+/u', ' ', $text);
        
        $words = preg_split('/\s+/u', $text, -1, PREG_SPLIT_NO_EMPTY);

        $stopWords = [
            'o', 'a', 'os', 'as', 'um', 'uma', 'uns', 'umas',
            'de', 'do', 'da', 'dos', 'das', 'em', 'no', 'na', 'nos', 'nas',
            'por', 'para', 'com', 'sem', 'que', 'e', 'ou', 'mas', 'se', 'como',
            'este', 'esta', 'estes', 'estas', 'esse', 'essa', 'esses', 'essas',
            'aquele', 'aquela', 'aqueles', 'aquelas', 'um', 'uma',
            'é', 'são', 'ser', 'foi', 'era', 'tem', 'têm', 'ter', 'tinha',
            'ao', 'aos', 'à', 'às', 'pelo', 'pela', 'pelos', 'pelas',
            'sobre', 'entre', 'quando', 'onde', 'qual', 'quais', 'quem', 'ele', 'ela', 'eles', 'elas'
        ];

        $filteredWords = array_diff($words, $stopWords);
        
        // Remove short words
        $filteredWords = array_filter($filteredWords, function($word) {
            return mb_strlen($word, 'UTF-8') > 2;
        });

        return array_unique($filteredWords);
    }
}
