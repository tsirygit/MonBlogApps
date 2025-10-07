<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

/**
 * Contrôleur de la page d'accueil.
 * Affiche les publications avec leurs auteurs, likes et commentaires.
 */
class HomepageController extends Controller
{
    /**
     * Affiche la page d'accueil avec la liste des publications.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        // Récupère tous les posts avec leurs relations
        $posts = Post::with(['user', 'likes'])
            ->withCount(['likes', 'comments'])
            ->get();

        // Ajoute une propriété isLiked pour chaque post selon l'utilisateur connecté
        foreach ($posts as $post) {
            $post->isLiked = $post->likes()
                ->where('user_id', Auth::id())
                ->exists();
        }

        // Rend la vue Inertia avec les données des posts
        return Inertia::render('Homepage', [
            'posts' => $posts,
        ]);
    }
}
