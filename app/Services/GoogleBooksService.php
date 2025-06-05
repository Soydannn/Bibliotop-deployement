<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GoogleBooksService
{
    protected string $baseUrl = 'https://www.googleapis.com/books/v1/volumes';

    public function getTopRatedBooks(string $query = 'fiction'): array
    {
        // On récupère jusqu'à 40 résultats pour avoir plus de chance d'en trouver 10 avec notes
        $response = Http::get($this->baseUrl, [
            'q' => $query,
            'maxResults' => 40,
            'orderBy' => 'relevance',
        ]);

        if ($response->failed()) {
            return [];
        }

        $items = $response->json()['items'] ?? [];

        // Séparer livres avec notes et sans notes
        $booksWithRating = array_filter($items, function ($book) {
            return isset($book['volumeInfo']['averageRating']);
        });

        $booksWithoutRating = array_filter($items, function ($book) {
            return !isset($book['volumeInfo']['averageRating']);
        });

        // Trier les livres avec note par ordre décroissant de note
        usort($booksWithRating, function ($a, $b) {
            return $b['volumeInfo']['averageRating'] <=> $a['volumeInfo']['averageRating'];
        });

        // Prendre les 10 premiers livres notés
        $topBooks = array_slice($booksWithRating, 0, 10);

        // Si moins de 10, compléter avec les livres sans note
        if (count($topBooks) < 10) {
            $needed = 10 - count($topBooks);
            $topBooks = array_merge($topBooks, array_slice($booksWithoutRating, 0, $needed));
        }

        return $topBooks;
    }
}
