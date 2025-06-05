<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
     // Si ta table s'appelle "books" (par défaut Laravel utilise le pluriel du modèle)
    // Sinon précise avec : protected $table = 'nom_table';

    // Si tu veux pouvoir assigner ces champs en masse (mass assignment)
    protected $fillable = ['google_book_id', 'title', 'authors', 'published_date', 'description', 'thumbnail', 'average_rating', 'info_link'];

    /**
     * La relation inverse many-to-many vers User (utilisateurs qui ont ajouté ce livre en favoris)
     */
    public function usersWhoFavorited()
    {
        return $this->belongsToMany(User::class, 'favorites', 'book_id', 'user_id');
    }
}
