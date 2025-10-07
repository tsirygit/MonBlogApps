<?php

namespace App\Events;

use App\Models\Comment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

/**
 * Événement déclenché lorsqu'un nouveau commentaire est créé.
 * Diffusé en temps réel via un canal privé lié à l'auteur du post.
 */
class CommentEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Le commentaire concerné par l'événement.
     *
     * @var Comment
     */
    public $comment;

    /**
     * Crée une nouvelle instance de l'événement.
     *
     * @param Comment $comment
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Définit le canal sur lequel l'événement sera diffusé.
     *
     * @return Channel
     */
    public function broadcastOn(): Channel
    {
        return new PrivateChannel('user.' . $this->comment->post->user_id);
    }
}
