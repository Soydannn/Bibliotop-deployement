<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Favorite;
use Illuminate\Support\Facades\Http;

class FavoriteController extends Controller
{
    public function store($bookId)
    {
        Favorite::firstOrCreate([
            'user_id' => Auth::id(),
            'book_id' => $bookId
        ]);

        return back()->with('success', 'Ajouté aux favoris.');
    }

    public function destroy($bookId)
    {
        Favorite::where('user_id', Auth::id())
            ->where('book_id', $bookId)
            ->delete();

        return back()->with('success', 'Retiré des favoris.');
    }
}
