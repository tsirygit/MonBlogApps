<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

/**
 * Politique d'autorisation pour les actions sur les posts.
 *
 * Cette classe définit les règles permettant à un utilisateur
 * de modifier, mettre à jour ou supprimer un post.
 */
class PostPolicy
{
    /**
     * Détermine si l'utilisateur peut modifier le post.
     *
     * @param User $user
     * @param Post $post
     * @return bool
     */
    public function edit(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }

    /**
     * Détermine si l'utilisateur peut mettre à jour le post.
     *
     * @param User $user
     * @param Post $post
     * @return bool
     */
    public function update(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }

    /**
     * Détermine si l'utilisateur peut supprimer le post.
     *
     * @param User $user
     * @param Post $post
     * @return bool
     */
    public function destroy(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }
}
