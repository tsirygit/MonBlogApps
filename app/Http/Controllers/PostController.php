<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * Contrôleur responsable de la gestion des publications (posts).
 */
class PostController extends Controller
{
    use AuthorizesRequests;

    /**
     * Affiche la liste des publications.
     *
     * @return \Inertia\Response|\Illuminate\Http\Response
     */
    public function index()
    {
        // À implémenter si nécessaire
    }

    /**
     * Affiche le formulaire de création d'une publication.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        if (!Auth::check()) {
            abort(403, 'Accès refusé');
        }

        return Inertia::render('Posts/Create');
    }

    /**
     * Enregistre une nouvelle publication dans la base de données.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'image' => 'nullable|image|mimes:png,jpg,jpeg,svg,gif|max:2048',
            'title' => 'required|string',
            'content' => 'required|string',
        ]);

        $path = null;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('posts', 'public');
        }

        $validated['image'] = $path;
        $validated['user_id'] = Auth::id();

        Post::create($validated);

        return redirect()->route('homepage')->with('success', 'Votre post a été publié avec succès.');
    }

    /**
     * Affiche une publication spécifique.
     *
     * @param Post $post
     * @return \Inertia\Response
     */
    public function show(Post $post)
    {
        $post->load(['comments.user', 'likes', 'user']);
        $post->loadCount(['likes', 'comments']);
        $post->isLiked = $post->likes()->where('user_id', Auth::id())->exists();

        return Inertia::render('Posts/Show', [
            'post' => $post,
        ]);
    }

    /**
     * Affiche le formulaire d'édition d'une publication.
     *
     * @param Post $post
     * @return \Inertia\Response
     */
    public function edit(Post $post)
    {
        $this->authorize('edit', $post);

        return Inertia::render('Posts/Edit', [
            'post' => $post,
        ]);
    }

    /**
     * Met à jour une publication existante.
     *
     * @param Request $request
     * @param Post $post
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'image' => 'nullable|image|mimes:png,jpg,jpeg,svg,gif|max:2048',
            'title' => 'required|string',
            'content' => 'required|string',
        ]);

        $this->authorize('update', $post);

        if ($request->hasFile('image')) {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $path = $request->file('image')->store('posts', 'public');
            $validated['image'] = $path;
        }

        $post->update($validated);

        return redirect()->route('homepage')->with('success', 'Votre post a été modifié avec succès.');
    }

    /**
     * Supprime une publication.
     *
     * @param Post $post
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Post $post)
    {
        $this->authorize('destroy', $post);

        $post->delete();

        return redirect()->route('homepage')->with('success', 'Votre post a été supprimé avec succès.');
    }

    /**
     * Gère le like/dislike d'un post via une requête AJAX.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function ajaxLike(Request $request)
    {
        $request->validate([
            'post_id' => 'required|integer|exists:posts,id',
            'like' => 'nullable|boolean',
        ]);

        $user = Auth::user();
        $postId = $request->post_id;
        $like = $request->like;

        $likeDislike = $user->LikeDislike($postId, $like);

        return redirect()->route('homepage')->with('status', $likeDislike);
    }
}
