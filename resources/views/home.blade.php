<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Biblitop - Accueil</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white min-h-screen flex flex-col">

<!-- Header -->
<header class="bg-black text-white flex items-center justify-between px-6 py-4">
    <div class="text-lg font-bold">Biblitop</div>
    <a href="{{ route('home') }}">Page d'accueil</a>
    <a href="{{ route('favorites.index') }}">Mes Favoris</a>
    <div class="space-x-4">
        @auth
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="bg-red-600 px-4 py-1 rounded hover:bg-red-700 transition">
                    Se déconnecter
                </button>
            </form>
        @else
            <a href="{{ route('register') }}" class="bg-transparent border border-white px-4 py-1 rounded hover:bg-white hover:text-black transition">
                S'inscrire
            </a>
            <a href="{{ route('login') }}" class="bg-white text-black px-4 py-1 rounded hover:bg-gray-300 transition">
                Se connecter
            </a>
        @endauth
    </div>
</header>

<!-- Main content -->
<main class="flex-grow container mx-auto px-6 py-8">
    <h2 class="text-2xl font-semibold mb-6">Livres populaires</h2>

    @if(count($books) > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($books as $book)
                <div class="border rounded shadow p-4 flex flex-col">
                    @if(isset($book['volumeInfo']['imageLinks']['thumbnail']))
                        <img src="{{ $book['volumeInfo']['imageLinks']['thumbnail'] }}" alt="{{ $book['volumeInfo']['title'] }}" class="mb-4 w-full h-48 object-cover rounded" />
                    @else
                        <div class="bg-gray-200 mb-4 h-48 flex items-center justify-center rounded text-gray-500">Pas d'image</div>
                    @endif

                    <h3 class="font-bold text-lg mb-1">{{ $book['volumeInfo']['title'] }}</h3>
                    <p class="text-gray-700 mb-2">
                        Auteur : 
                        @if(isset($book['volumeInfo']['authors']))
                            {{ implode(', ', $book['volumeInfo']['authors']) }}
                        @else
                            Inconnu
                        @endif
                    </p>
                    <p class="text-sm text-gray-600 mb-2">
                        Publié en : {{ $book['volumeInfo']['publishedDate'] ?? 'N/A' }}
                    </p>
                    @if(isset($book['volumeInfo']['averageRating']))
                        <p class="text-yellow-500 font-semibold">⭐ {{ $book['volumeInfo']['averageRating'] }}</p>
                    @else
                        <p class="text-gray-400 italic">Pas de note</p>
                    @endif

                    <a href="{{ route('show', ['id' => $book['id']]) }}" class="text-blue-600 hover:underline mt-2">Voir plus</a>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-600">Aucun livre disponible pour le moment.</p>
    @endif
</main>

<!-- Footer -->
<footer class="bg-black text-white text-center py-4 mt-8">
    &copy; {{ date('Y') }} Biblitop. Tous droits réservés.
</footer>

</body>
</html>
