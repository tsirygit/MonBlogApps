<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Comment;

/**
 * Politique d'autorisation pour les actions sur les commentaires.
 *
 * Cette classe définit les règles permettant à un utilisateur
 * de modifier, mettre à jour ou supprimer un commentaire.
 */
class CommentPolicy
{
    /**
     * Détermine si l'utilisateur peut modifier le commentaire.
     *
     * @param User $user
     * @param Comment $comment
     * @return bool
     */
    public function edit(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id;
    }

    /**
     * Détermine si l'utilisateur peut mettre à jour le commentaire.
     *
     * @param User $user
     * @param Comment $comment
     * @return bool
     */
    public function update(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id;
    }

    /**
     * Détermine si l'utilisateur peut supprimer le commentaire.
     *
     * @param User $user
     * @param Comment $comment
     * @return bool
     */
    public function destroy(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id;
    }
}
