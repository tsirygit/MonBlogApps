<?php

namespace App\Models;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Like extends Model
{
    protected $fillable = [
        'post_id',
        'user_id',
        'like',
    ];

    /**
     * Relation entre le modèle Like et post.
     * Un Like appartient a un post.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }


    /**
     * Relation entre le modèle Like et User.
     * Un Like appartient a un utilisateur.
     */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
