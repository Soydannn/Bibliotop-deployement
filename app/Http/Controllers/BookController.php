<?php

namespace App\Http\Controllers;

use App\Services\GoogleBooksService;
use Illuminate\Support\Facades\Http;

class BookController extends Controller
{
    protected GoogleBooksService $googleBooksService;

    public function __construct(GoogleBooksService $googleBooksService)
    {
        $this->googleBooksService = $googleBooksService;
    }

    public function index()
    {
        // On récupère les 10 livres (avec notes + sans notes)
        $books = $this->googleBooksService->getTopRatedBooks('fiction');
        return view('home', compact('books'));

        // Affichage brut pour debug
        dd($books);


    }
    public function show(string $id)
{
    $response = Http::get("https://www.googleapis.com/books/v1/volumes/{$id}");

    if ($response->failed()) {
        abort(404, 'Livre non trouvé.');
    }

    $book = $response->json();

    return view('show', ['book' => $book]);
}
}
