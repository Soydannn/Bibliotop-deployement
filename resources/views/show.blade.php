<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>{{ $book['volumeInfo']['title'] ?? 'Détail du livre' }} - Biblitop</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white min-h-screen flex flex-col">
    <header class="bg-black text-white px-6 py-4 flex justify-between items-center">
        <div class="text-xl font-bold">Biblitop</div>
        <a href="/" class="text-sm underline hover:text-gray-300">← Retour à l'accueil</a>
    </header>

    <main class="flex-grow container mx-auto px-6 py-8">
        <div class="flex flex-col md:flex-row gap-8">
            @if(isset($book['volumeInfo']['imageLinks']['thumbnail']))
                <img src="{{ $book['volumeInfo']['imageLinks']['thumbnail'] }}" alt="Couverture" class="w-64 h-auto rounded shadow">
            @endif

            <div>
                <h1 class="text-3xl font-bold mb-2">{{ $book['volumeInfo']['title'] ?? 'Titre inconnu' }}</h1>

                <p class="text-gray-700 mb-2">
                    Auteur(s) : 
                    {{ $book['volumeInfo']['authors'][0] ?? 'Inconnu' }}
                </p>

                <p class="text-sm text-gray-600 mb-2">Publié en : {{ $book['volumeInfo']['publishedDate'] ?? 'N/A' }}</p>

                @if(isset($book['volumeInfo']['averageRating']))
                    <p class="text-yellow-500 font-semibold mb-4">⭐ {{ $book['volumeInfo']['averageRating'] }}</p>
                @else
                    <p class="text-gray-400 italic mb-4">Pas de note</p>
                @endif

                <h2 class="text-xl font-semibold mb-2">Description :</h2>
                <p class="text-gray-800 whitespace-pre-line">
                    {!! nl2br(e($book['volumeInfo']['description'] ?? 'Aucune description disponible.')) !!}
                </p>
                @if(isset($book['volumeInfo']['infoLink']))
                <a href="{{ $book['volumeInfo']['infoLink'] }}"
                 target="_blank"
                class="inline-block mt-4 text-blue-600 underline hover:text-blue-800 font-medium">
                Voir ce livre sur Google Books
        </a>
        @endif

        @php
        $bookId = $book['id']; // supposons que $book contient les données du livre depuis l’API
        $userFavorites = auth()->check()
            ? auth()->user()->favorites->pluck('book_id')->toArray()
            : [];
        @endphp

        @auth
        @if (in_array($bookId, $userFavorites))
            <form action="{{ route('favorites.destroy', $bookId) }}" method="POST">
                @csrf
                @method('DELETE')
                <button class="inline-block mt-4 text-blue-600 underline text-red-800 font-medium" type="submit">Retirer des favoris</button>
            </form>
        @else
            <form action="{{ route('favorites.store', $bookId) }}" method="POST">
                @csrf
                <button class="inline-block mt-4 text-blue-600 underline hover:text-blue-800 font-medium" type="submit">Ajouter aux favoris</button>
            </form>
        @endif
        @endauth


            </div>
        </div>
    </main>

    <footer class="bg-black text-white text-center py-4 mt-8">
        &copy; {{ date('Y') }} Biblitop. Tous droits réservés.
    </footer>
</body>
</html>
