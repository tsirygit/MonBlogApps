<?php

namespace App\Http\Controllers;

use App\Events\CommentEvent;
use App\Models\Post;
use App\Models\Comment;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * Contrôleur responsable de la gestion des commentaires.
 */
class CommentController extends Controller
{
    use AuthorizesRequests;

    /**
     * Affiche le formulaire de création d'un commentaire pour un post donné.
     *
     * @param Post $post
     * @return \Inertia\Response|\Illuminate\Http\Response
     */
    public function create(Post $post)
    {
        if (!Auth::check()) {
            abort(403, 'Accès refusé');
        }

        return Inertia::render('Comments/Create', [
            'post' => $post,
        ]);
    }

    /**
     * Enregistre un nouveau commentaire dans la base de données.
     * Déclenche un événement CommentEvent après création.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'post_id' => 'required|integer|exists:posts,id',
            'content' => 'required|string',
        ]);

        $validated['user_id'] = Auth::id();

        $comment = Comment::create($validated);
        $comment->load('user', 'post');

        event(new CommentEvent($comment));

        return redirect()
            ->route('post.show', $comment->post_id)
            ->with('success', 'Vous avez commenté la publication avec succès.');
    }

    /**
     * Affiche le formulaire d'édition d'un commentaire.
     *
     * @param Comment $comment
     * @return \Inertia\Response
     */
    public function edit(Comment $comment)
    {
        $this->authorize('edit', $comment);

        return Inertia::render('Comments/Edit', [
            'comment' => $comment,
        ]);
    }

    /**
     * Met à jour le contenu d'un commentaire existant.
     *
     * @param Request $request
     * @param Comment $comment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Comment $comment)
    {
        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        $this->authorize('update', $comment);

        $comment->update($validated);

        return redirect()
            ->route('post.show', $comment->post_id)
            ->with('success', 'Votre commentaire a été modifié avec succès.');
    }

    /**
     * Supprime un commentaire de la base de données.
     *
     * @param Comment $comment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Comment $comment)
    {
        $this->authorize('destroy', $comment);

        $comment->delete();

        return redirect()
            ->route('post.show', $comment->post_id)
            ->with('success', 'Votre commentaire a été supprimé avec succès.');
    }
}
