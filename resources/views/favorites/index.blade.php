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

<main class="flex-grow p-6">
    <h1 class="text-2xl font-bold mb-6">Mes livres favoris</h1>

    @php
        $favorites = auth()->user()->favorites;
    @endphp

    @if ($favorites->isEmpty())
        <p class="text-gray-600">Vous n'avez aucun livre en favori.</p>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @foreach ($favorites as $fav)
                @php
                    $bookData = null;
                    try {
                        $response = Http::get('https://www.googleapis.com/books/v1/volumes/' . $fav->book_id);
                        if ($response->ok()) {
                            $bookData = $response->json();
                        }
                    } catch (\Exception $e) {
                        $bookData = null;
                    }
                @endphp

                @if ($bookData)
                    @php
                        $info = $bookData['volumeInfo'];
                        $title = $info['title'] ?? 'Titre inconnu';
                        $authors = implode(', ', $info['authors'] ?? []);
                        $thumbnail = $info['imageLinks']['thumbnail'] ?? null;
                    @endphp

                    <div class="border rounded-lg shadow p-4 bg-white">
                        @if ($thumbnail)
                            <img src="{{ $thumbnail }}" alt="Couverture" class="w-full h-64 object-cover mb-4">
                        @endif
                        <h2 class="text-lg font-semibold">{{ $title }}</h2>
                        <p class="text-sm text-gray-700 mb-4">{{ $authors }}</p>

                        <form action="{{ route('favorites.destroy', $fav->book_id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition">
                                Retirer des favoris
                            </button>
                        </form>
                    </div>
                @else
                    <p class="text-sm text-red-600">Impossible de récupérer les infos du livre ID : {{ $fav->book_id }}</p>
                @endif
            @endforeach
        </div>
    @endif
</main>
<footer class="bg-gray-100 text-center py-4 text-sm text-gray-600 mt-auto">
    &copy; {{ date('Y') }} Biblitop. Tous droits réservés.
</footer>

</body>
</html>
